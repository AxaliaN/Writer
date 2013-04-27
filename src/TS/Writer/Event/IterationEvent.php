<?php

namespace TS\Writer\Event;

use TS\Writer\IterableWriterInterface;

/**
 * IterationEvent
 *
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.0
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
     * Returns the last line written of the IterableWriter.
     *
     * @return array
     */
    public function getLastLine()
    {
        return $this->writer->getLastLine();
    }
}
