<?php
declare(strict_types=1);

namespace Phoole\Tests\Reader;

use PHPUnit\Framework\TestCase;
use Phoole\Base\Reader\PhpReader;

class PhpReaderTest extends TestCase
{
    private $obj;
    private $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new PhpReader();
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
     * @covers Phoole\Base\Reader\PhpReader::readFromFile()
     */
    public function testReadFromFile1()
    {
        $this->assertEquals(
            ['Test' => 'ddd'],
            $this->invokeMethod('readFromFile', [__DIR__.'/good.php'])
        );
    }

    /**
     * test file not found
     *
     * @covers Phoole\Base\Reader\PhpReader::readFromFile()
     */
    public function testReadFromFile2()
    {
        $this->expectExceptionMessage('failed to open stream');
        $this->assertEquals(
            ['Test' => 'ddd'],
            $this->invokeMethod('readFromFile', ['god.php'])
        );
    }

    /**
     * test bad php file
     *
     * @covers Phoole\Base\Reader\PhpReader::readFromFile()
     */
    public function testReadFromFile3()
    {
        $this->expectExceptionMessage('yntax error');
        $this->assertEquals(
            ['Test' => 'ddd'],
            $this->invokeMethod('readFromFile', [__DIR__.'/badphp.txt'])
        );
    }
}
