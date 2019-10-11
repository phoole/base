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
 * ReferenceTrait
 *
 * @package Phoole\Base
 */
trait ReferenceTrait
{
    /**
     * default is '${' and '}'
     *
     * @var string
     */
    protected $ref_pattern = '~(\$\{(\w((?!\$\{|\}).)*)\})~';

    /**
     * @inheritDoc
     */
    public function setReferencePattern(string $start, string $end): object
    {
        $s = preg_quote($start);
        $e = preg_quote($end);
        $this->ref_pattern = sprintf("~(%s(\w((?!%s|%s).)*)%s)~", $s, $s, $e, $e);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function deReference(&$subject): void
    {
        if (is_string($subject)) {
            $subject = $this->deReferenceString($subject);
        }

        if (!is_array($subject)) {
            return;
        }

        foreach ($subject as &$data) {
            $this->dereference($data);
        }
    }

    /**
     * Replace all references in the target string (recursively)
     *
     * @param  string $subject
     * @return mixed
     * @throws \RuntimeException if bad thing happens
     */
    protected function deReferenceString(string $subject)
    {
        $loop = 0;
        // recursive matching in string
        while (preg_match($this->ref_pattern, $subject, $matched)) {
            if ($loop++ > 20) {
                throw new \RuntimeException("Loop in resolving $subject");
            }

            $ref = $this->resolveReference($matched[2]);
            if (is_string($ref)) {
                $subject = str_replace($matched[1], $ref, $subject);
            } else {
                return $this->checkValue($ref, $subject, $matched[1]);
            }
        }
        return $subject;
    }

    /**
     * Check dereferenced value
     *
     * @param  mixed  $value
     * @param  string $subject  the subject to dereference
     * @param  string $matched  the matched reference
     * @return mixed
     * @throws \RuntimeException if $subject malformed
     */
    protected function checkValue($value, string $subject, string $matched)
    {
        // reference not resolved
        if (is_null($value)) {
            return $this->resolveUnknown($subject, $matched);
        }

        // malformed subject
        if ($subject != $matched) {
            throw new \RuntimeException("Error in resolving $matched");
        }

        // good match
        return $value;
    }

    /**
     * resolving the references
     *
     * @param  mixed $name
     * @return mixed
     * @throws \RuntimeException if bad thing happens
     */
    protected function resolveReference(string $name)
    {
        if (is_string($name)) {
            return $this->getReference($name);
        }
        return $name;
    }

    /**
     * Dealing with unknown reference, may leave it untouched
     *
     * @param  string $subject  the subject to dereference
     * @param  string $matched  the matched reference
     * @return mixed
     * @throws \RuntimeException if $subject malformed
     */
    protected function resolveUnknown(string $subject, string $matched)
    {
        throw new \RuntimeException("Unable to resolve $matched in $subject");
    }

    /**
     * resolve the references
     *
     * @param  string $name
     * @return mixed
     * @throws \RuntimeException if bad thing happens
     */
    abstract protected function getReference(string $name);
}
