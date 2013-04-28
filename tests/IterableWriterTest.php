<?php

use Symfony\Component\EventDispatcher\EventDispatcher;
use TS\Writer\Event\IterationEvent;
use TS\Writer\IterableWriter;
use TS\Writer\WriterEvents;

class ArrayWriter extends IterableWriter
{
    private $array = array();

    public function getArray()
    {
        return $this->array;
    }

    public function write()
    {
        $success = $this->valid();
        $data    = $this->morphData();

        if ($success && $data !== null) {
            $this->eventDispatcher->dispatch(WriterEvents::WRITE, new IterationEvent($this));

            $this->array[] = $data;
        }

        return $success;
    }
}

class IterableWriterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ArrayWriter
     */
    private $writer;

    protected function setUp()
    {
        $this->writer = new ArrayWriter(new EventDispatcher);
    }

    protected function tearDown()
    {
        $this->writer = null;
    }

    public function testWhatever()
    {
        $data = array(1, 2, 3, 4, 5);

        $this->writer->setData($data);

        $this->writer->writeAll();

        $this->assertEquals($data, $this->writer->getArray());
    }
}
