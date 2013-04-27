# Writer

Extensible data output library.

## Build status

[![Build Status](https://travis-ci.org/timoschaefer/Writer.png)](https://travis-ci.org/timoschaefer/Writer)

## Installation

[Download the latest stable version](https://github.com/timoschaefer/Writer/archive/1.0.zip) or simply use [Composer](http://getcomposer.org) to install:

```json
{
    "require": {
        "ts/writer": "v1.0.0"
    }
}
```

## Direct usage of an implementation

If you know exactly what you want to output, feel free to instantiate a Writer implementation directly:

```php
use Symfony\Component\EventDispatcher\EventDispatcher;
use TS\Writer\Implementation\Json;

$jsonWriter = new Json(new EventDispatcher);

// Setting the data array
$jsonWriter->setData(array(/* ... */));

// Setting the file path to output to
$jsonWriter->setTargetFile(/* path where the .json file should be created */);

// Dumping the data
$jsonWriter->writeAll();
```

## Using the FileWriterFactory

Instead of instantiating Writer implementations directly you can use the FileWriterFactory to create the Writer.

```php
use Symfony\Component\EventDispatcher\EventDispatcher;
use TS\Writer\FileWriterFactory;

$factory = new FileWriterFactory(new EventDispatcher);

// Registration
$factory->registerWriter('TS\\Writer\\Implementation\\Json');
// ...
// Registering further implementations...
// ...

// Creating the Writer
$writer = $factory->createForType('json');

// Setting the data array
$writer->setData(array(/* ... */));

// Setting the file path to output to
$writer->setTargetFile(/* path where the .json file should be created */);

// Dumping the data
$writer->writeAll();
```

## Using the Symfony EventDispatcher

You can intercept or influence most parts of the Writer's lifecycle by utilizing [Symfony's EventDispatcher Component](http://symfony.com/doc/current/components/event_dispatcher/introduction.html).

The Events triggered by the Writer can be found inside ``TS\Writer\WriterEvents``:

- **BEFORE_WRITE**: Dispatched before the Writer tries to write.
- **INIT**: Dispatched when the Writer is instantiated.
- **WRITE**: Dispatched when a line write occurs.
- **WRITE_ALL**: Dispatched when a Writer's writeAll() method is run.
- **WRITE_COMPLETE**: Dispatched when the Writer has finished writing.

## Using the Writer with Silex or Laravel 4

The Writer comes with Service Providers for both Silex and Laravel 4, which can be found in the ``TS\Writer\Provider`` namespace.

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
