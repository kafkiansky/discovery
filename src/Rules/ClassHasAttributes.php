<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Rules;

use Kafkiansky\Discovery\DiscoveryRule;

final class ClassHasAttributes implements DiscoveryRule
{
    /**
     * @var class-string[]
     */
    private readonly array $attributes;

    /**
     * @param class-string ...$attributes
     */
    public function __construct(string ...$attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function satisfy(string $fqcn): bool
    {
        $r = new \ReflectionClass($fqcn);

        foreach ($this->attributes as $attribute) {
            if (0 < \count($r->getAttributes($attribute))) {
                return true;
            }
        }

        return false;
    }
}
