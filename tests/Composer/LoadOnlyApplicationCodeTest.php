<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Tests\Composer;

use Kafkiansky\Discovery\CodeLocation\Composer\LoadOnlyApplicationCode;
use Kafkiansky\Discovery\Rules\All;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class LoadOnlyApplicationCodeTest extends TestCase
{
    public function testClassShouldBeLoad(): void
    {
        $constraint = new LoadOnlyApplicationCode(
            __DIR__.'/../../'
        );

        self::assertTrue($constraint->shouldLoad(All::class));
    }

    public function testClassShouldNotBeLoad(): void
    {
        $constraint = new LoadOnlyApplicationCode(
            __DIR__.'/../../'
        );

        self::assertFalse($constraint->shouldLoad(TestCase::class));
    }
}
