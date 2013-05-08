<?php

namespace TS\Writer\Exception;

use Exception;
use RuntimeException;

/**
 * Thrown when the FileWriterFactory wasn't able to create a matching writer.
 *
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.0
 */
final class FactoryException extends RuntimeException
{
    /**
     * @param  string    $type
     * @param  Exception $previous
     */
    public function __construct($type, Exception $previous = null)
    {
        parent::__construct(
            sprintf("The FileWriterFactory couldn't create a matching Writer for type [%s].", $type),
            0,
            $previous
        );
    }
}
