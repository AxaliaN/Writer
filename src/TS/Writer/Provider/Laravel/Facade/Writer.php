<?php

namespace TS\Writer\Provider\Laravel\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * Writer
 *
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.0
 */
class Writer extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'writer';
    }
}
