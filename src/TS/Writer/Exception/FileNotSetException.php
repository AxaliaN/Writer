<?php

namespace TS\Writer\Exception;

use Exception;
use LogicException;

/**
 * FileNotSetException
 *
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.0
 */
final class FileNotSetException extends LogicException
{
    /**
     * Thrown when a file writer is told to write without defining the file path.
     *
     * @param Exception $previous
     */
    public function __construct(Exception $previous = null)
    {
        parent::__construct('No file to write to given.', 0, $previous);
    }
}
