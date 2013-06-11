<?php

namespace TS\Writer\Exception;

use Exception;
use LogicException;

/**
 * Thrown when a file writer is told to write without defining the file path.
 *
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.1
 */
final class FileNotSetException extends LogicException
{
    /**
     * @param Exception $previous
     */
    public function __construct(Exception $previous = null)
    {
        parent::__construct('No file to write to given.', 0, $previous);
    }
}
