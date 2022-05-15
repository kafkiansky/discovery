<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Rules;

use Kafkiansky\Discovery\DiscoveryRule;
use function Kafkiansky\Discovery\withErrorHandling;

final class ClassImplements implements DiscoveryRule
{
    /**
     * @var class-string[]
     */
    private array $interfaces;

    /**
     * @param class-string[]|class-string $interfaces
     */
    public function __construct(array|string $interfaces, private bool $implementsAll = true)
    {
        $this->interfaces = \is_string($interfaces) ? [$interfaces] : $interfaces;
    }

    /**
     * {@inheritdoc}
     */
    public function satisfy(string $fqcn): bool
    {
        return withErrorHandling(function () use ($fqcn): bool {
            $implementedInterfaces = \class_implements($fqcn);

            if ($implementedInterfaces === false) {
                return false;
            }

            $intersect = \array_intersect($this->interfaces, \array_values($implementedInterfaces));

            return $this->implementsAll ? \count($intersect) === \count($this->interfaces) : \count($intersect) > 0;
        });
    }
}
