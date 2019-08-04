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
     * Register publish groups.
     *
     * @return void
     * @author Morten Rugaard <moru@nodes.dk>
     */
    protected function publishGroups()
    {
        // Config files
        $this->publishes([
            __DIR__ . '/../config/nstack.php' => config_path('nstack.php'),
        ], 'config');
    }

    /**
     * Setup container binding.
     *
     * @return void
     * @author Casper Rasmussen <moru@nodes.dk>
     */
    protected function setupBindings()
    {
        $this->app->bind(NStack::classc, function ($app) {
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