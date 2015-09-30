<?php

namespace TS\Writer\Exception;

use Exception;
use RuntimeException;

/**
 * Thrown when anything unwanted happens during file system operations.
 *
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2014
 * @version   1.2
 */
final class FilesystemException extends RuntimeException
{
    /**
     * @param string    $message
     * @param Exception $previous
     */
    public function __construct($message, Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
