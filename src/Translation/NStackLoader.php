<?php

namespace NStack\Translation;

use Illuminate\Translation\FileLoader;
use Illuminate\Filesystem\Filesystem;
use NStack\NStack;
use NStack\Clients\LocalizeClient;
use NStack\Models\Resource;
use Illuminate\Support\Collection;
use Carbon\Carbon;

/**
 * NStackLoader for translations
 *
 * @author Pawel Wilk <pawi@nodesagency.com>
 *
 */
class NStackLoader extends FileLoader
{
    /**
     * @var NStack
     */
    protected $nstack;

    /**
     * @var LocalizeClient|null
     */
    protected $client;

    /**
     * @var string
     */
    protected $platform;

    /**
     * @var int
     */
    protected $cacheTime;

    /**
     * To avoid spamming the service, only retry the service if retry is above this count or sec below.
     *
     * @var int
     */
    protected $maxNetworkRetries;

    /**
     * To avoid spamming the service, only retry the service if retry is above the count above or this sec.
     *
     * @var int
     */
    protected $retryNetworkAfterSec;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $failedCarbons;

    /**
     * Constructor
     *
     * @param Filesystem $files
     * @param string $path
     * @param NStack $nstack
     */
    public function __construct(Filesystem $files, $path, NStack $nstack) {
        parent::__construct($files, $path);

        $this->nstack = $nstack;
        $this->failedCarbons = new Collection();

        $this->platform = config('nstack.platform');
        $this->cacheTime = config('nstack.cacheTime', 600);
        $this->maxNetworkRetries = config('nstack.maxNetworkRetries', 3);
        $this->retryNetworkAfterSec = config('nstack.retryNetworkAfterSec', 10);
    }

    /**
     * {@inheritDoc}
     * @see \Illuminate\Translation\LoaderInterface::load()
     */
    public function load($locale, $group, $namespace = null)
    {
        if (!is_null($namespace) && ($namespace != '*')) {
            return $this->loadNamespaced($locale, $group, $namespace);
        }

        if ($resource = $this->findResource($locale)) {
            $data = $this->loadTranslations($resource);

            if (isset($data[$group])) {
                return $data[$group];
            }
        }

        return parent::load($locale, $group);
    }

    /**
     * Download and cache translations
     *
     * @param Resource $resource
     * @param bool $refresh
     *
     * @return array
     */
    protected function loadTranslations(Resource $resource, $refresh = true)
    {
        $cacheKey = sprintf('nstack.resource.%d', $resource->getId());

        if (($data = \Cache::get($cacheKey)) && !$refresh) {
            return $data;
        }

        $response = $this->request(function () use ($resource) {
            return $this->getClient()->showResource($resource->getUrl());
        });

        if (empty($response['data'])) {
            return [];
        }

        \Cache::put($cacheKey, $response['data'], $this->cacheTime);

        return $response['data'];
    }

    protected function request(\Closure $closure)
    {
        try {
            return $closure();
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            if ($e->getCode() == 403) {
                throw new \Exception('Invalid credentials');
            }

            $this->performFail();
        }

        if ($this->shouldTryAgain()) {
            sleep(1);

            return $this->request($closure);
        } else {
            throw new \Exception('Maximum amount retries reached');
        }
    }

    /**
     * Find resource corresponding to locale
     *
     * @param string $locale
     * @return \NStack\Models\Resource|boolean
     */
    protected function findResource($locale)
    {
        foreach ($this->getResources() as $resource) { /* @var $resource \NStack\Models\Resource */
            if (locale_filter_matches($resource->getLanguage()->getLocale(), $locale)) {
                return $resource;
            }
        }

        return false;
    }

    /**
     * Return nstack localize client
     *
     * @return \NStack\Clients\LocalizeClient
     */
    protected function getClient()
    {
        if (is_null($this->client)) {
            $this->client = new LocalizeClient($this->nstack->getConfig());
        }

        return $this->client;
    }

    /**
     * Get available resources for platform
     *
     * @return Resource[]
     */
    protected function getResources($force = false)
    {
        $cacheKey = 'nstack.availableLocales';

        if (($data = \Cache::get($cacheKey)) && !$force) {
            return $data;
        }

        $response = $this->request(function () {
            return $this->getClient()->indexResources($this->platform);
        });

        if (empty($response)) {
            return [];
        }

        \Cache::put($cacheKey, $response, $this->cacheTime);

        return $response;
    }

    /**
     * shouldTryAgain.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @return bool
     */
    private function shouldTryAgain()
    {
        if ($this->failedCarbons->count() < $this->maxNetworkRetries) {
            return true;
        }

        /** @var Carbon $carbon */
        $carbon = $this->failedCarbons->first();
        if ($carbon->diffInSeconds(Carbon::now()) >= $this->retryNetworkAfterSec) {
            return true;
        }

        return false;
    }

    /**
     * performFail.
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     * @return void
     */
    private function performFail()
    {
        $this->failedCarbons->prepend(new Carbon());
        if ($this->failedCarbons->count() > 3) {
            $this->failedCarbons->pop();
        }
    }
}
