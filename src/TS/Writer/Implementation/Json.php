<?php

namespace TS\Writer\Implementation;

use TS\Writer\FileWriter;

/**
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.1
 */
class Json extends FileWriter
{
    /**
     * @var int
     */
    private $options = 0;

    /**
     * @var bool
     */
    private $prettyPrint = true;

    /**
     * Returns the options for json_encode, adding JSON_PRETTY_PRINT if pretty printing is enabled.
     *
     * @return int
     */
    protected function options()
    {
        $options = $this->options;

        if ($this->prettyPrint === true) {
            $options |= JSON_PRETTY_PRINT;
        }

        return $options;
    }

    /**
     * Dumps the data array as a string.
     *
     * @return string
     */
    public function dumpData()
    {
        return json_encode($this->data, $this->options());
    }

    /**
     * Sets the given $option flag, adding or removing it from the internal flag set.
     *
     * @param  int  $option
     * @param  bool $enable
     * @return static
     */
    public function setOption($option, $enable = true)
    {
        if ($option == JSON_PRETTY_PRINT) {
            $this->prettyPrint = $enable;
        }

        if ($enable === true) {
            $this->options |= (int)$option;
        } else {
            $this->options &= (int)~$option;
        }

        return $this;
    }

    /**
     * Whether or not to use pretty printing.
     *
     * @param  bool $prettyPrint
     * @return static
     */
    public function setPrettyPrint($prettyPrint = true)
    {
        $this->prettyPrint = $prettyPrint;

        return $this;
    }

    /**
     * Returns the types supported by the Writer using an indexed array.
     *
     * @return array
     */
    public function supportedTypes()
    {
        return array('json');
    }
}
