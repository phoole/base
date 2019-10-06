<?php
declare(strict_types=1);

namespace Phoole\Tests\Reader;

use PHPUnit\Framework\TestCase;
use Phoole\Base\Reader\Reader;

class ReaderTest extends TestCase
{
    private $obj;
    private $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new Reader();
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
     * read json
     *
     * @covers Phoole\Base\Reader\Reader::readFile()
     */
    public function testReadFile1()
    {
        $this->assertEquals(
            ['Test' => 'ddd'],
            $this->invokeMethod('readFile', [__DIR__.'/good.json'])
        );
    }

    /**
     * read php
     *
     * @covers Phoole\Base\Reader\Reader::readFile()
     */
    public function testReadFile2()
    {
        $this->assertEquals(
            ['Test' => 'ddd'],
            $this->invokeMethod('readFile', [__DIR__.'/good.php'])
        );
    }

    /**
     * read yml
     *
     * @covers Phoole\Base\Reader\Reader::readFile()
     */
    public function testReadFile3()
    {
        $this->assertEquals(
            ['fruit' => ['apple', 'pear'], 'animal' => ['type' => 'mammal']],
            $this->invokeMethod('readFile', [__DIR__.'/good.yml'])
        );
    }

    /**
     * unsupported type
     *
     * @covers Phoole\Base\Reader\Reader::readFile()
     */
    public function testReadFile4()
    {
        $this->expectExceptionMessage('non-supported type');
        $this->assertEquals(
            [],
            $this->invokeMethod('readFile', [__DIR__.'/good.xml'])
        );
    }
}
