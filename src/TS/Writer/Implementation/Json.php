<?php

namespace TS\Writer\Implementation;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TS\Writer\FileWriter;
use TS\Writer\Exception\DumpingException;

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
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($eventDispatcher);

        if (version_compare(PHP_VERSION, '5.4.0', '<')) {
            $this->compatMode = true;

            define('JSON_UNESCAPED_SLASHES', 64);
            define('JSON_PRETTY_PRINT', 128);
            define('JSON_UNESCAPED_UNICODE', 256);
        }
    }

    /**
     * Compability json_encode method for PHP versions < 5.4. Taken from upgrade.php.
     *
     * @param  array            $data
     * @param  int              $options
     * @param  string           $indent
     * @return string
     * @throws DumpingException
     */
    protected function compatEncode($data, $options = 0, $indent = '')
    {
        $obj = ($options & JSON_FORCE_OBJECT);

        list($space, $tab, $nl) = ($options & JSON_PRETTY_PRINT)
            ? array(' ', str_repeat(' ', $this->indentation) . $indent, $this->lineBreak)
            : array('', '', '');

        if ($options & JSON_NUMERIC_CHECK and is_string($data) and is_numeric($data)) {
            $data = (strpos($data, '.') || strpos($data, 'e') ? (float) $data : (int) $data);
        }

        if (is_array($data) || ($obj = is_object($data))) {
            if ( ! $obj) {
                $keys = array_keys((array) $data);
                $obj  = ! ($keys == array_keys($keys));
            }

            $empty = 0;
            $json  = '';

            foreach ((array) $data as $key => $value) {
                $json .= ($empty++ ? ',' . $nl : '')
                    . $tab . ($obj ? ($this->compatEncode($key, $options, $tab) . ':' . $space) : '')
                    . ($this->compatEncode($value, $options, $tab));
            }

            $json = $obj ? '{' . $nl . $json . $nl . $indent . '}' : '[' . $nl . $json . $nl . $indent . ']';
        } elseif (is_string($data)) {
            if ( ! utf8_decode($data)) {
                throw new DumpingException('Invalid UTF-8 encoding in string [' . $data . '].');
            }

            $rewrite = array(
                "\\"   => "\\\\",
                "\""   => "\\\"",
                "\010" => "\\b",
                "\f"   => "\\f",
                "\n"   => "\\n",
                "\r"   => "\\r",
                "\t"   => "\\t",
                '/'    => $options & JSON_UNESCAPED_SLASHES ? '/'       : "\\/",
                '<'    => $options & JSON_HEX_TAG           ? "\\u003C" : '<',
                '>'    => $options & JSON_HEX_TAG           ? "\\u003E" : '>',
                "'"    => $options & JSON_HEX_APOS          ? "\\u0027" : "'",
                '"'    => $options & JSON_HEX_QUOT          ? "\\u0022" : "\"",
                '&'    => $options & JSON_HEX_AMP           ? "\\u0026" : '&',
            );

            $data = strtr($data, $rewrite);

            if (function_exists('iconv') && ($options & JSON_UNESCAPED_UNICODE) == 0) {
                $callback = function($value) {
                    return current(unpack('H*', iconv('UTF-8', 'UCS-2BE', $value)));
                };

                $data = preg_replace_callback("/[^\\x{0000}-\\x{007F}]/u", $callback, $data);
            }

            if ($options & 0x8000) {
                $data = preg_replace("/[\000-\037]/", '', $data);
            }

            $json = '"' . $data . '"';
        } elseif (is_bool($data)) {
            $json = $data ? 'true' : 'false';
        } elseif ($data === null) {
            $json = 'null';
        } elseif (is_int($data) || is_float($data)) {
            $json = (string) $data;
        } else {
            throw new DumpingException('Type ' . gettype($data) . " can't be converted to json.");
        }

        return $json;
    }

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
        if ($this->compatMode === false) {
            return json_encode($this->data, $this->options());
        }

        return $this->compatEncode($this->data, $this->options());
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
            $this->options |= (int) $option;
        } else {
            $this->options &= (int) ~$option;
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
