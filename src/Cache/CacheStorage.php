<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Cache;

interface CacheStorage
{
    /**
     * @param non-empty-string $id
     *
     * @return class-string[]|false
     */
    public function get(string $id): array|false;

    /**
     * @param non-empty-string $id
     * @param class-string[] $classes
     */
    public function set(string $id, array $classes): void;
    public function flush(): void;
}
