<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Tests;

use Kafkiansky\Discovery\FileNotFound;
use PHPUnit\Framework\TestCase;
use function Kafkiansky\Discovery\withPath;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class WithPathTest extends TestCase
{
    public function providePaths(): \Generator
    {
        yield [__DIR__.'/../composer.json', [__DIR__.'/../', 'composer.json']];
        yield [__DIR__.'/../phpunit.xml', [__DIR__.'/../', 'phpunit.xml']];
        yield [__DIR__.'/../psalm.xml', [__DIR__.'/../', 'psalm.xml']];
    }

    /**
     * @dataProvider providePaths
     *
     * @param non-empty-string $assertWith
     * @param array{non-empty-string, non-empty-string} $arrange
     */
    public function testPathExists(string $assertWith, array $arrange): void
    {
        list($basePath, $parts) = $arrange;

        self::assertEquals($assertWith, withPath($basePath, $parts));
    }

    public function testPathDoesntExists(): void
    {
        self::expectException(FileNotFound::class);
        withPath(__DIR__. '/Some.php');
    }
}
