<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery;

/**
 * @param non-empty-string $basePath
 * @param non-empty-string ...$parts
 *
 * @throws FileNotFound
 *
 * @return non-empty-string
 */
function withPath(string $basePath, string ...$parts): string
{
    $path = '/'.\trim($basePath, '/');

    foreach ($parts as $part) {
        $path .= "/$part";
    }

    if (\file_exists($path) === false) {
        throw new FileNotFound(\sprintf('No file was found at path "%s".', $path));
    }

    return $path;
}

/**
 * @template T
 *
 * @param callable(): T $function
 *
 * @throws \ErrorException
 *
 * @return T|false
 */
function withErrorHandling(callable $function): mixed
{
    \set_error_handler(static function (int $errorNo, string $errorStr, ?string $errorFile, ?int $errorLine): bool {
        if (0 === \error_reporting()) {
            return false;
        }

        throw new \ErrorException($errorStr, 0, $errorNo, $errorFile, $errorLine);
    });

    try {
        /** @var T|false */
        return $function();
    } finally {
        \restore_error_handler();
    }
}
