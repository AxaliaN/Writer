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
final class FactoryTypeException extends RuntimeException
{
    /**
     * @param  string    $type
     * @param  Exception $previous
     */
    public function __construct($type, Exception $previous = null)
    {
        parent::__construct(
            sprintf("The FileWriterContainer couldn't create a matching writer for type [%s].", $type),
            0,
            $previous
        );
    }
}
