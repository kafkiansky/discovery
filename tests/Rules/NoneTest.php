<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Tests\Rules;

use Kafkiansky\Discovery\Rules\ClassImplements;
use Kafkiansky\Discovery\Rules\None;
use Kafkiansky\Discovery\Tests\Stubs\A;
use Kafkiansky\Discovery\Tests\Stubs\B;
use Kafkiansky\Discovery\Tests\Stubs\C;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class NoneTest extends TestCase
{
    public function testRuleMatched(): void
    {
        $rule = new None(new ClassImplements(C::class));
        self::assertFalse($rule->satisfy(A::class));
    }

    public function testNoRuleMatched(): void
    {
        $rule = new None(new ClassImplements(C::class));
        self::assertTrue($rule->satisfy(B::class));
    }

    public function testNone(): void
    {
        self::assertTrue((new None())->satisfy(A::class));
    }
}
