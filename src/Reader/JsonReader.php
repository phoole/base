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
 * JsonReader
 *
 * @package Phoole\Base
 */
class JsonReader extends AbstractReader
{
    /**
     * {@inheritDoc}
     */
    protected function readFromFile($path)
    {
        try {
            $data = \json_decode(\file_get_contents($path), true);
            if ($data === null) {
                throw new \RuntimeException(\json_last_error_msg());
            }
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
        return $data;
    }
}
