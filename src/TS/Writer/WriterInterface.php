<?php

namespace TS\Writer;

/**
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2014
 * @version   1.2
 */
interface WriterInterface
{
    /**
     * Returns the previously set data array.
     *
     * @return array
     */
    public function getData();

    /**
     * Sets the data array to be written.
     *
     * @param  array $data
     * @return static
     */
    public function setData(array $data);

    /**
     * Writes all data.
     *
     * @return bool
     */
    public function writeAll();
}
