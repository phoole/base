<?php

declare(strict_types=1);

namespace Phoole\Tests\Tree;

use Phoole\Base\Tree\Tree;
use PHPUnit\Framework\TestCase;

class TreeTest extends TestCase
{
    private $obj;

    private $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new Tree(
            [
                'db.user' => 'root',
                'db.passwd' => 'bingo',
                'db.host.dev' => 'dev1'
            ]
        );
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
     * @covers Phoole\Base\Tree\Tree::get()
     */
    public function testGet()
    {
        // find match
        $this->assertEquals(
            ['user' => 'root', 'passwd' => 'bingo', 'host' => ['dev' => 'dev1']],
            $this->obj->get('db')
        );

        $this->assertEquals(
            'root',
            $this->obj->get('db.user')
        );

        $this->assertEquals(
            ['dev' => 'dev1'],
            $this->obj->get('db.host')
        );

        // not found
        $this->assertTrue(
            NULL === $this->obj->get('db.bingo')
        );
    }

    /**
     * @covers Phoole\Base\Tree\Tree::has()
     */
    public function testHas()
    {
        // find match
        $this->assertEquals(
            TRUE,
            $this->obj->has('db')
        );

        $this->assertEquals(
            FALSE,
            $this->obj->has('db.server')
        );
    }

    /**
     * @covers Phoole\Base\Tree\Tree::add()
     */
    public function testAdd()
    {
        // add string
        $this->obj->add('db.server', 'test');
        $this->assertEquals(
            'test',
            $this->obj->get('db.server')
        );

        // add array
        $new = ['ip' => '10.0.0.0', 'mac' => 'xxx'];
        $this->obj->add('db.server', $new);
        $this->assertEquals(
            $new,
            $this->obj->get('db.server')
        );

        // false is allowed
        $this->obj->add('db.server', FALSE);
        $this->assertTrue(FALSE === $this->obj->get('db.server'));

        // fix data
        $new = ['ip.ipv6' => 'xxx'];
        $this->obj->add('db.server', $new);
        $this->assertEquals(
            ['ip' => ['ipv6' => 'xxx']],
            $this->obj->get('db.server')
        );
    }

    /**
     * @covers Phoole\Base\Tree\Tree::delete()
     */
    public function testDelete()
    {
        // add string
        $this->obj->add('db.server', 'test');
        $this->assertEquals(
            'test',
            $this->obj->get('db.server')
        );

        // delete it
        $this->obj->delete('db.server');
        $this->assertTrue(NULL === $this->obj->get('db.server'));
    }

    /**
     * @covers Phoole\Base\Tree\Tree::serachNode()
     */
    public function testSearchNode()
    {
        $data = [
            'db' => [
                'user' => 'root',
                'host' => 'dev1'
            ]
        ];

        // find match
        $this->assertEquals(
            'root',
            $this->invokeMethod('searchNode', ['db.user', &$data])
        );

        $this->assertEquals(
            ['user' => 'root', 'host' => 'dev1'],
            $this->invokeMethod('searchNode', ['db', &$data])
        );

        // not found
        $this->assertEquals(
            NULL,
            $this->invokeMethod('searchNode', ['db.port', &$data])
        );

        // create if not found
        $this->assertEquals(
            [],
            $this->invokeMethod('searchNode', ['db.port', &$data, TRUE])
        );
    }

    /**
     * @covers Phoole\Base\Tree\Tree::childNode()
     */
    public function testChildNode()
    {
        $data = [
            'db' => [
                'user' => 'root',
                'host' => 'dev1'
            ]
        ];

        // find match
        $this->assertEquals(
            ['user' => 'root', 'host' => 'dev1'],
            $this->invokeMethod('childNode', ['db', &$data, FALSE])
        );

        $this->assertEquals(
            'root',
            $this->invokeMethod('childNode', ['user', &$data['db'], FALSE])
        );

        // not found
        $this->assertEquals(
            NULL,
            $this->invokeMethod('childNode', ['port', &$data['db'], FALSE])
        );

        // create the child
        $this->assertEquals(
            [],
            $this->invokeMethod('childNode', ['port', &$data['db'], TRUE])
        );
    }

    /**
     * @covers Phoole\Base\Tree\Tree::fixData()
     */
    public function testFixData()
    {
        $data = [
            'env' => 'test',
            'db.user' => 'root',
            'db.host' => 'dev1'
        ];

        // find match
        $this->assertEquals(
            ['env' => 'test', 'db' => ['user' => 'root', 'host' => 'dev1']],
            $this->invokeMethod('fixData', [$data])
        );

        $data = ['test'];
        $this->assertEquals(
            $data,
            $this->invokeMethod('fixData', [$data])
        );
    }

    /**
     * @covers Phoole\Base\Tree\Tree::getName()
     */
    public function testGetName()
    {
        $this->assertEquals(
            'name',
            $this->invokeMethod('getName', ['this.is.the.name'])
        );

        $this->assertEquals(
            'oneName',
            $this->invokeMethod('getName', ['oneName'])
        );

        $this->assertEquals(
            '',
            $this->invokeMethod('getName', [''])
        );
    }

    /**
     * @covers Phoole\Base\Tree\Tree::parentNode()
     */
    public function testParentNode()
    {
        $this->assertEquals(
            ['dev' => 'dev1'],
            $this->invokeMethod('parentNode', ['db.host.dev'])
        );

        $data = $this->obj->get('');
        $this->assertEquals(
            $data,
            $this->invokeMethod('parentNode', ['db'])
        );
    }
}
