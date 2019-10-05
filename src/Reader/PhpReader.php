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
 * PhpReader
 *
 * @package Phoole\Base
 */
class PhpReader extends AbstractReader
{
    /**
     * {@inheritDoc}
     */
    protected function readFromFile($path)
    {
        try {
            return include $path;
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }
}
