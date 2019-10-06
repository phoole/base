<?php

/**
 * Phoole (PHP7.2+)
 *
 * @category  Library
 * @package   Phoole\Base
 * @copyright Copyright (c) 2019 Hong Zhang
 */
declare(strict_types=1);

namespace Phoole\Base\Reader;

/**
 * Reader
 *
 * @package Phoole\Base
 */
class Reader implements ReaderInterface
{
    /**
     * supported types
     *
     * @var    string[]
     */
    protected $supported = [
        'json' => __NAMESPACE__ . '\JsonReader',
        'php'  => __NAMESPACE__ . '\PhpReader',
        'yml'  => __NAMESPACE__ . '\YamlReader'
    ];

    /**
     * {@inheritDoc}
     */
    public function readFile(string $path)
    {
        $suffix = substr($path, strpos($path, '.') + 1);

        if (!isset($this->supported[$suffix])) {
            throw new \RuntimeException("non-supported type $suffix");
        }

        return (new $this->supported[$suffix]())->readFile($path);
    }
}
