<?php

/**
 * Sample reference class
 */
declare(strict_types=1);

namespace Phoole\Tests\Reference;

use Phoole\Base\Reference\ReferenceTrait;
use Phoole\Base\Reference\ReferenceInterface;

class ReferenceClass implements ReferenceInterface
{
    use ReferenceTrait;

    // testing data
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    protected function resolveUnknown(string $subject, string $matched)
    {
        // leave reference untouched
        return $subject;
    }

    /**
     *  Return NULL if unknown reference found
     *
     * @inheritDoc
     */
    protected function getReference(string $name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        } else {
            return NULL;
        }
    }
}
