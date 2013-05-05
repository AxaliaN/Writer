<?php

namespace TS\Writer;

use TS\Writer\Event\WriterEvent;
use TS\Writer\Exception\FileNotSetException;
use TS\Writer\Exception\FilesystemException;

/**
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.0
 */
abstract class FileWriter extends AbstractWriter implements FileWriterInterface
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var string
     */
    protected $lineEnding = "\n";

    /**
     * @var int
     */
    protected $mode = 0;

    /**
     * @var int
     */
    const FILE_MODE_APPEND = \FILE_APPEND;

    /**
     * @var int
     */
    const FILE_MODE_OVERWRITE = 0;

    /**
     * Validates whether the given file name has the correct extension for this writer and
     * converts it if it doesn't match one of the supported ones.
     *
     * @param  string $fileName
     * @return string
     */
    protected function validateFileName($fileName)
    {
        $pathinfo = pathinfo($fileName);

        // Wrong extension, set the correct one - the one with the highest prevalence
        // set in our supportedTypes() method.
        if (!isset($pathinfo['extension']) || !$this->supports($pathinfo['extension'])) {
            $types        = $this->supportedTypes();
            $newExtension = reset($types);
            $fileName     = $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '.' . $newExtension;
        }

        return $fileName;
    }

    /**
     * Returns the name of the file that should be written to.
     *
     * @return string
     */
    public function getFileName()
    {
        $filename = pathinfo($this->file, PATHINFO_FILENAME);
        $ext      = pathinfo($this->file, PATHINFO_EXTENSION);

        return ($this->isFileSet() ? $filename . (!empty($ext) ? '.' . $ext : '') : null);
    }

    /**
     * Returns the full path of the file that should be written to.
     *
     * @return string
     */
    public function getFilePath()
    {
        return ($this->isFileSet() ? $this->file : null);
    }

    /**
     * Checks if a file name has been set already.
     *
     * @return bool
     */
    public function isFileSet()
    {
        return $this->file !== null;
    }

    /**
     * Sets the mode a file should be accessed with.
     *
     * @param  int    $mode
     * @return static
     */
    public function setFileAccessMode($mode = 0)
    {
        $this->mode = (int)$mode;

        return $this;
    }

    /**
     * Sets the line ending character.
     *
     * @param  string $lineEnding
     * @return static
     */
    public function setLineEnding($lineEnding)
    {
        $this->lineEnding = $lineEnding;

        return $this;
    }

    /**
     * Sets the path and file that the data should be written to.
     *
     * @param  string              $filePath
     * @param  bool                $createDir
     * @return static
     * @throws FilesystemException
     */
    public function setTargetFile($filePath, $createDir = false)
    {
        $tarDir = pathinfo($filePath, PATHINFO_DIRNAME);

        if (!file_exists($tarDir)) {
            if ($createDir === false) {
                throw new FilesystemException(
                    sprintf('Path [%s] does not exist.', $tarDir)
                );
            }

            // @codeCoverageIgnoreStart
            if (@mkdir($tarDir) === false) {
                throw new FilesystemException(
                    sprintf('Could not create path [%s].', $tarDir)
                );
            }
        }

        if (!is_writable($tarDir)) {
            throw new FilesystemException(
                sprintf('Path [%s] is not writable.', $tarDir)
            );
        }
        // @codeCoverageIgnoreEnd

        $this->file = $this->validateFileName($filePath);

        return $this;
    }

    /**
     * Returns whether this writer supports writing a file of the given type.
     *
     * @param  string $fileType
     * @return bool
     */
    public function supports($fileType)
    {
        return in_array($fileType, $this->supportedTypes(), true);
    }

    /**
     * Writes all data to the previously specified file.
     *
     * @return bool
     * @throws FileNotSetException
     * @throws FilesystemException
     */
    public function writeAll()
    {
        if (!$this->isFileSet()) {
            throw new FileNotSetException;
        }

        $this->eventDispatcher->dispatch(WriterEvents::BEFORE_WRITE, new WriterEvent($this));

        $data = $this->dumpData();

        // Save file
        $success = (bool)@file_put_contents($this->file, $data, $this->mode);

        if ($success === false) {
            throw new FilesystemException(
                sprintf("Couldn't write to file [%s].", $this->file)
            );
        }

        $this->eventDispatcher->dispatch(WriterEvents::WRITE_COMPLETE, new WriterEvent($this));

        return true;
    }
}
