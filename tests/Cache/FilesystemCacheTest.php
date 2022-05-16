<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Tests\Cache;

use Kafkiansky\Discovery\Cache\Adapter\FilesystemCache;
use Kafkiansky\Discovery\Cache\CacheStorage;
use Kafkiansky\Discovery\Cache\NameUsingRuleHash;
use Kafkiansky\Discovery\DiscoveryRule;
use Kafkiansky\Discovery\FileNotFound;
use Kafkiansky\Discovery\InvalidJsonSchema;
use Kafkiansky\Discovery\Rules\ClassExtends;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class FilesystemCacheTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->cache()->flush();
    }

    public function testCacheNotExists(): void
    {
        self::assertFalse($this->cache()->get($this->id(new ClassExtends(\Exception::class))));
    }

    public function testCacheWritten(): void
    {
        $cache = $this->cache();

        $id = $this->id(new ClassExtends(\Exception::class));
        $cache->set($id, [FileNotFound::class, InvalidJsonSchema::class]);
        self::assertEquals(
            [
                FileNotFound::class,
                InvalidJsonSchema::class,
            ],
            $cache->get($id)
        );
    }

    public function testCacheWasNotWrittenWhenCallableRejected(): void
    {
        $cache = $this->cache(fn (): bool => false);

        $id = $this->id(new ClassExtends(\Exception::class));
        $cache->set($id, [FileNotFound::class, InvalidJsonSchema::class]);
        self::assertFalse($cache->get($id));
    }

    /**
     * @return non-empty-string
     */
    private function id(DiscoveryRule $rule): string
    {
        return (new NameUsingRuleHash())($rule);
    }

    /**
     * @psalm-param (callable():bool)|null $saveIf
     */
    private function cache(?callable $saveIf = null): CacheStorage
    {
        return new FilesystemCache(directory: __DIR__.'/../storage', cacheIf: $saveIf);
    }
}
