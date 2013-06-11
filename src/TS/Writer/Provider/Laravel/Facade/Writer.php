<?php

namespace TS\Writer\Provider\Laravel\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.1
 */
class Writer extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'writer';
    }
}
