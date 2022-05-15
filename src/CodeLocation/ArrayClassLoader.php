<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\CodeLocation;

final class ArrayClassLoader implements ClassLoader
{
    /**
     * @param class-string[] $classes
     */
    public function __construct(private array $classes)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function load(): \Traversable
    {
        yield from $this->classes;
    }
}
