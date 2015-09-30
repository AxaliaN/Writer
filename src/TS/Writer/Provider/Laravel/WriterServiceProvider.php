<?php

namespace TS\Writer\Provider\Laravel;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\EventDispatcher\EventDispatcher;
use TS\Writer\FileWriterContainer;

/**
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2014
 * @version   1.2
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
                $container = new FileWriterContainer($app['symfony.dispatcher']);

                $container->registerWriter('TS\\Writer\\Implementation\\Csv', 'csv');
                $container->registerWriter('TS\\Writer\\Implementation\\Ini', 'ini');
                $container->registerWriter('TS\\Writer\\Implementation\\Json', 'json');
                $container->registerWriter('TS\\Writer\\Implementation\\Txt', 'txt');
                $container->registerWriter('TS\\Writer\\Implementation\\Xml', 'xml');
                $container->registerWriter('TS\\Writer\\Implementation\\Yaml', array('yml', 'yaml'));

                return $container;
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
