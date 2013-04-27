<?php

namespace TS\Writer\Exception;

use Exception;
use RuntimeException;

/**
 * DumpingException
 *
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.0
 */
final class DumpingException extends RuntimeException
{
    /**
     * Thrown when anything unwanted happens during data dumping.
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
