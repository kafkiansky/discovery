<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Tests;

use Kafkiansky\Discovery\CodeLocation\ArrayClassLoader;
use Kafkiansky\Discovery\Discovery;
use Kafkiansky\Discovery\DiscoveryRule;
use Kafkiansky\Discovery\FileNotFound;
use Kafkiansky\Discovery\InvalidJsonSchema;
use Kafkiansky\Discovery\Rules\All;
use Kafkiansky\Discovery\Rules\Any;
use Kafkiansky\Discovery\Rules\ClassExtends;
use Kafkiansky\Discovery\Rules\ClassImplements;
use Kafkiansky\Discovery\Rules\None;
use Kafkiansky\Discovery\Rules\ClassUses;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class DiscoveryTest extends TestCase
{
    public function testClassesDiscovered(): void
    {
        $discovery = new Discovery(
            new ArrayClassLoader([
                All::class,
                Any::class,
                None::class,
                ClassExtends::class,
                ClassImplements::class,
                ClassUses::class,
                FileNotFound::class,
                InvalidJsonSchema::class,
                \InvalidArgumentException::class,
            ])
        );

        $classes = \iterator_to_array($discovery->discover(new ClassImplements(DiscoveryRule::class)));
        self::assertCount(6, $classes);
        self::assertEquals(
            [
                All::class,
                Any::class,
                None::class,
                ClassExtends::class,
                ClassImplements::class,
                ClassUses::class,
            ],
            $classes,
        );
    }
}
