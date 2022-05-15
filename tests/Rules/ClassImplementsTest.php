<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Tests\Rules;

use Kafkiansky\Discovery\Rules\ClassImplements;
use Kafkiansky\Discovery\Tests\Stubs\A;
use Kafkiansky\Discovery\Tests\Stubs\C;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ClassImplementsTest extends TestCase
{
    public function testClassImplements(): void
    {
        $rule = new ClassImplements(C::class);
        self::assertTrue($rule->satisfy(A::class));
    }

    public function testClassNotImplements(): void
    {
        $rule = new ClassImplements(\ArrayAccess::class);
        self::assertFalse($rule->satisfy(A::class));
    }

    public function testClassImplementsAll(): void
    {
        $rule = new ClassImplements(interfaces: [C::class, \Stringable::class], implementsAll: true);
        self::assertTrue($rule->satisfy(A::class));

        $rule = new ClassImplements(interfaces: [C::class, \ArrayAccess::class], implementsAll: true);
        self::assertFalse($rule->satisfy(A::class));
    }

    public function testClassImplementsAny(): void
    {
        $rule = new ClassImplements(interfaces: [C::class, \ArrayAccess::class], implementsAll: false);
        self::assertTrue($rule->satisfy(A::class));

        $rule = new ClassImplements(interfaces: [\Traversable::class, \ArrayAccess::class], implementsAll: false);
        self::assertFalse($rule->satisfy(A::class));
    }
}
