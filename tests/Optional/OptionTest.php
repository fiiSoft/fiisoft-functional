<?php

namespace FiiSoft\Test\Functional\Optional;

use FiiSoft\Functional\Optional\Lazy;
use FiiSoft\Functional\Optional\None;
use FiiSoft\Functional\Optional\Option;
use FiiSoft\Functional\Optional\Some;

class OptionTest extends \PHPUnit_Framework_TestCase
{
    public function test_it_can_create_none_option_instance()
    {
        $none = Option::none();
        
        self::assertInstanceOf(None::class, $none);
        self::assertSame($none, Option::none());
    }
    
    public function test_it_can_create_some_option_instance()
    {
        $some = Option::some(5);
    
        self::assertInstanceOf(Some::class, $some);
        self::assertNotSame($some, Option::some(5));
        
        $this->assertSame(5, $some->get());
    }
    
    public function test_it_can_create_lazy_option_instance()
    {
        $lazy = Option::lazy(function () { return 3; });
    
        self::assertInstanceOf(Lazy::class, $lazy);
        self::assertNotSame($lazy, Option::lazy(function () { return 3; }));
        
        self::assertSame(3, $lazy->get());
    }
    
    public function test_it_can_create_various_instances_with_general_method()
    {
        $none = Option::from(false, false);
        self::assertInstanceOf(None::class, $none);
    
        $some = Option::from(5);
        self::assertInstanceOf(Some::class, $some);
        $this->assertSame(5, $some->get());
    
        $lazy = Option::from(function () { return 3; });
        self::assertInstanceOf(Lazy::class, $lazy);
        self::assertSame(3, $lazy->get());
    }
}
