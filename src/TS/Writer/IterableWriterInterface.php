<?php

namespace TS\Writer;

use Iterator;

/**
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2014
 * @version   1.2
 */
interface IterableWriterInterface extends WriterInterface, Iterator
{
    /**
     * Gets the data array for the current line.
     *
     * @return mixed
     */
    public function getLastLine();

    /**
     * Sets the data array for the current line.
     *
     * @param mixed $lastLine
     */
    public function setLastLine($lastLine = null);

    /**
     * Writes a single line.
     *
     * @return bool
     */
    public function write();
}
