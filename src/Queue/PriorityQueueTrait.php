<?php

/**
 * Phoole (PHP7.2+)
 *
 * @category  Library
 * @package   Phoole\Base
 * @copyright Copyright (c) 2019 Hong Zhang
 */
declare(strict_types=1);

namespace Phoole\Base\Queue;

/**
 * PriorityQueueTrait
 *
 * @package Phoole\Base
 */
trait PriorityQueueTrait
{
    /**
     * data storage
     *
     * @var  array
     */
    protected $queue = [];

    /**
     * marker for sorted or not
     *
     * @var  bool
     */
    protected $sorted = true;

    /**
     * counter for priority
     *
     * @var  int
     */
    protected $counter = 20000000;

    /**
     * {@inheritDoc}
     */
    public function insert($data, int $priority = 0): void
    {
        $i = $this->getIndex($priority);
        $this->queue[$i] = ['data' => $data, 'priority' => $priority];
        $this->sorted = false;
    }

    /**
     * {@inheritDoc}
     */
    public function combine(PriorityQueueInterface $queue): PriorityQueueInterface
    {
        $nqueue = clone $this;
        foreach ($queue->queue as $data) {
            $nqueue->insert($data['data'], $data['priority']);
        }
        return $nqueue;
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->queue);
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        $this->sortQueue();
        return new \ArrayIterator(array_column($this->queue, 'data'));
    }

    /**
     * Generate an integer key
     *
     * @param  int $priority
     * @throws \RuntimeException  priority out of range
     * @return int
     */
    protected function getIndex(int $priority): int
    {
        if (abs($priority) > 1000) {
            throw new \RuntimeException("Priority $priority out of range.");
        }
        return --$this->counter + $priority * 10000;
    }

    /**
     * Sort the queue from higher to lower int $key
     *
     * @return $this
     */
    protected function sortQueue()
    {
        if (!$this->sorted) {
            krsort($this->queue);
            $this->sorted = true;
        }
        return $this;
    }
}
