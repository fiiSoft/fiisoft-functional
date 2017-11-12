<?php

namespace FiiSoft\Test\Functional\Optional;

use FiiSoft\Functional\Optional\Lazy;
use FiiSoft\Functional\Optional\None;
use FiiSoft\Functional\Optional\Option;
use FiiSoft\Functional\Optional\Some;

class NoneTest extends \PHPUnit_Framework_TestCase
{
    public function test_it_behaves_like_expected_from_none_option()
    {
        $none = Option::none();
        
        self::assertTrue($none->isEmpty());
        self::assertFalse($none->isPresent());
        self::assertSame('ala', $none->getOrElse('ala'));
    }
    
    /**
     * @expectedException \RuntimeException
     */
    public function test_it_throws_exception_on_get()
    {
        Option::none()->get();
    }
    
    public function test_it_can_use_alternative_values()
    {
        $option = Option::none()
            ->orElse(new Lazy(function () { return 4; }, 4))
            ->orElse(function () { return null; })
            ->orElse(null)
            ->orElse(new None())
            ->orElse(2)
            ->orElse(new Some(5));
        
        self::assertTrue($option->isPresent());
        self::assertFalse($option->isEmpty());
        self::assertSame(2, $option->get());
    }
}
