<?php

/**
 * Phoole (PHP7.2+)
 *
 * @category  Library
 * @package   Phoole\Base
 * @copyright Copyright (c) 2019 Hong Zhang
 */
declare(strict_types=1);

namespace Phoole\Base\Reflect;

/**
 * ParameterTrait
 *
 * try get parameters of the callable / constructor etc.
 *
 * @package Phoole\Base
 */
trait ParameterTrait
{
    /**
     * Get class/object constructor parameters
     *
     * @param  string|object $class   class name or object
     * @return \ReflectionParameter[]
     * @throws \InvalidArgumentException if something goes wrong
     */
    protected function getConstructorParameters($class): array
    {
        try {
            $reflector = new \ReflectionClass($class);
            $constructor = $reflector->getConstructor();
            return is_null($constructor) ? [] : $constructor->getParameters();
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
    }

    /**
     * Get callable parameters
     *
     * @param  callable $callable
     * @return \ReflectionParameter[]
     * @throws \InvalidArgumentException if something goes wrong
     */
    protected function getCallableParameters(callable $callable): array
    {
        try {
            // [class, method]
            if (is_array($callable)) {
                $reflector = new \ReflectionClass($callable[0]);
                $method = $reflector->getMethod($callable[1]);

            // function or closure
            } elseif (is_string($callable) || $callable instanceof \Closure) {
                $method = new \ReflectionFunction($callable);

            // object with __invoke() defined
            } elseif (is_object($callable)) {
                $reflector = new \ReflectionClass($callable);
                $method = $reflector->getMethod('__invoke');

            // unknown callable ?
            } else {
                throw new \InvalidArgumentException('unknown type of callable');
            }
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
        return $method->getParameters();
    }
}
