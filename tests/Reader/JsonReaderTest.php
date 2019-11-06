<?php

declare(strict_types=1);

namespace Phoole\Tests\Reader;

use PHPUnit\Framework\TestCase;
use Phoole\Base\Reader\JsonReader;

class JsonReaderTest extends TestCase
{
    private $obj;

    private $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new JsonReader();
        $this->ref = new \ReflectionClass(get_class($this->obj));
    }

    protected function tearDown(): void
    {
        $this->obj = $this->ref = NULL;
        parent::tearDown();
    }

    protected function invokeMethod($methodName, array $parameters = array())
    {
        $method = $this->ref->getMethod($methodName);
        $method->setAccessible(TRUE);
        return $method->invokeArgs($this->obj, $parameters);
    }

    /**
     * normal test
     *
     * @covers Phoole\Base\Reader\JsonReader::readFromFile()
     */
    public function testReadFromFile1()
    {
        $this->assertEquals(
            ['Test' => 'ddd'],
            $this->invokeMethod('readFromFile', [__DIR__ . '/good.json'])
        );
    }

    /**
     * test file not found
     *
     * @covers Phoole\Base\Reader\JsonReader::readFromFile()
     */
    public function testReadFromFile2()
    {
        $this->expectExceptionMessage('failed to open stream');
        $this->assertEquals(
            ['Test' => 'ddd'],
            $this->invokeMethod('readFromFile', ['god.json'])
        );
    }

    /**
     * test bad json file
     *
     * @covers Phoole\Base\Reader\JsonReader::readFromFile()
     */
    public function testReadFromFile3()
    {
        $this->expectExceptionMessage('yntax error');
        $this->assertEquals(
            ['Test' => 'ddd'],
            $this->invokeMethod('readFromFile', [__DIR__ . '/badjson.txt'])
        );
    }
}
