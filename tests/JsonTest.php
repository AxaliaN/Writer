<?php

use TS\Writer\Implementation\Json;

class JsonTest extends BaseTest
{
    protected $data = array(
        'array'         => array('key1' => 'value1', 'key2' => 'value2'),
        'bool'          => true,
        'float'         => 3.14,
        'int'           => 1,
        'null'          => null,
        'numericstring' => '3.14',
        'string'        => 'value',
    );

    /**
     * @var Json
     */
    private $writer;

    protected function setUp()
    {
        $this->writer = new Json($this->dispatcher);
        $this->writer->setTargetFile($this->tmpDir . 'jsonFile.json');
        $this->writer->setData($this->getData());
    }

    protected function tearDown()
    {
        $this->writer = null;
        @unlink($this->tmpDir . 'jsonFile.json');
    }

    public function testFactory()
    {
        $this->assertInstanceOf('TS\\Writer\\Implementation\\Json', Json::factory($this->dispatcher));
    }

    public function testJsonAccessors()
    {
        $this->writer->setLineEnding("\r\n");
        $this->writer->setOption(JSON_PRETTY_PRINT);
        $this->writer->setPrettyPrint(false);

        $reflection = new ReflectionObject($this->writer);

        $lineEnding = $reflection->getProperty('lineEnding');
        $lineEnding->setAccessible(true);

        $this->assertSame("\r\n", $lineEnding->getValue($this->writer));

        $prettyPrint = $reflection->getProperty('prettyPrint');
        $prettyPrint->setAccessible(true);

        $this->assertFalse($prettyPrint->getValue($this->writer));

        $options = $reflection->getMethod('options');
        $options->setAccessible(true);

        $this->assertSame(JSON_PRETTY_PRINT, $options->invoke($this->writer));

        $this->writer->setOption(JSON_PRETTY_PRINT, false);

        $this->assertSame(0, $options->invoke($this->writer));
    }

    public function testWriteAll()
    {
        $this->assertTrue($this->writer->writeAll());

        $expected = json_encode($this->getData(), JSON_PRETTY_PRINT);

        $this->assertSame($expected, $this->writer->dumpData());
        $this->assertSame($expected, file_get_contents($this->tmpDir . 'jsonFile.json'));
    }
}
