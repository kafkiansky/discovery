<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Tests\Stubs;

final class A extends B implements C, \Stringable
{
    use E;

    public function __toString(): string
    {
        return $this::class;
    }
}
