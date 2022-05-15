<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Tests\Rules;

use Kafkiansky\Discovery\Rules\ClassExtends;
use Kafkiansky\Discovery\Tests\Stubs\A;
use Kafkiansky\Discovery\Tests\Stubs\B;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ClassExtendsTest extends TestCase
{
    public function testClassExtends(): void
    {
        $rule = new ClassExtends(B::class);
        self::assertTrue($rule->satisfy(A::class));
    }

    public function testClassNotExtends(): void
    {
        $rule = new ClassExtends(TestCase::class);
        self::assertFalse($rule->satisfy(A::class));
    }
}
