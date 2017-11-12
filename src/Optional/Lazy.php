<?php

namespace FiiSoft\Functional\Optional;

use Closure;

final class Lazy extends Option
{
    /** @var Closure */
    private $closure;
    
    /** @var Lazy */
    private $lazy;
    
    /** @var mixed|null */
    private $noneValue;
    
    /** @var bool */
    private $evaluated = false;
    
    /** @var bool */
    private $isSome = false;
    
    /** @var mixed */
    private $value;
    
    /** @var array */
    private $orElse = [];
    
    /**
     * @param Closure|Lazy $closure
     * @param mixed|null $noneValue
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function __construct($closure, $noneValue = null)
    {
        $this->noneValue = $noneValue;
        
        if ($closure instanceof Closure) {
            $this->closure = $closure;
        } elseif ($closure instanceof Lazy) {
            $this->lazy = $closure;
        } else {
            throw new \InvalidArgumentException('Invalid param');
        }
    }
    
    /**
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @return bool
     */
    public function isPresent()
    {
        $this->evaluate();
        
        return $this->isSome;
    }
    
    /**
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @return bool
     */
    public function isEmpty()
    {
        $this->evaluate();
        
        return !$this->isSome;
    }
    
    /**
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @return mixed
     */
    public function get()
    {
        $this->evaluate();
    
        if ($this->isSome) {
            return $this->value;
        }
        
        throw new \RuntimeException('Impossible to return value from None Option');
    }
    
    /**
     * @param mixed $else
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @return mixed
     */
    public function getOrElse($else)
    {
        $this->evaluate();
    
        return $this->isSome
            ? $this->value
            : self::none()->getOrElse($else);
    }
    
    /**
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @return void
     */
    private function evaluate()
    {
        if ($this->evaluated) {
            return;
        }
    
        if ($this->closure) {
            $func = $this->closure;
            $this->value = self::none()->getOrElse($func());
        } else {
            $this->value = $this->lazy->getOrElse($this->noneValue);
        }
        
        $this->isSome = $this->value !== $this->noneValue;
    
        if (!$this->isSome) {
            foreach ($this->orElse as $else) {
                $value = self::from($else);
                if ($value->isPresent()) {
                    $this->value = $value->get();
                    $this->isSome = true;
                    break;
                }
            }
        }
        
        $this->evaluated = true;
    }
    
    /**
     * @param mixed $else
     * @return Option
     */
    public function orElse($else)
    {
        $this->orElse[] = $else;
        return $this;
    }
}