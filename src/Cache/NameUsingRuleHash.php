<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Cache;

use Kafkiansky\Discovery\DiscoveryRule;

final class NameUsingRuleHash
{
    /**
     * @return non-empty-string
     */
    public function __invoke(DiscoveryRule $rule): string
    {
        /** @var non-empty-string */
        return \sha1(\serialize($rule));
    }
}
