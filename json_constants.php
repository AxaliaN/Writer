<?php

if (version_compare(PHP_VERSION, '5.4.0', '<')) {
    if (!defined('JSON_UNESCAPED_SLASHES')) {
        define('JSON_UNESCAPED_SLASHES', 64);
    }

    if (!defined('JSON_PRETTY_PRINT')) {
        define('JSON_PRETTY_PRINT', 128);
    }

    if (!defined('JSON_UNESCAPED_UNICODE')) {
        define('JSON_UNESCAPED_UNICODE', 256);
    }
}
