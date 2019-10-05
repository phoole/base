<?php
declare(strict_types=1);

namespace Phoole\Tests\Reader;

use PHPUnit\Framework\TestCase;
use Phoole\Base\Reader\YamlReader;

class YamlReaderTest extends TestCase
{
    private $obj;
    private $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new YamlReader();
        $this->ref = new \ReflectionClass(get_class($this->obj));
    }

    protected function tearDown(): void
    {
        $this->obj = $this->ref = null;
        parent::tearDown();
    }

    protected function invokeMethod($methodName, array $parameters = array())
    {
        $method = $this->ref->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($this->obj, $parameters);
    }

    /**
     * normal test
     *
     * @covers Phoole\Base\Reader\YamlReader::readFromFile()
     */
    public function testReadFromFile1()
    {
        if (!extension_loaded('yaml')) {
            $this->markTestSkipped('need YAML extension');
        }
        $this->assertEquals(
            ['fruit' => ['apple', 'pear'], 'animal' => ['type' => 'mammal']],
            $this->invokeMethod('readFromFile', [__DIR__.'/good.yml'])
        );
    }

    /**
     * test file not found
     *
     * @covers Phoole\Base\Reader\YamlReader::readFromFile()
     */
    public function testReadFromFile2()
    {
        if (!extension_loaded('yaml')) {
            $this->markTestSkipped('need YAML extension');
        }
        $this->expectExceptionMessage('failed to open stream');
        $this->assertEquals(
            ['Test' => 'ddd'],
            $this->invokeMethod('readFromFile', ['god.yml'])
        );
    }

    /**
     * test bad YAML file
     *
     * @covers Phoole\Base\Reader\YamlReader::readFromFile()
     */
    public function testReadFromFile3()
    {
        if (!extension_loaded('yaml')) {
            $this->markTestSkipped('need YAML extension');
        }
        $this->expectExceptionMessage('parsing error');
        $this->assertEquals(
            ['Test' => 'ddd'],
            $this->invokeMethod('readFromFile', [__DIR__.'/badyml.txt'])
        );
    }
}
