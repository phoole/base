<?php

/**
 * Phoole (PHP7.2+)
 *
 * @category  Library
 * @package   Phoole\Base
 * @copyright Copyright (c) 2019 Hong Zhang
 */
declare(strict_types=1);

namespace Phoole\Base\Reference;

/**
 * ReferenceInterface
 *
 * @package Phoole\Base
 */
interface ReferenceInterface
{
    /**
     * Set open/closing chars for reference pattern, such as '${' and '}'
     *
     * e.g.
     * ```php
     * // reset reference pattern, starts with '%{' and ends with '%}
     * $this->setReference('%{', '%}');
     * ```
     *
     * @param  string $start  start chars
     * @param  string $end    ending chars
     * @return object $this
     */
    public function setReferencePattern(string $start, string $end): object;

    /**
     * Replace all references in the target (recursively)
     *
     * @param  mixed &$subject
     * @return void
     * @throws \RuntimeException if bad thing happens
     */
    public function deReference(&$subject): void;
}
