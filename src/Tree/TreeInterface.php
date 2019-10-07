<?php

/**
 * Phoole (PHP7.2+)
 *
 * @category  Library
 * @package   Phoole\Base
 * @copyright Copyright (c) 2019 Hong Zhang
 */
declare(strict_types=1);

namespace Phoole\Base\Tree;

/**
 * TreeInterface
 *
 * @package Phoole\Base
 */
interface TreeInterface
{
    /**
     * Get a node. NUll if not found. Return the entire tree if $node == ''
     *
     * @param  string $node
     * @return mixed
     */
    public function &get(string $node);

    /**
     * Has a node or not
     *
     * @param  string $node
     * @return bool
     */
    public function has(string $node): bool;

    /**
     * Add or replace the node
     *
     * @param  string $node
     * @param  mixed  $data
     * @return $this
     */
    public function add(string $node, $data): TreeInterface;

    /**
     * Delete node from the tree
     *
     * @param  string $node
     * @return $this
     */
    public function delete(string $node): TreeInterface;
}
