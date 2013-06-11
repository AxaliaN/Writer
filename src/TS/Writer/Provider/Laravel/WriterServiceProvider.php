<?php

namespace TS\Writer\Provider\Laravel;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\EventDispatcher\EventDispatcher;
use TS\Writer\FileWriterFactory;

/**
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.1
 */
class WriterServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    protected $defer = true;

    /**
     * {@inheritDoc}
     */
    public function boot()
    {
    }

    /**
     * Registers the reader with the Laravel Container.
     */
    public function register()
    {
        $this->registerSymfonyDispatcher();

        $this->app['writer'] = $this->app->share(
            function ($app) {
                $factory = new FileWriterFactory($app['symfony.dispatcher']);

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
     * Registers the Symfony EventDispatcher with the Laravel Container.
     */
    public function registerSymfonyDispatcher()
    {
        try {
            $this->app['symfony.dispatcher'];
        } catch (\ReflectionException $e) {
            $this->app['symfony.dispatcher'] = $this->app->share(
                function ($app) {
                    return new EventDispatcher;
                }
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function provides()
    {
        return array('writer', 'symfony.dispatcher');
    }
}
