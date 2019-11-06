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
 * TreeAwareTrait
 *
 * @package Phoole\Base
 */
trait TreeAwareTrait
{
    /**
     * @var TreeInterface
     */
    protected $tree;

    /**
     * @return TreeInterface
     */
    public function getTree(): TreeInterface
    {
        return $this->tree;
    }
}