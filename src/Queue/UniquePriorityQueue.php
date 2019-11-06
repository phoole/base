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
     * @param  mixed[] $input
     * @return mixed[]
     */
    protected function getUnique(array $input): array
    {
        $result = [];
        foreach ($input as $val) {
            $key = $this->getKey($val);
            if (!isset($result[$key])) {
                $result[$key] = $val;
            }
        }
        return \array_values($result);
    }

    /**
     * Generate related key base on value
     *
     * @param  mixed $val
     * @return string
     */
    protected function getKey($val): string
    {
        if (is_object($val)) {
            $key = \spl_object_hash($val);
        } elseif (is_scalar($val)) {
            $key = (string) $val;
        } else {
            $key = md5(\serialize($val));
        }
        return $key;
    }
}