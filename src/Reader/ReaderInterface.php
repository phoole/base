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
 * ReaderInterface
 *
 * @package Phoole\Base
 */
interface ReaderInterface
{
    /**
     * Read, parse & return contents from the $path
     *
     * @param  string $path the full path
     * @return mixed
     * @throws \RuntimeException  if something goes wrong
     */
    public function readFile(string $path);
}
