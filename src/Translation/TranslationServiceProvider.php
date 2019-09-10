<?php

namespace NStack\Translation;

use Illuminate\Translation\TranslationServiceProvider as ServiceProvider;

/**
 * TranslationServiceProvider
 *
 * @author Pawel Wilk <pawi@nodesagency.com>
 *
 */
class TranslationServiceProvider extends ServiceProvider
{
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
}
