<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\CodeLocation;

interface LoaderConstraint
{
    /**
     * @param non-empty-string $fqcn
     *
     * @throws \Throwable
     */
    public function shouldLoad(string $fqcn): bool;
}
