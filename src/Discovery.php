<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery;

use Kafkiansky\Discovery\CodeLocation\ClassLoader;

final class Discovery implements DiscoverCode
{
    public function __construct(private ClassLoader $classLoader)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function discover(DiscoveryRule $rule): \Traversable
    {
        foreach ($this->classLoader->load() as $class) {
            if ($rule->satisfy($class)) {
                yield $class;
            }
        }
    }
}
