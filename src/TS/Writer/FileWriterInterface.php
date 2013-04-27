<?php

namespace TS\Writer;

/**
 * FileWriterInterface
 *
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.0
 */
interface FileWriterInterface extends WriterInterface
{
    /**
     * Dumps the data array as a string.
     *
     * @return string
     */
    public function dumpData();

    /**
     * Returns the name of the file that should be written to.
     *
     * @return string
     */
    public function getFileName();

    /**
     * Returns the full path of the file that should be written to.
     *
     * @return string
     */
    public function getFilePath();

    /**
     * Checks if a file name has been set already.
     *
     * @return bool
     */
    public function isFileSet();

    /**
     * Sets the mode a file should be accessed with.
     *
     * @param  int    $mode
     * @return static
     */
    public function setFileAccessMode($mode = 0);

    /**
     * Sets the path and file that the data should be written to.
     *
     * @param  string $filePath
     * @param  bool   $createDir
     * @return static
     */
    public function setTargetFile($filePath, $createDir = false);

    /**
     * Returns the types supported by the Writer using an indexed array - lower keys have higher prevalence.
     *
     * @return array
     */
    public function supportedTypes();

    /**
     * Returns whether this Writer supports writing a file of the given type.
     *
     * @param  string $fileType
     * @return bool
     */
    public function supports($fileType);
}
