<?php

namespace FiiSoft\Test\Functional\Optional;

use FiiSoft\Functional\Optional\Option;
use FiiSoft\Functional\Optional\Some;

class SomeTest extends \PHPUnit_Framework_TestCase
{
    public function test_it_behaves_like_expected_from_some_option()
    {
        $some = new Some('ooo');
    
        self::assertTrue($some->isPresent());
        self::assertFalse($some->isEmpty());
        self::assertSame('ooo', $some->get());
        self::assertSame('ooo', $some->getOrElse('ala'));
    }
    
    public function test_it_can_be_created_from_other_non_empty_option()
    {
        $some = new Some(Option::lazy(function () { return 1; }));
        
        self::assertInstanceOf(Some::class, $some);
        self::assertSame(1, $some->get());
        
        $other = Option::some(new Some('a'));
        self::assertSame('a', $other->get());
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function test_it_cannot_be_created_from_other_empty_option()
    {
        Option::some(Option::none());
    }
    
    public function test_it_can_use_alternative_values()
    {
        $option = Option::some(8)
            ->orElse(Option::lazy(function () { return 4; }, 4))
            ->orElse(function () { return null; })
            ->orElse(null)
            ->orElse(Option::none())
            ->orElse(2)
            ->orElse(new Some(5));
        
        self::assertTrue($option->isPresent());
        self::assertFalse($option->isEmpty());
        self::assertSame(8, $option->get());
    }
}
