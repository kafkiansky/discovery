<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Rules;

use Kafkiansky\Discovery\DiscoveryRule;
use function Kafkiansky\Discovery\withErrorHandling;

final class ClassExtends implements DiscoveryRule
{
    /**
     * @var class-string[]
     */
    private array $parents;

    /**
     * @param class-string[]|class-string $parents
     */
    public function __construct(array|string $parents)
    {
        $this->parents = \is_string($parents) ? [$parents] : $parents;
    }

    /**
     * {@inheritdoc}
     */
    public function satisfy(string $fqcn): bool
    {
        return withErrorHandling(function () use ($fqcn): bool {
            $parent = \get_parent_class($fqcn);

            if ($parent === false) {
                return false;
            }

            return \in_array($parent, $this->parents);
        });
    }
}
