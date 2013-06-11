<?php

namespace TS\Writer;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TS\Writer\Event\WriterEvent;

/**
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.1
 */
abstract class AbstractWriter implements WriterInterface
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;

        $this->eventDispatcher->dispatch(WriterEvents::INIT, new WriterEvent($this));
    }

    /**
     * @param  EventDispatcherInterface $eventDispatcher
     * @return static
     */
    public static function factory(EventDispatcherInterface $eventDispatcher)
    {
        return new static($eventDispatcher);
    }

    /**
     * Returns the previously set data array.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets the data array to be written.
     *
     * @param  array $data
     * @return static
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }
}
