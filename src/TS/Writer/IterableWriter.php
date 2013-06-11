<?php

namespace TS\Writer;

use TS\Writer\Event\IterationEvent;
use TS\Writer\Event\WriterEvent;

/**
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.1
 */
abstract class IterableWriter extends AbstractWriter implements IterableWriterInterface
{
    /**
     * @var array
     */
    protected $lastLine;

    /**
     * Morphs the data, so that we can hook into and validate each subset of our data array.
     *
     * @return mixed
     */
    protected function morphData()
    {
        $this->setLastLine($this->current());

        $this->eventDispatcher->dispatch(WriterEvents::BEFORE_WRITE, new IterationEvent($this));

        return $this->getLastLine();
    }

    /**
     * Iterator::current()
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->data);
    }

    /**
     * Gets the data array for the current line.
     *
     * @return array
     */
    public function getLastLine()
    {
        return $this->lastLine;
    }

    /**
     * Iterator::key()
     *
     * @return mixed
     */
    public function key()
    {
        return key($this->data);
    }

    /**
     * Iterator::next()
     */
    public function next()
    {
        next($this->data);
    }

    /**
     * Iterator::rewind()
     */
    public function rewind()
    {
        reset($this->data);
    }

    /**
     * Sets the data array for the current line.
     *
     * @param  mixed $lastLine
     * @return static
     */
    public function setLastLine($lastLine = null)
    {
        $this->lastLine = $lastLine;

        return $this;
    }

    /**
     * Iterator::valid()
     *
     * @return bool
     */
    public function valid()
    {
        return $this->key() !== null;
    }

    /**
     * Writes all data.
     *
     * @return bool
     */
    public function writeAll()
    {
        $this->eventDispatcher->dispatch(WriterEvents::WRITE_ALL, new WriterEvent($this));

        for ($this->rewind(); $this->valid(); $this->next()) {
            $this->write();
        }

        $this->eventDispatcher->dispatch(WriterEvents::WRITE_COMPLETE, new WriterEvent($this));

        return true;
    }
}
