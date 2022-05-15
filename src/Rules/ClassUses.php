<?php

namespace Kafkiansky\Discovery\Rules;

use Kafkiansky\Discovery\DiscoveryRule;
use function Kafkiansky\Discovery\withErrorHandling;

final class ClassUses implements DiscoveryRule
{
    /**
     * @var non-empty-string[]
     */
    private array $traits;

    /**
     * @param non-empty-string[]|non-empty-string $traits
     */
    public function __construct(array|string $traits, private bool $usesAll = true)
    {
        $this->traits = \is_string($traits) ? [$traits] : $traits;
    }

    /**
     * {@inheritdoc}
     */
    public function satisfy(string $fqcn): bool
    {
        return withErrorHandling(function () use ($fqcn): bool {
            $intersect = \array_intersect($this->traits, $this->classUsesTraits($fqcn));

            return $this->usesAll ? \count($intersect) === \count($this->traits) : \count($intersect) > 0;
        });
    }

    /**
     * @param class-string $class
     *
     * @return non-empty-string[]
     */
    private function classUsesTraits(string $class): array
    {
        $traits = [];

        $parents = \class_parents($class);

        if ($parents === false) {
            return [];
        }

        foreach ($parents + [$class] as $class) {
            $traits += $this->traitUsesTrait($class);
        }

        return \array_unique($traits);
    }

    /**
     * @return non-empty-string[]
     */
    private function traitUsesTrait(string $class): array
    {
        $traits = \class_uses($class);

        if ($traits === false) {
            return [];
        }

        foreach ($traits as $trait) {
            $traits += $this->traitUsesTrait($trait);
        }

        /** @var non-empty-string[] */
        return $traits;
    }
}
