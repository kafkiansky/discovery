<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Cache;

use Kafkiansky\Discovery\DiscoverCode;
use Kafkiansky\Discovery\DiscoveryRule;

final class DiscoveryWithCache implements DiscoverCode
{
    /**
     * @var callable(DiscoveryRule):non-empty-string
     */
    private $cacheNameGenerator;

    /**
     * @psalm-param (callable(DiscoveryRule):non-empty-string)|null $cacheNameGenerator
     */
    public function __construct(
        private DiscoverCode $delegate,
        private CacheStorage $cache,
        ?callable $cacheNameGenerator = null
    ) {
        $this->cacheNameGenerator = $cacheNameGenerator ?: new NameUsingRuleHash();
    }

    public function discover(DiscoveryRule $rule): \Traversable
    {
        $id = ($this->cacheNameGenerator)($rule);

        if (($cached = $this->cache->get($id)) !== false) {
            /** @var \Traversable<class-string> */
            return yield from $cached;
        }

        $classes = \iterator_to_array($this->delegate->discover($rule));

        $this->cache->set($id, $classes);

        yield from $classes;
    }
}
