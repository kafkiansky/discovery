<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Tests\Rules;

use Kafkiansky\Discovery\Rules\ClassUses;
use Kafkiansky\Discovery\Tests\Stubs\A;
use Kafkiansky\Discovery\Tests\Stubs\B;
use Kafkiansky\Discovery\Tests\Stubs\D;
use Kafkiansky\Discovery\Tests\Stubs\E;
use Kafkiansky\Discovery\Tests\Stubs\F;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class TraitUsesTest extends TestCase
{
    public function testUsesTrait(): void
    {
        $rule = new ClassUses(D::class);
        self::assertTrue($rule->satisfy(B::class));
    }

    public function testUsesTraitFromParent(): void
    {
        $rule = new ClassUses(D::class);
        self::assertTrue($rule->satisfy(A::class));

        $rule = new ClassUses([E::class, D::class]);
        self::assertTrue($rule->satisfy(A::class));
    }

    public function testUsesTraitFromTrait(): void
    {
        $rule = new ClassUses([F::class]);
        self::assertTrue($rule->satisfy(A::class));
    }

    public function testUsesAllTraits(): void
    {
        $rule = new ClassUses([D::class, E::class, F::class]);
        self::assertTrue($rule->satisfy(A::class));

        $rule = new ClassUses([D::class, E::class, F::class]);
        self::assertFalse($rule->satisfy(B::class));
    }

    public function testUsesAnyTrait(): void
    {
        $rule = new ClassUses(traits: [D::class, E::class, F::class], usesAll: false);
        self::assertTrue($rule->satisfy(B::class));
    }
}