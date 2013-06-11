<?php

namespace TS\Writer\Tests;

use PHPUnit_Framework_TestCase;
use stdClass;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.1
 */
abstract class BaseTest extends PHPUnit_Framework_TestCase
{
    protected $data = array(
        'array'  => array('key' => 'value'),
        'bool'   => true,
        'float'  => 3.14,
        'int'    => 1,
        'null'   => null,
        'string' => 'value',
    );

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    protected $tmpDir;

    public function __construct()
    {
        parent::__construct();

        $this->dispatcher = new EventDispatcher();
        $this->tmpDir     = realpath(__DIR__ . '/tmp') . '/';
    }

    protected function getData()
    {
        $data = $this->data;

        if (!isset($data['object'])) {
            $data['object'] = new stdClass;
        }

        return $data;
    }
}
