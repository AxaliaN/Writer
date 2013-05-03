<?php

namespace TS\Writer;

/**
 * @package   Writer
 * @author    Timo Schäfer
 * @copyright 2013
 * @version   1.0
 */
abstract class WriterEvents
{
    /**
     * Dispatched before the Writer tries to write.
     *
     * Arguments:
     * - Writer instance
     *
     * @var string
     */
    const BEFORE_WRITE = 'writer.before_write';

    /**
     * Dispatched when the Writer is instantiated.
     *
     * Arguments:
     * - Writer instance
     *
     * @var string
     */
    const INIT = 'writer.init';

    /**
     * Dispatched when a line write occurs.
     *
     * Arguments:
     * - Writer instance
     * - Data array
     *
     * @var string
     */
    const WRITE = 'writer.write';

    /**
     * Dispatched when a Writer's writeAll() method is run.
     *
     * Arguments:
     * - Writer instance
     *
     * @var string
     */
    const WRITE_ALL = 'writer.write_all';

    /**
     * Dispatched when the Writer has finished writing.
     *
     * Arguments:
     * - Writer instance
     *
     * @var string
     */
    const WRITE_COMPLETE = 'writer.write_complete';
}
