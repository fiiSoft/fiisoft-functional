<?php

namespace FiiSoft\Test\Functional\Optional;

use FiiSoft\Functional\Optional\Option;

class LazyTest extends \PHPUnit_Framework_TestCase
{
    public function test_it_can_evaluate_to_some_option()
    {
        $lazy = Option::lazy(function () { return true; }, false);
        
        self::assertTrue($lazy->isPresent());
        self::assertFalse($lazy->isEmpty());
        self::assertTrue($lazy->get());
        self::assertTrue($lazy->getOrElse(false));
    }
    
    public function test_it_can_evaluate_to_empty_option()
    {
        $lazy = Option::lazy(function () { return false; }, false);
        
        self::assertTrue($lazy->isEmpty());
        self::assertFalse($lazy->isPresent());
        self::assertTrue($lazy->getOrElse(true));
    
        try {
            $lazy->get();
            self::fail('Expected RuntimeException not thrown');
        } catch (\RuntimeException $e) {
            //expected
        }
    }
    
    public function test_it_can_use_alternative_values()
    {
        $option = Option::lazy(function () { return 'a'; }, 'a')
            ->orElse(function () { return null; })
            ->orElse(null)
            ->orElse(Option::none())
            ->orElse(Option::from(5))
            ->orElse('ddd');
        
        self::assertTrue($option->isPresent());
        self::assertFalse($option->isEmpty());
        self::assertSame(5, $option->get());
    }
    
    public function test_it_evaluates_only_once()
    {
        $count = 0;
        $option = Option::lazy(function () use (&$count) { return ++$count; });
        
        self::assertTrue($option->isPresent());
        self::assertSame(1, $option->get());
        self::assertSame(1, $option->get());
        
        self::assertSame(1, $count);
    }
}
