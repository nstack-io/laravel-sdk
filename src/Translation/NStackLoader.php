<?php

namespace NStack\Translation;

use Illuminate\Translation\FileLoader;
use Illuminate\Filesystem\Filesystem;
use NStack\NStack;
use NStack\Clients\LocalizeClient;
use NStack\Models\Resource;
use Illuminate\Support\Collection;

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
    protected $cacheTime = 600;

    /**
     * Constructor
     *
     * @param Filesystem $files
     * @param unknown $path
     * @param NStack $nstack
     */
    public function __construct(Filesystem $files, $path, NStack $nstack) {
        parent::__construct($files, $path);

        $this->nstack = $nstack;
        $this->platform = config('nstack.platform');
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
            $data = $this->downloadResource($resource);

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
     * @return array
     */
    protected function downloadResource(Resource $resource, $force = false)
    {
        return \Cache::remember(sprintf('nstack.resource.%d', $resource->getId()), $this->cacheTime, function () use ($resource) {
            return $this->getClient()->showResource($resource->getUrl())['data'];
        });
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
        return \Cache::remember('nstack.availableLocales', $this->cacheTime, function () {
            return $this->getClient()->indexResources($this->platform);
        });
    }
}
