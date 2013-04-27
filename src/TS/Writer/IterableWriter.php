<?php

namespace TS\Writer;

use TS\Writer\Event\WriterEvent;

/**
 * IterableWriter
 *
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.0
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
     * @param  array $lastLine
     * @return array
     */
    protected function morphData(array $lastLine)
    {
        $this->setLastLine($lastLine);

        $this->eventDispatcher->dispatch(WriterEvents::BEFORE_WRITE, new WriterEvent($this));

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
     * @param  mixed  $lastLine
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
