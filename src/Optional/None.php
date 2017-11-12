<?php

namespace FiiSoft\Functional\Optional;

use Closure;

final class None extends Option
{
    /**
     * @return bool
     */
    public function isPresent()
    {
        return false;
    }
    
    /**
     * @return bool
     */
    public function isEmpty()
    {
        return true;
    }
    
    /**
     * @throws \RuntimeException
     * @return mixed
     */
    public function get()
    {
        throw new \RuntimeException('Method get() called on None Option');
    }
    
    /**
     * @param mixed $else
     * @throws \RuntimeException
     * @return mixed
     */
    public function getOrElse($else)
    {
        if ($else instanceof Option) {
            return $this->getOrElse($else->get());
        }
    
        if ($else instanceof Closure) {
            return $this->getOrElse($else());
        }
        
        return $else;
    }
    
    /**
     * @param mixed $else
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @return Option
     */
    public function orElse($else)
    {
        return self::from($else);
    }
}