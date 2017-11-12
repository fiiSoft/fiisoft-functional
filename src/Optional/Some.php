<?php

namespace FiiSoft\Functional\Optional;

use Closure;

final class Some extends Option
{
    /** @var mixed */
    private $value;
    
    /**
     * @param mixed $value
     * @throws \RuntimeException
     */
    public function __construct($value)
    {
        if ($value instanceof Option || $value instanceof Closure) {
            $this->value = self::none()->getOrElse($value);
        } else {
            $this->value = $value;
        }
    }
    
    /**
     * @return bool
     */
    public function isPresent()
    {
        return true;
    }
    
    /**
     * @return bool
     */
    public function isEmpty()
    {
        return false;
    }
    
    /**
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }
    
    /**
     * @param mixed $else
     * @return mixed
     */
    public function getOrElse($else)
    {
        return $this->value;
    }
    
    /**
     * @param mixed $else
     * @return Option
     */
    public function orElse($else)
    {
        return $this;
    }
}