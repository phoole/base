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
 * YamlReader
 *
 * @package Phoole\Base
 */
class YamlReader extends AbstractReader
{
    /**
     * {@inheritDoc}
     */
    protected function readFromFile($path)
    {
        try {
            $data = \yaml_parse_file($path);
            if ($data === false) {
                throw new \RuntimeException("Parse $path error");
            }
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
        }
        return $data;
    }
}
