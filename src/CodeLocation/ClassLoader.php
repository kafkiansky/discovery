<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\CodeLocation;

interface ClassLoader
{
    /**
     * @throws \Throwable
     *
     * @return \Traversable<class-string>
     */
    public function load(): \Traversable;
}
