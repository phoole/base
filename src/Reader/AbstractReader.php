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
 * AbstractReader
 *
 * @package Phoole\Base
 */
abstract class AbstractReader implements ReaderInterface
{
    /**
     * {@inheritDoc}
     */
    public function readFile(string $path)
    {
        if (!file_exists($path)) {
            throw new \RuntimeException("$path not found");
        }

        if (!is_readable($path)) {
            throw new \RuntimeException("$path not readable");
        }

        // read file
        return $this->readFromFile($path);
    }

    /**
     * Truly read from the file
     *
     * @param  string $path
     * @throws \RuntimeException  if something goes wrong
     * @return mixed
     */
    abstract protected function readFromFile($path);
}
