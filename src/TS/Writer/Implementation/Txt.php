<?php

namespace TS\Writer\Implementation;

use TS\Writer\IterableFileWriter;

/**
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.0
 */
class Txt extends IterableFileWriter
{
    /**
     * Actual line writing logic.
     *
     * @param  mixed $data
     * @return bool
     */
    protected function writeLine($data)
    {
        return (bool)@file_put_contents($this->file, $data . $this->lineEnding, $this->mode);
    }

    /**
     * Dumps the data array as a string.
     *
     * @return string
     */
    public function dumpData()
    {
        $dump = '';

        foreach ($this->data as $value) {
            $dump .= (string)$value . $this->lineEnding;
        }

        return $dump;
    }

    /**
     * Returns the types supported by the Writer using an indexed array - lower keys
     * have higher prevalence.
     *
     * @return array
     */
    public function supportedTypes()
    {
        return array('txt');
    }
}
