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
 * Tree
 *
 * @package Phoole\Base
 */
class Tree implements TreeInterface
{
    /**
     * the tree
     *
     * @var    array
     */
    protected $tree;

    /**
     * construct a tree with/without default data
     *
     * @param  array $data
     */
    public function __construct(array $data = [])
    {
        $this->tree = $data ? $this->fixData($data) : [];
    }

    /**
     * {@inheritDoc}
     */
    public function &get(string $node)
    {
        if ('' === $node) {
            $result = &$this->tree;
        } else {
            $result = &$this->searchNode($node, $this->tree);
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $node): bool
    {
        if ('' === $node) {
            return !empty($this->tree);
        } else {
            return $this->searchNode($node, $this->tree) !== NULL;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function add(string $node, $data): TreeInterface
    {
        // get the node, create it if not exists
        $n = &$this->searchNode($node, $this->tree, TRUE);

        // fix data
        if (is_array($data)) {
            $data = $this->fixData($data);
        }

        // merge
        if (is_array($n) && is_array($data)) {
            $n = array_replace_recursive($n, $data);
        } else {
            $n = $data;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function delete(string $node): TreeInterface
    {
        if ('' === $node) {
            $this->tree = [];
        } elseif ($this->has($node)) {
            $par = &$this->parentNode($node);
            $name = $this->getName($node);
            unset($par[$name]);
        }
        return $this;
    }

    /**
     * Get the parent node
     *
     * @param  string $node
     * @return array
     */
    protected function &parentNode(string $node): array
    {
        $split = explode('.', $node);
        array_pop($split);
        $result = &$this->get(join('.', $split));
        return $result;
    }

    /**
     * Get short name
     *
     * @param  string $node
     * @return string
     */
    protected function getName(string $node): string
    {
        $split = explode('.', $node);
        return array_pop($split);
    }

    /**
     * Fix data, convert 'flat.name' to array node name
     *
     * @param  array $data
     * @return array
     */
    protected function fixData(array $data): array
    {
        $result = [];
        if (isset($data[0])) {
            return $data;
        }

        foreach ($data as $k => $v) {
            $res = &$this->searchNode($k, $result, TRUE);
            if (is_array($v) && is_array($res)) {
                $res = array_replace_recursive($res, $this->fixData($v));
            } else {
                $res = $v;
            }
        }
        return $result;
    }

    /**
     * Search a node in the $data, create on the fly
     *
     * @param  string  $path
     * @param  array  &$data
     * @param  bool    $create
     * @return mixed  null for not found
     */
    protected function &searchNode(string $path, array &$data, bool $create = FALSE)
    {
        $found = &$data;
        foreach (explode('.', $path) as $key) {
            $found = &$this->childNode($key, $found, $create);
            if (NULL === $found) {
                break;
            }
        }
        return $found;
    }

    /**
     * get or create the next/child node, return NULL if not found
     *
     * @param  string  $key
     * @param  mixed  &$data
     * @param  bool    $create  create the node if not exist
     * @return mixed
     */
    protected function &childNode(string $key, &$data, bool $create)
    {
        $null = NULL;
        if (is_array($data)) {
            if (isset($data[$key])) {
                return $data[$key];
            } elseif ($create) {
                $data[$key] = [];
                return $data[$key];
            }
        }
        return $null;
    }
}