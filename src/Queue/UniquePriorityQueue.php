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
        return count($this->getUnique(array_column($this->queue, 'data')));
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        $this->sortQueue();
        return new \ArrayIterator(
            $this->getUnique(array_column($this->queue, 'data'))
        );
    }

    /**
     * Remove duplicated items
     *
     * @param  array $input
     * @return array
     */
    protected function getUnique(array $input): array
    {
        $result = [];
        foreach ($input as $k => $val) {
            if (is_object($val)) {
                $key = \spl_object_hash($val);
            } elseif (is_scalar($val)) {
                $key = (string) $val;
            }
            if (!isset($result[$key])) {
                $result[$key] = $val;
            }
        }
        return \array_values($result);
    }
}
