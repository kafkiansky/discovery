<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Tests\Rules;

use Kafkiansky\Discovery\Rules\All;
use Kafkiansky\Discovery\Rules\ClassExtends;
use Kafkiansky\Discovery\Rules\ClassImplements;
use Kafkiansky\Discovery\Rules\ClassUses;
use Kafkiansky\Discovery\Tests\Stubs\A;
use Kafkiansky\Discovery\Tests\Stubs\B;
use Kafkiansky\Discovery\Tests\Stubs\C;
use Kafkiansky\Discovery\Tests\Stubs\D;
use Kafkiansky\Discovery\Tests\Stubs\E;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class AllTest extends TestCase
{
    public function testAllRuleMatches(): void
    {
        $rule = new All(
            new ClassImplements(C::class),
            new ClassUses(E::class),
            new ClassExtends(B::class),
        );

        self::assertTrue($rule->satisfy(A::class));
    }

    public function testOneFromRuleNotMatched(): void
    {
        $rule = new All(
            new ClassImplements(C::class),
            new ClassUses(D::class),
        );

        self::assertFalse($rule->satisfy(B::class));
    }

    public function testAll(): void
    {
        self::assertTrue((new All())->satisfy(A::class));
    }
}
