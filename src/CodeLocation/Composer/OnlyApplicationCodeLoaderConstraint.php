<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\CodeLocation\Composer;

use Kafkiansky\Discovery\CodeLocation\LoaderConstraint;
use Kafkiansky\Discovery\FileNotFound;
use Kafkiansky\Discovery\InvalidJsonSchema;
use function Kafkiansky\Discovery\withPath;

final class OnlyApplicationCodeLoaderConstraint implements LoaderConstraint
{
    /**
     * @param non-empty-string $appPath
     */
    public function __construct(private string $appPath)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function shouldLoad(string $fqcn): bool
    {
        /** @var string[] */
        static $allowedNamespaces = [];

        if (\count($allowedNamespaces) === 0) {
            $allowedNamespaces = $this->allowedNamespaces();
        }

        foreach ($allowedNamespaces as $allowedNamespace) {
            if (\str_starts_with($fqcn, $allowedNamespace)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws FileNotFound
     * @throws InvalidJsonSchema
     *
     * @return non-empty-string[]
     */
    private function allowedNamespaces(): array
    {
        $composerJsonPath = withPath($this->appPath, 'composer.json');

        /** @var array{autoload?: array{psr-4?: array<non-empty-string, non-empty-string>}} */
        $schema = \json_decode(\file_get_contents($composerJsonPath), true);

        if (\json_last_error() !== \JSON_ERROR_NONE) {
            throw new InvalidJsonSchema(\sprintf('Invalid composer.json schema: %s.', \json_last_error_msg()));
        }

        if (isset($schema['autoload']['psr-4']) === false) {
            throw new InvalidJsonSchema('No autoload/psr-4 section was found in composer.json.');
        }

        return \array_keys($schema['autoload']['psr-4']);
    }
}
