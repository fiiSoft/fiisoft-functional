<?php

namespace FiiSoft\Functional\Optional;

use Closure;

abstract class Option
{
    /** @var None */
    private static $noneInstance;
    
    /**
     * @return None
     */
    public static function none()
    {
        if (!self::$noneInstance) {
            self::$noneInstance = new None();
        }
        return self::$noneInstance;
    }
    
    /**
     * @param mixed $value
     * @throws \RuntimeException
     * @return Some
     */
    public static function some($value)
    {
        return new Some($value);
    }
    
    /**
     * @param Closure $closure
     * @param mixed|null $none
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @return Lazy
     */
    public static function lazy(Closure $closure, $none = null)
    {
        return new Lazy($closure, $none);
    }
    
    /**
     * @param mixed $value
     * @param mixed|null $none
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @return Option
     */
    public static function from($value, $none = null)
    {
        if ($value === $none) {
            return self::none();
        }
    
        if ($value instanceof None) {
            return $value;
        }
    
        if ($value instanceof Closure) {
            return new Lazy($value, $none);
        }
    
        if ($value instanceof Lazy) {
            return new Lazy($value, $none);
        }
    
        if ($value instanceof Option) {
            return self::from($value->get(), $none);
        }
    
        return new Some($value);
    }
    
    /**
     * @return bool
     */
    abstract public function isPresent();
    
    /**
     * @return bool
     */
    abstract public function isEmpty();
    
    /**
     * @throws \RuntimeException
     * @return mixed
     */
    abstract public function get();
    
    /**
     * @param mixed $else
     * @return mixed
     */
    abstract public function getOrElse($else);
    
    /**
     * @param mixed $else
     * @return Option Be aware this can be the same object or a new object! It depends on implementation and can vary.
     */
    abstract public function orElse($else);
}