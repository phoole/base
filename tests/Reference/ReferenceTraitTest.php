<?php
declare(strict_types=1);

namespace Phoole\Tests\Reference;

use PHPUnit\Framework\TestCase;

class ReferenceTraitTest extends TestCase
{
    private $obj;
    private $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $data = [
            'test1' => 'Y${wow1}',
            'test2' => 'wow3',
            'wow1'  => '${${test2}}',
            'wow3'  => 'xxx',
            'xxx'   => '${yyy}',
            'yyy'   => 'b${xxx}',
            'zzz'   => [1,2],
        ];
        require_once __DIR__ . '/ReferenceClass.php';
        $this->obj = new ReferenceClass($data);
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
     * @covers Phoole\Base\Reference\ReferenceTrait::setReferencePattern()
     */
    public function testSetReferencePattern()
    {
        $this->obj->setReferencePattern('((', '))');
        $str = 'xy((wow3)) ((((test2))))';
        $this->assertEquals('xyxxx xxx', $this->invokeMethod('deReferenceString', [$str]));
    }

    /**
     * @covers Phoole\Base\Reference\ReferenceTrait::deReferenceString()
     */
    public function testDeReferenceString()
    {
        // normal deref
        $str = 'xy${wow3}';
        $this->assertEquals('xyxxx', $this->invokeMethod('deReferenceString', [$str]));

        // recursive deref
        $str = 'zz${test1}';
        $this->assertEquals('zzYxxx', $this->invokeMethod('deReferenceString', [$str]));

        // array deref
        $str = '${zzz}';
        $this->assertEquals([1,2], $this->invokeMethod('deReferenceString', [$str]));
    }

    /**
     * @covers Phoole\Base\Reference\ReferenceTrait::deReferenceString()
     */
    public function testDeReferenceString2()
    {
        // mixed deref
        $str = 'x${zzz}';
        $this->expectExceptionMessageRegExp("/Error in resolving/");
        $this->assertEquals('xx', $this->invokeMethod('deReferenceString', [$str]));
    }

    /**
     * @covers Phoole\Base\Reference\ReferenceTrait::deReferenceString()
     */
    public function testDeReferenceString3()
    {
        // loop found
        $str = '${xxx}ddd';
        $this->expectExceptionMessageRegExp("/Loop/");
        $this->assertEquals('${yyy}', $this->invokeMethod('deReferenceString', [$str]));
    }

    /**
     * @covers Phoole\Base\Reference\ReferenceTrait::deReference()
     */
    public function testDeReference()
    {
        // string also works
        $str = '${zzz}';
        $this->invokeMethod('deReference', [&$str]);
        $this->assertEquals([1,2], $str);

        // deref an array
        $arr = ['${zzz}', 'a' => ['${test1}']];
        $this->invokeMethod('deReference', [&$arr]);
        $this->assertEquals([[1,2], 'a' => ['Yxxx']], $arr);
    }

    /**
     * @covers Phoole\Base\Reference\ReferenceTrait::resolveUnknown()
     */
    public function testResolveUnknown()
    {
        // leave it untouched
        $str = 'b${ingo}';
        $this->invokeMethod('deReference', [&$str]);
        $this->assertEquals('b${ingo}', $str);
    }
}