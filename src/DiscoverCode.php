<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery;

interface DiscoverCode
{
    /**
     * @throws \Throwable
     *
     * @return \Traversable<class-string>
     */
    public function discover(DiscoveryRule $rule): \Traversable;
}
