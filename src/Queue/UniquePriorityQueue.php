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
 * UniquePriorityQueue
 *
 * Make sure items in the queue are unique
 *
 * @package Phoole\Base
 */
class UniquePriorityQueue extends PriorityQueue
{
    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count(array_unique(array_column($this->queue, 'data'), \SORT_REGULAR));
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        $this->sortQueue();
        return new \ArrayIterator(
            array_unique(array_column($this->queue, 'data'), \SORT_REGULAR)
        );
    }
}
