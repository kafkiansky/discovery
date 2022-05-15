<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery;

interface DiscoveryRule
{
    /**
     * @param class-string $fqcn
     *
     * @throws \Throwable
     */
    public function satisfy(string $fqcn): bool;
}
