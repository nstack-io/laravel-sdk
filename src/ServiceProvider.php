<?php

namespace NStack;

use Illuminate\Support\Str;
use Illuminate\Translation\TranslationServiceProvider;
use NStack\Translation\NStackLoader;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends TranslationServiceProvider
{
    /**
     * boot
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     */
    public function boot()
    {
        if (!Str::contains($this->app->version(), 'Lumen')) {
            $this->publishGroups();
        }
    }

    /**
     * register
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     */
    public function register()
    {
        $this->registerManager();
        $this->setupBindings();
        parent::register();
    }

    /**
     * publishGroups
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     */
    protected function publishGroups()
    {
        $this->publishes([
            __DIR__.'/../config' => config_path(),
        ], 'config');
    }

    /**
     * Setup container binding.
     *
     * @return void
     * @author Casper Rasmussen <cr@nodes.dk>
     */
    protected function setupBindings()
    {
        $this->app->bind(NStack::class, function ($app) {
            return $app['nstack'];
        });
    }

    /**
     * {@inheritDoc}
     * @see \Illuminate\Translation\TranslationServiceProvider::registerLoader()
     */
    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new NStackLoader($app['files'], $app['path.lang'], $app->get('nstack'));
        });
    }

    /**
     * Register assets manager.
     *
     * @return void
     * @author Casper Rasmussen <cr@nodes.dk>
     */
    public function registerManager()
    {
        $this->app->singleton('nstack', function ($app) {
            $configArray = config('nstack');

            $config = Config::createFromArray($configArray);

            return new NStack($config);
        });
    }
}
