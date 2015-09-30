<?php

namespace TS\Writer\Exception;

use Exception;
use RuntimeException;

/**
 * Thrown when the container wasn't able to create a matching writer.
 *
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2014
 * @version   1.2
 */
final class FactoryClassException extends RuntimeException
{
    /**
     * @param  string    $class
     * @param  Exception $previous
     */
    public function __construct($class, Exception $previous = null)
    {
        parent::__construct(
            sprintf("The FileWriterContainer couldn't create a matching writer for class [%s].", $class),
            0,
            $previous
        );
    }
}
