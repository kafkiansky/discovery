<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Tests\Rules;

use Kafkiansky\Discovery\Rules\Any;
use Kafkiansky\Discovery\Rules\ClassImplements;
use Kafkiansky\Discovery\Rules\ClassUses;
use Kafkiansky\Discovery\Tests\Stubs\A;
use Kafkiansky\Discovery\Tests\Stubs\B;
use Kafkiansky\Discovery\Tests\Stubs\C;
use Kafkiansky\Discovery\Tests\Stubs\D;
use Kafkiansky\Discovery\Tests\Stubs\F;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class AnyTest extends TestCase
{
    public function testAnyRuleMatched(): void
    {
        $rule = new Any(
            new ClassImplements(C::class),
            new ClassUses(D::class),
        );

        self::assertTrue($rule->satisfy(B::class));
    }

    public function testNoRuleMatched(): void
    {
        $rule = new Any(
            new ClassImplements(C::class),
            new ClassUses(F::class),
        );

        self::assertFalse($rule->satisfy(B::class));
    }

    public function testAny(): void
    {
        self::assertFalse((new Any())->satisfy(A::class));
    }
}
