<?php

namespace TS\Writer\Provider\Silex;

use Silex\Application;
use Silex\ServiceProviderInterface;
use TS\Writer\FileWriterFactory;

/**
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.0
 */
class WriterServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers the Writer with the Silex Container.
     *
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app['writer'] = $app->share(
            function ($app) {
                $factory = new FileWriterFactory($app['dispatcher']);

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
     * {@inheritDoc}
     *
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }
}
