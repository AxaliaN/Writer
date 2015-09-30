<?php

namespace TS\Writer\Event;

use TS\Writer\IterableWriterInterface;

/**
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2014
 * @version   1.2
 */
class IterationEvent extends WriterEvent
{
    /**
     * @var IterableWriterInterface
     */
    protected $writer;

    /**
     * @param IterableWriterInterface $writer
     */
    public function __construct(IterableWriterInterface $writer)
    {
        $this->writer = $writer;
    }

    /**
     * Returns the last line of the IterableWriter.
     *
     * @return array
     */
    public function getLastLine()
    {
        return $this->writer->getLastLine();
    }
}
