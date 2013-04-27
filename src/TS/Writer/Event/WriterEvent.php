<?php

namespace TS\Writer\Event;

use Symfony\Component\EventDispatcher\Event;
use TS\Writer\WriterInterface;

/**
 * WriterEvent
 *
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.0
 */
class WriterEvent extends Event
{
    /**
     * @var WriterInterface
     */
    protected $writer;

    /**
     * @param WriterInterface $writer
     */
    public function __construct(WriterInterface $writer)
    {
        $this->writer = $writer;
    }

    /**
     * Returns the previously set data array.
     *
     * @return array
     */
    public function getData()
    {
        return $this->writer->getData();
    }

    /**
     * Returns the Writer.
     *
     * @return WriterInterface
     */
    public function getWriter()
    {
        return $this->writer;
    }
}
