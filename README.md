# Writer

Extensible data output library.

## Build status

[![Build Status](https://travis-ci.org/timoschaefer/Writer.png)](https://travis-ci.org/timoschaefer/Writer)

## Installation

[Download the latest stable version](https://github.com/timoschaefer/Writer/archive/1.0.zip) or simply use [Composer](http://getcomposer.org) to install:

```json
{
    "require": {
        "ts/writer": "1.0.*"
    }
}
```

## Direct usage of an implementation

If you know exactly what you want to output, feel free to instantiate a writer implementation directly:

```php
use Symfony\Component\EventDispatcher\EventDispatcher;
use TS\Writer\Implementation\Json;

// Creating the writer
$jsonWriter = new Json(new EventDispatcher);

// Setting the data array
$jsonWriter->setData(array(/* ... */));

// Setting the file path to output to
$jsonWriter->setTargetFile(/* path where the .json file should be created */);

// Dumping the data
$jsonWriter->writeAll();
```

## Using the FileWriterFactory

Instead of instantiating writer implementations directly you can use the FileWriterFactory to create the writer:

```php
use Symfony\Component\EventDispatcher\EventDispatcher;
use TS\Writer\FileWriterFactory;

// Creating the FileWriterFactory
$factory = new FileWriterFactory(new EventDispatcher);

// Registration
$factory->registerWriter('TS\\Writer\\Implementation\\Json');
// ...
// Registering further implementations...
// ...

// Creating the writer
$writer = $factory->createForType('json');

// Setting the data array
$writer->setData(array(/* ... */));

// Setting the file path to output to
$writer->setTargetFile(/* path where the .json file should be created */);

// Dumping the data
$writer->writeAll();
```

## Using the Symfony EventDispatcher

You can intercept or influence most parts of the writer's lifecycle by utilizing [Symfony's EventDispatcher Component](http://symfony.com/doc/current/components/event_dispatcher/introduction.html).

The events triggered by the writer can be found in the ``TS\Writer\WriterEvents`` namespace:

- **BEFORE_WRITE**: Dispatched before the writer tries to write.
- **INIT**: Dispatched when the writer is instantiated.
- **WRITE**: Dispatched when a line write occurs.
- **WRITE_ALL**: Dispatched when a writer's writeAll() method is called.
- **WRITE_COMPLETE**: Dispatched when the writer has finished writing.

## Using the writer with Laravel and Silex

The writer comes with a Service Provider for both Laravel and Silex, which can be found in the ``TS\Writer\Provider`` namespace.

## Available implementations

| Type  | Class name                     |
| ----- | ------------------------------ |
| Csv   | TS\Writer\Implementation\Csv   |
| Ini   | TS\Writer\Implementation\Ini   |
| Json  | TS\Writer\Implementation\Json  |
| Txt   | TS\Writer\Implementation\Txt   |
| Xml   | TS\Writer\Implementation\Xml   |
| Yaml  | TS\Writer\Implementation\Yaml  |

Since creating excel sheets can be a little complex I've opted out of trying to achieve an abstraction. Feel free to use the great [PHPExcel Library](http://phpexcel.codeplex.com/) directly for that purpose.
