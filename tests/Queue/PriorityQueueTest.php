<?php
declare(strict_types=1);

namespace Phoole\Tests\Queue;

use PHPUnit\Framework\TestCase;
use Phoole\Base\Queue\PriorityQueue;

class PriorityQueueTest extends TestCase
{
    private $obj;
    private $ref;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new PriorityQueue();
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
     * @covers Phoole\Base\Queue\PriorityQueue::insert()
     */
    public function testInsert()
    {
        $this->obj->insert(10);
        $this->assertTrue(1 === count($this->obj));

        $this->obj->insert(11, 0);
        $this->assertTrue(2 === count($this->obj));

        $this->obj->insert(20, 20);

        // check priority
        $result = [];
        foreach($this->obj as $d) {
            $result[] = $d;
        }
        $this->assertEquals([20,10,11], $result);
    }

        /**
     * @covers Phoole\Base\Queue\PriorityQueue::combine()
     */
    public function testCombine()
    {
        $o1 = new PriorityQueue();
        $o1->insert(10);
        $o1->insert(20);

        $o2 = new PriorityQueue();
        $o2->insert(15);
        $o2->insert(25, 10);

        $o3 = $o2->combine($o1);
        $result = [];
        foreach($o3 as $d) {
            $result[] = $d;
        }
        $this->assertEquals([25,15,10,20], $result);
    }
}