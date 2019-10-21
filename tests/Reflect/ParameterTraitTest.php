<?php

declare(strict_types=1);

namespace Phoole\Tests;

use PHPUnit\Framework\TestCase;
use Phoole\Base\Reflect\ParameterTrait;

class myParam
{
    use ParameterTrait;
}

function myFunc(myParam $e)
{
    return $e;
}

class myClass
{
    public function __invoke(myParam $e)
    {
        echo "bingo";
        return $e;
    }

    public function myMethod(myParam $e)
    {
        return $e;
    }

    public static function myStatic(myParam $e)
    {
        return $e;
    }

    public function noParam()
    {

    }

    public function paramIsString(string $s)
    {
        return $s;
    }
}

class myClass2
{
    public function __construct(myParam $e)
    {

    }
}

class ProviderTest extends TestCase
{
    private $obj;
    private $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new myParam();
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
     * @covers Phoole\Base\Refelect\ParameterTrait::getCallableParameters()
     */
    public function testGetCallableParameters()
    {
        $name = __NAMESPACE__ . '\\myParam';

        // func name
        $func = __NAMESPACE__ . '\\myFunc';
        $params = $this->invokeMethod('getCallableParameters', [$func]);
        $this->assertEquals($name, $params[0]->getType()->getName());

        // closure
        $func = function(myParam $e) { return $e; };
        $params = $this->invokeMethod('getCallableParameters', [$func]);
        $this->assertEquals($name, $params[0]->getType()->getName());

        // invokable
        $func = new myClass();
        $params = $this->invokeMethod('getCallableParameters', [$func]);
        $this->assertEquals($name, $params[0]->getType()->getName());

        // [class, method]
        $func = [new myClass(), 'myMethod'];
        $params = $this->invokeMethod('getCallableParameters', [$func]);
        $this->assertEquals($name, $params[0]->getType()->getName());

        // [staticClass, method]
        $func = [__NAMESPACE__ . '\\myClass', 'myStatic'];
        $params = $this->invokeMethod('getCallableParameters', [$func]);
        $this->assertEquals($name, $params[0]->getType()->getName());
    }

    /**
     * @covers Phoole\Base\Refelect\ParameterTrait::getCallableParameters()
     */
    public function testGetCallableParameters2()
    {
        // pass a non-callable
        $name = __NAMESPACE__ . '\\myParam';
        $func = 'notRealFunc';

        $this->expectExceptionMessage('must be callable');
        $params = $this->invokeMethod('getCallableParameters', [$func]);
        $this->assertEquals($name, $params[0]->getType()->getName());
    }

    /**
     * @covers Phoole\Base\Refelect\ParameterTrait::getCallableParameters()
     */
    public function testGetCallableParameters3()
    {
        // empty parameters
        $name = __NAMESPACE__ . '\\myParam';
        $func = [new myClass(), 'noParam'];

        $params = $this->invokeMethod('getCallableParameters', [$func]);
        $this->assertEquals(0, count($params));
    }

    /**
     * @covers Phoole\Base\Refelect\ParameterTrait::getConstructorParameters()
     */
    public function testGetConstructorParameters()
    {
        // empty parameters
        $params = $this->invokeMethod('getConstructorParameters', [new myParam()]);
        $this->assertEquals(0, count($params));

        // one param
        $params = $this->invokeMethod('getConstructorParameters', [__NAMESPACE__ . '\\myClass2']);
        $this->assertEquals(1, count($params));
        $this->assertEquals(
            __NAMESPACE__ . '\\myParam',
            $params[0]->getType()->getName()
        );
    }
}