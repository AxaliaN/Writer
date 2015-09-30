<?php

namespace TS\Writer\Provider\Silex\Tests;

use PHPUnit_Framework_TestCase;
use Silex\Application;
use TS\Writer\Provider\Silex\WriterServiceProvider;

/**
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2014
 * @version   1.2
 */
class WriterServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Application
     */
    private $application;

    protected function setUp()
    {
        $this->application = new Application;
    }

    public function testRegistration()
    {
        $this->application->register(new WriterServiceProvider);

        $this->assertInstanceOf('TS\\Writer\\FileWriterContainer', $this->application['writer']);
    }

    public function testBoot()
    {
        $this->application->register(new WriterServiceProvider);
        $this->application->boot();
    }

    /**
     * @dataProvider writerClasses
     */
    public function testImplementationsRegisteredAndWorking($type, $writerClass)
    {
        $this->application->register(new WriterServiceProvider);

        $writer = $this->application['writer']->createForType($type);

        $this->assertInstanceOf($writerClass, $writer);
    }

    public function writerClasses()
    {
        return array(
            array('csv', 'TS\\Writer\\Implementation\\Csv'),
            array('ini', 'TS\\Writer\\Implementation\\Ini'),
            array('json', 'TS\\Writer\\Implementation\\Json'),
            array('txt', 'TS\\Writer\\Implementation\\Txt'),
            array('xml', 'TS\\Writer\\Implementation\\Xml'),
            array('yml', 'TS\\Writer\\Implementation\\Yaml'),
        );
    }
}
