<?php

namespace NStack;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * boot
     *
     * @author Casper Rasmussen <cr@nodes.dk>
     */
    public function boot()
    {
        $this->publishGroups();
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
