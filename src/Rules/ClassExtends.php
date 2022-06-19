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
    private readonly array $parents;

    /**
     * @param class-string ...$parents
     */
    public function __construct(string ...$parents)
    {
        $this->parents = $parents;
    }

    /**
     * {@inheritdoc}
     */
    public function satisfy(string $fqcn): bool
    {
        return withErrorHandling(function () use ($fqcn): bool {
            $parent = $fqcn;

            $parents = [];
            do {
                $parents[] = $parent;
            } while (false !== ($parent = get_parent_class($parent)));

            return \count(\array_intersect($this->parents, $parents)) > 0;
        });
    }
}
