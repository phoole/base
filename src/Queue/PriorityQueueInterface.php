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
 * PriorityQueueInterface
 *
 * @package Phoole\Base
 */
interface PriorityQueueInterface extends \IteratorAggregate, \Countable
{
    /**
     * Insert data into the queue with priority
     *
     * @param  mixed $data
     * @param  int  $priority priority, higher number retrieved first(-1000 - 1000)
     * @throws \RuntimeException if priority out of range
     * @return void
     */
    public function insert($data, int $priority = 0): void;

    /**
     * Combine with queue and return a combined new queue
     *
     * @param  PriorityQueueInterface $queue
     * @return PriorityQueueInterface
     */
    public function combine(PriorityQueueInterface $queue): PriorityQueueInterface;
}
