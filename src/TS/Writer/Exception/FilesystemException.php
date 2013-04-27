<?php

namespace TS\Writer\Exception;

use Exception;
use RuntimeException;

/**
 * FilesystemException
 *
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.0
 */
final class FilesystemException extends RuntimeException
{
    /**
     * Thrown when anything unwanted happens during file system operations.
     *
     * @param string    $message
     * @param Exception $previous
     */
    public function __construct($message, Exception $previous = null)
    {
        parent::__construct(
            $message,
            0,
            $previous
        );
    }
}
