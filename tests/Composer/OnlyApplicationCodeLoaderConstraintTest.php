<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Tests\Composer;

use Kafkiansky\Discovery\CodeLocation\Composer\OnlyApplicationCodeLoaderConstraint;
use Kafkiansky\Discovery\Rules\All;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class OnlyApplicationCodeLoaderConstraintTest extends TestCase
{
    public function testClassShouldBeLoad(): void
    {
        $constraint = new OnlyApplicationCodeLoaderConstraint(
            __DIR__.'/../../'
        );

        self::assertTrue($constraint->shouldLoad(All::class));
    }

    public function testClassShouldNotBeLoad(): void
    {
        $constraint = new OnlyApplicationCodeLoaderConstraint(
            __DIR__.'/../../'
        );

        self::assertFalse($constraint->shouldLoad(TestCase::class));
    }
}
