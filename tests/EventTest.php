<?php

namespace TS\Writer\Tests;

use PHPUnit_Framework_TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use TS\Writer\Event\IterationEvent;
use TS\Writer\Event\WriterEvent;
use TS\Writer\Implementation\Txt;
use TS\Writer\WriterEvents;

/**
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.1
 */
class EventTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        @unlink(__DIR__ . '/tmp/eventTest.txt');
    }

    public function testEventPropagation()
    {
        $dispatcher = new EventDispatcher();

        $listenedEvents = array(
            WriterEvents::BEFORE_WRITE   => false,
            WriterEvents::INIT           => false,
            WriterEvents::WRITE          => false,
            WriterEvents::WRITE_ALL      => false,
            WriterEvents::WRITE_COMPLETE => false,
        );

        $dispatcher->addListener(
            WriterEvents::BEFORE_WRITE,
            function () use (&$listenedEvents) {
                $listenedEvents[WriterEvents::BEFORE_WRITE] = true;
            }
        );

        $dispatcher->addListener(
            WriterEvents::INIT,
            function () use (&$listenedEvents) {
                $listenedEvents[WriterEvents::INIT] = true;
            }
        );

        $dispatcher->addListener(
            WriterEvents::WRITE,
            function () use (&$listenedEvents) {
                $listenedEvents[WriterEvents::WRITE] = true;
            }
        );

        $dispatcher->addListener(
            WriterEvents::WRITE_ALL,
            function () use (&$listenedEvents) {
                $listenedEvents[WriterEvents::WRITE_ALL] = true;
            }
        );

        $dispatcher->addListener(
            WriterEvents::WRITE_COMPLETE,
            function () use (&$listenedEvents) {
                $listenedEvents[WriterEvents::WRITE_COMPLETE] = true;
            }
        );

        $writer = new Txt($dispatcher);
        $writer->setTargetFile(__DIR__ . '/tmp/eventTest.txt');
        $writer->setData(array('Just a line.'));

        $this->assertTrue($writer->writeAll());

        foreach ($listenedEvents as $event) {
            $this->assertTrue($event);
        }
    }

    public function testEventAccessors()
    {
        $writer = new Txt(new EventDispatcher());

        $data = array('Just a line.');
        $writer->setData($data);

        $lastLine = 'blah';
        $writer->setLastLine($lastLine);

        $iterationEvent = new IterationEvent($writer);
        $writerEvent    = new WriterEvent($writer);

        $this->assertSame($writer, $iterationEvent->getWriter());
        $this->assertSame($writer, $writerEvent->getWriter());

        $this->assertSame($data, $iterationEvent->getData());
        $this->assertSame($data, $writerEvent->getData());

        $this->assertSame($lastLine, $iterationEvent->getLastLine());
    }
}
