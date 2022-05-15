<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Tests\Composer;

use Kafkiansky\Discovery\CodeLocation\Composer\ComposerClassmapClassLoader;
use Kafkiansky\Discovery\CodeLocation\Composer\LoadOnlyApplicationCode;
use Kafkiansky\Discovery\Rules\All;
use Kafkiansky\Discovery\Rules\None;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ComposerClassmapClassLoaderTest extends TestCase
{
    public function testClassesLoadedFromAutoloadClassmapOnlyForApplicationCode(): void
    {
        $loader = new ComposerClassmapClassLoader(
            __DIR__.'/../../',
            new LoadOnlyApplicationCode(__DIR__.'/../../')
        );

        $classes = \iterator_to_array($loader->load());

        self::assertCount(\count($classes), \array_filter($classes, function (string $class): bool {
            return \str_starts_with($class, "Kafkiansky\\Discovery\\");
        }));
    }

    public function testClassesLoadedFromAutoloadClassmapFromEntireVendor(): void
    {
        $loader = new ComposerClassmapClassLoader(__DIR__.'/../../');

        $classes = \iterator_to_array($loader->load());

        self::assertTrue(\count($classes) > \count(\array_filter($classes, function (string $class): bool {
            return \str_starts_with($class, "Kafkiansky\\Discovery\\");
        })));
    }

    public function testClassLoadedUsingCustomLoader(): void
    {
        $loader = new ComposerClassmapClassLoader(__DIR__.'/../../');
        $loader = $loader->withClassMapLoader(function (string $_path): array {
            return [
                All::class => All::class,
                None::class => None::class,
            ];
        });

        $classes = \iterator_to_array($loader->load());
        self::assertEquals(
            [
                All::class,
                None::class,
            ],
            $classes,
        );
    }
}
