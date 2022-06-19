<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Tests\Rules;

use Kafkiansky\Discovery\Rules\ClassHasAttributes;
use Kafkiansky\Discovery\Tests\Stubs\A;
use Kafkiansky\Discovery\Tests\Stubs\B;
use Kafkiansky\Discovery\Tests\Stubs\H;
use Kafkiansky\Discovery\Tests\Stubs\XAttribute;
use Kafkiansky\Discovery\Tests\Stubs\YAttribute;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ClassHasAttributesTest extends TestCase
{
    public function testClassHasAttribute(): void
    {
        $rule = new ClassHasAttributes(XAttribute::class);
        self::assertTrue($rule->satisfy(A::class));

        $rule = new ClassHasAttributes(XAttribute::class, YAttribute::class);
        self::assertTrue($rule->satisfy(A::class));

        $rule = new ClassHasAttributes(XAttribute::class, YAttribute::class);
        self::assertTrue($rule->satisfy(H::class));
    }

    public function testClassNotExtends(): void
    {
        $rule = new ClassHasAttributes(XAttribute::class);
        self::assertFalse($rule->satisfy(B::class));
    }
}
