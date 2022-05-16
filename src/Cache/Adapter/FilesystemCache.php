<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Cache\Adapter;

use Kafkiansky\Discovery\Cache\CacheStorage;

final class FilesystemCache implements CacheStorage
{
    private const EXTENSION = '.discovery.cache';

    /**
     * @var callable(): bool
     */
    private $cacheIf;

    /**
     * @param non-empty-string $directory
     * @param non-empty-string $extension
     * @psalm-param (callable():bool)|null $cacheIf
     */
    public function __construct(
        private string $directory,
        private string $extension = self::EXTENSION,
        ?callable $cacheIf = null
    ) {
        $this->cacheIf = $cacheIf ?: fn (): bool => true;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $id): array|false
    {
        $filename = $this->getFilename($id);

        if (\file_exists($filename) === false) {
            return false;
        }

        $data = \file_get_contents($filename);

        if ($data === false) {
            return false;
        }

        /** @var class-string[]|false */
        $classes = \unserialize($data);

        if ($classes === false) {
            return false;
        }

        return $classes;
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $id, array $classes): void
    {
        if (($this->cacheIf)()) {
            $filename = $this->getFilename($id);

            [$data, $filepath] = [\serialize($classes), \pathinfo($filename, \PATHINFO_DIRNAME)];

            if (\is_dir($filepath) === false) {
                \mkdir($filepath, 0777, true);
            }

            \file_put_contents($filename, $data);
        }
    }

    public function flush(): void
    {
        $iterator = new \RecursiveDirectoryIterator($this->directory);
        $iterator = new \RecursiveIteratorIterator($iterator);
        $iterator = new \RegexIterator($iterator, '/^.+\\' . $this->extension . '$/i');

        /** @var string $name */
        foreach ($iterator as $name => $_file) {
            @unlink($name);
        }
    }

    /**
     * @param non-empty-string $id
     *
     * @return non-empty-string
     */
    private function getFilename(string $id): string
    {
        return $this->directory . \DIRECTORY_SEPARATOR . $id . $this->extension;
    }
}
