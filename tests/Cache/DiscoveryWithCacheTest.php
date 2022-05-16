<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Tests\Cache;

use Kafkiansky\Discovery\Cache\CacheStorage;
use Kafkiansky\Discovery\Cache\DiscoveryWithCache;
use Kafkiansky\Discovery\Cache\NameUsingRuleHash;
use Kafkiansky\Discovery\DiscoverCode;
use Kafkiansky\Discovery\DiscoveryRule;
use Kafkiansky\Discovery\FileNotFound;
use Kafkiansky\Discovery\InvalidJsonSchema;
use Kafkiansky\Discovery\Rules\ClassExtends;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class DiscoveryWithCacheTest extends TestCase
{
    public function testClassesHaveNotBeenCachedBefore(): void
    {
        $rule = new ClassExtends(\Exception::class);

        $discovery = $this->createMock(DiscoverCode::class);

        $discovery
            ->expects($this->once())
            ->method('discover')
            ->willReturn((function (): \Traversable {
                foreach ([FileNotFound::class, InvalidJsonSchema::class] as $class) {
                    yield $class;
                }
            })())
        ;

        $cache = $this->createMock(CacheStorage::class);

        $cache
            ->expects($this->once())
            ->method('get')
            ->willReturn(false);

        $cache
            ->expects($this->once())
            ->method('set')
            ->with((new NameUsingRuleHash())($rule), [FileNotFound::class, InvalidJsonSchema::class]);

        $discoveryWithCache = new DiscoveryWithCache($discovery, $cache);

        self::assertEquals([FileNotFound::class, InvalidJsonSchema::class], \iterator_to_array($discoveryWithCache->discover($rule)));
    }

    public function testClassesHaveBeenCachedBefore(): void
    {
        $rule = new ClassExtends(\Exception::class);

        $discovery = $this->createMock(DiscoverCode::class);

        $discovery
            ->expects($this->never())
            ->method('discover');
        ;

        $cache = $this->createMock(CacheStorage::class);

        $cache
            ->expects($this->once())
            ->method('get')
            ->willReturn([FileNotFound::class, InvalidJsonSchema::class]);

        $cache
            ->expects($this->never())
            ->method('set');

        $discoveryWithCache = new DiscoveryWithCache($discovery, $cache);

        self::assertEquals([FileNotFound::class, InvalidJsonSchema::class], \iterator_to_array($discoveryWithCache->discover($rule)));
    }

    public function testCustomIdentifierGeneratorWasCalled(): void
    {
        $rule = new ClassExtends(\Exception::class);

        $discovery = $this->createMock(DiscoverCode::class);

        $discovery
            ->expects($this->once())
            ->method('discover')
            ->willReturn((function (): \Traversable {
                foreach ([FileNotFound::class, InvalidJsonSchema::class] as $class) {
                    yield $class;
                }
            })())
        ;

        $cache = $this->createMock(CacheStorage::class);

        $cache
            ->expects($this->once())
            ->method('get')
            ->willReturn(false);

        $cache
            ->expects($this->once())
            ->method('set')
            ->with(\str_replace('\\', '', \get_class($rule)), [FileNotFound::class, InvalidJsonSchema::class]);

        $discoveryWithCache = new DiscoveryWithCache($discovery, $cache, function (DiscoveryRule $rule): string {
            /** @var non-empty-string */
            return \str_replace('\\', '', \get_class($rule));
        });

        self::assertEquals([FileNotFound::class, InvalidJsonSchema::class], \iterator_to_array($discoveryWithCache->discover($rule)));
    }
}
