# Writer

Extensible data output library.

## Build status and SensioLabs Insight medal

[![Build Status](https://travis-ci.org/timoschaefer/Writer.png)](https://travis-ci.org/timoschaefer/Writer) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/5e2e0920-458d-4d20-8cd6-c324b4524a00/mini.png)](https://insight.sensiolabs.com/projects/5e2e0920-458d-4d20-8cd6-c324b4524a00)

## Installation

Use [Composer](http://getcomposer.org) to install:

```json
{
    "require": {
        "ts/writer": "1.2.*"
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

## Using the FileWriterContainer

Instead of instantiating writer implementations directly you can use the FileWriterContainer to create the writer:

```php
use Symfony\Component\EventDispatcher\EventDispatcher;
use TS\Writer\FileWriterContainer;

// Creating the FileWriterContainer
$container = new FileWriterContainer(new EventDispatcher);

// Registration and setting a type
$container->registerWriter('TS\\Writer\\Implementation\\Json', 'json');
// ...
// Registering further implementations...
// ...

// Creating the writer
$writer = $container->createForType('json');

// Setting the data array
$writer->setData(array(/* ... */));

// Setting the file path to output to
$writer->setTargetFile(/* path where the .json file should be created */);

// Dumping the data
$writer->writeAll();
```

Also supports everyone's favorite ``ArrayAccess`` interface:

```php
// Register
$container['json'] = 'TS\\Writer\\Implementation\\Json';

// Create writer
$writer = $container['json'];
// ... or
$writer = $container['TS\\Writer\\Implementation\\Json'];

// Check if writer or type is supported
var_dump(isset($container['json'])); // true

// Unregister
unset($container['json']);
// ... or
unset($container['TS\\Writer\\Implementation\\Json']);
// ... or
unset($container[$writer]);
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

The writer comes with service providers for both Laravel and Silex, which can be found in the ``TS\Writer\Provider`` namespace.

## Available implementations

| Type  | Class name                     |
| ----- | ------------------------------ |
| Csv   | TS\Writer\Implementation\Csv   |
| Ini   | TS\Writer\Implementation\Ini   |
| Json  | TS\Writer\Implementation\Json  |
| Txt   | TS\Writer\Implementation\Txt   |
| Xml   | TS\Writer\Implementation\Xml   |
| Yaml  | TS\Writer\Implementation\Yaml  |

Since creating excel sheets can be a little complex I've opted out of trying to achieve an abstraction. Feel free to use the great [PHPExcel library](http://phpexcel.codeplex.com/) directly for that purpose.
