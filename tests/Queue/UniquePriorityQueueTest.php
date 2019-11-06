<?php

declare(strict_types=1);

namespace Phoole\Tests\Queue;

use PHPUnit\Framework\TestCase;
use Phoole\Base\Queue\UniquePriorityQueue;

class UniquePriorityQueueTest extends TestCase
{
    private $obj;

    private $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new UniquePriorityQueue();
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
     * @covers Phoole\Base\Queue\UniquePriorityQueue::insert()
     */
    public function testInsert()
    {
        $this->obj->insert(10);
        $this->assertTrue(1 === count($this->obj));

        $this->obj->insert(10, 0);
        $this->assertTrue(1 === count($this->obj));
    }

    /**
     * @covers Phoole\Base\Queue\UniquePriorityQueue::insert()
     */
    public function testInsert2()
    {
        // test object
        $o = new UniquePriorityQueue();
        $this->obj->insert($o, 10);
        $this->assertTrue(1 === count($this->obj));

        $this->obj->insert($o, 20);
        $this->assertTrue(1 === count($this->obj));
    }

    /**
     * @covers Phoole\Base\Queue\UniquePriorityQueue::insert()
     */
    public function testInsert3()
    {
        $o1 = new UniquePriorityQueue();
        $this->obj->insert($o1, 10);
        $this->assertTrue(1 === count($this->obj));

        $o2 = new UniquePriorityQueue();
        $this->obj->insert($o2, 20);
        $this->assertTrue(2 === count($this->obj));
    }

    /**
     * @covers Phoole\Base\Queue\PriorityQueue::combine()
     */
    public function testCombine()
    {
        $o1 = new UniquePriorityQueue();
        $o1->insert(10);
        $o1->insert(20);

        $o2 = new UniquePriorityQueue();
        $o2->insert(10);
        $o2->insert(25, 10);

        $o3 = $o2->combine($o1);

        $this->assertEquals(3, count($o3));

        $result = [];
        foreach ($o3 as $d) {
            $result[] = $d;
        }
        $this->assertEquals([25, 10, 20], $result);
    }
}