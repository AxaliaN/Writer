<?php

namespace TS\Writer\Implementation;

use DOMDocument;
use DOMElement;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TS\Writer\Exception\DumpingException;
use TS\Writer\FileWriter;

/**
 * Xml
 *
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.0
 */
class Xml extends FileWriter
{
    /**
     * @var string
     */
    private $encoding = 'UTF-8';

    /**
     * @var bool
     */
    private $formatOutput = true;

    /**
     * @var string
     */
    private $rootNode = 'rootNode';

    /**
     * @var DOMDocument
     */
    private $xml;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        parent::__construct($eventDispatcher);

        // @codeCoverageIgnoreStart
        if (ini_get('zend.ze1_compatibility_mode') == 1) {
            ini_set('zend.ze1_compatibility_mode', 0);
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Converts the given $data array to a DOMElement node which will be
     * attached to the DOMDocument created by initializeXml().
     *
     * @param  string           $nodeName
     * @param  array            $data
     * @return DOMElement
     * @throws DumpingException
     */
    private function convert($nodeName, $data = array())
    {
        // Create root node
        $node = $this->xml->createElement($nodeName);

        if (is_array($data)) {
            // Iterate over attributes
            if (isset($data['@attributes'])) {
                foreach ($data['@attributes'] as $key => $value) {
                    // Keys have to start with a letter and my only contain certain symbols
                    if (!$this->validateTag($key)) {
                        throw new DumpingException('attribute');
                    }

                    $node->setAttribute($key, $this->convertBool($value));
                }

                unset($data['@attributes']);
            }

            if (isset($data['@value'])) {
                $textNode = $this->xml->createTextNode($this->convertBool($data['@value']));
                $node->appendChild($textNode);

                unset($data['@value']);

                // Return from recursion
                return $node;
            } elseif (isset($data['@cdata'])) {
                $cDataSection = $this->xml->createCDATASection($this->convertBool($data['@cdata']));
                $node->appendChild($cDataSection);

                unset($data['@cdata']);

                // Return from recursion
                return $node;
            }
        }

        // Create Subnodes through recursion
        if (is_array($data)) {
            // Recurse to get the node for that key
            foreach ($data as $key => $value) {
                if (!$this->validateTag($key)) {
                    throw new DumpingException('tag');
                }

                // More than one node of a kind
                if (is_array($value) && is_numeric(key($value))) {
                    // Numeric array indizes use the parent's key
                    foreach ($value as $v) {
                        $node->appendChild($this->convert($key, $v));
                    }
                    // Only one node of a kind
                } else {
                    $node->appendChild($this->convert($key, $value));
                }

                unset($data[$key]);
            }
        }

        // after we are done with all the keys in the array (if it is one)
        // we check if it has any text value, if yes, append it.
        if (!is_array($data)) {
            $childNode = @$this->xml->createTextNode($this->convertBool($data));

            if (!$childNode) {
                throw new DumpingException('Type ' . gettype($data) . " can't be converted to xml.");
            }

            $node->appendChild($childNode);
        }

        return $node;
    }

    /**
     * Converts boolean values to their string representations.
     *
     * @param  mixed $value
     * @return string
     */
    private function convertBool($value)
    {
        if (!is_bool($value)) {
            return $value;
        }

        return ($value === true ? 'true' : 'false');
    }

    /**
     * Initializes the DOMDocument object which will be dumped to our file later.
     *
     * @return DOMDocument
     */
    private function initializeXml()
    {
        $this->xml               = new DOMDocument('1.0', $this->encoding);
        $this->xml->formatOutput = $this->formatOutput;

        return $this->xml;
    }

    /**
     * Validates whether a tag contains any invalid symbols.
     *
     * @param  string $tag
     * @return bool
     */
    private function validateTag($tag)
    {
        return preg_match('/^[a-z_]+[a-z0-9\:\-\.\_]*[^:]*$/i', $tag, $matches) && $matches[0] == $tag;
    }

    /**
     * Dumps the data array as a string.
     *
     * @return string
     */
    public function dumpData()
    {
        $xml = $this->initializeXml();
        $xml->appendChild($this->convert($this->rootNode, $this->data));

        return $xml->saveXML();
    }

    /**
     * Sets the encoding to be used by the xml.
     *
     * @param  string $encoding
     * @return static
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;

        return $this;
    }

    /**
     * Sets whether generated xml data should be formatted and indented.
     *
     * @param  bool   $formatOutput
     * @return static
     */
    public function setFormatOutput($formatOutput = true)
    {
        $this->formatOutput = $formatOutput;

        return $this;
    }

    /**
     * Sets the root node of our xml data.
     *
     * @param  string $rootNode
     * @return static
     */
    public function setRootNode($rootNode)
    {
        $this->rootNode = $rootNode;

        return $this;
    }

    /**
     * Returns the types supported by the Writer using an indexed array - lower keys have higher prevalence.
     *
     * @return array
     */
    public function supportedTypes()
    {
        return array('xml');
    }
}
