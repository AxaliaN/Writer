<?php

namespace TS\Writer\Provider\Laravel;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\EventDispatcher\EventDispatcher;
use TS\Writer\FileWriterFactory;

/**
 * WriterServiceProvider
 *
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.0
 */
class WriterServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the Symfony EventDispatcher.
     */
    protected function registerSymfonyDispatcher()
    {
        try {
            $this->app['symfony.dispatcher'];
        } catch (\Exception $e) {
            $this->app['symfony.dispatcher'] = $this->app->share(
                function () {
                    return new EventDispatcher();
                }
            );
        }
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['writer'] = $this->app->extend(
            'reader',
            function ($factory, $app) {
                /** @var FileWriterFactory $factory */
                $factory->registerWriter('TS\\Writer\\Implementation\\Csv');
                $factory->registerWriter('TS\\Writer\\Implementation\\Ini');
                $factory->registerWriter('TS\\Writer\\Implementation\\Json');
                $factory->registerWriter('TS\\Writer\\Implementation\\Txt');
                $factory->registerWriter('TS\\Writer\\Implementation\\Xml');
                $factory->registerWriter('TS\\Writer\\Implementation\\Yaml');

                return $factory;
            }
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('writer', 'symfony.dispatcher');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSymfonyDispatcher();

        $this->app['writer'] = $this->app->share(
            function ($app) {
                return new FileWriterFactory($app['symfony.dispatcher']);
            }
        );
    }
}
