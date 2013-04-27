<?php

namespace TS\Writer\Implementation;

use TS\Writer\FileWriter;

/**
 * Json
 *
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.0
 */
class Json extends FileWriter
{
    /**
     * @var bool
     */
    private $compatMode = false;

    /**
     * @var int
     */
    private $indentation = 4;

    /**
     * @var string
     */
    private $lineBreak = "\n";

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
     * Manually set the Writer to use the compability encoding method.
     *
     * @param  bool   $compatMode
     * @return static
     */
    public function setCompabilityMode($compatMode = true)
    {
        $this->compatMode = $compatMode;

        return $this;
    }

    /**
     * Sets the number of spaces used for indentation.
     *
     * @param  int    $indentation
     * @return static
     */
    public function setIndentation($indentation = 4)
    {
        $this->indentation = $indentation;

        return $this;
    }

    /**
     * Sets the line break character to use when dumping json.
     *
     * @param  string $lineBreak
     * @return static
     */
    public function setLineBreak($lineBreak = "\n")
    {
        $this->lineBreak = $lineBreak;

        return $this;
    }

    /**
     * Sets the given $option flag, adding or removing it from the internal flag set.
     *
     * @param  int    $option
     * @param  bool   $enable
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
     * @param  bool   $prettyPrint
     * @return static
     */
    public function setPrettyPrint($prettyPrint = true)
    {
        $this->prettyPrint = $prettyPrint;

        return $this;
    }

    /**
     * Returns the types supported by the Writer using an indexed array - lower keys
     * have higher prevalence.
     *
     * @return array
     */
    public function supportedTypes()
    {
        return array('json');
    }
}
