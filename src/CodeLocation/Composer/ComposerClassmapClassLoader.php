<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\CodeLocation\Composer;

use Kafkiansky\Discovery\CodeLocation\ClassLoader;
use Kafkiansky\Discovery\CodeLocation\LoaderConstraint;
use function Kafkiansky\Discovery\withPath;

final class ComposerClassmapClassLoader implements ClassLoader
{
    /**
     * @var LoaderConstraint[]
     */
    private array $loaderConstraints;

    /**
     * @var callable(non-empty-string):array<class-string, non-empty-string>
     */
    private $classMapLoader;

    /**
     * @param non-empty-string $appPath
     */
    public function __construct(
        private string $appPath,
        LoaderConstraint ...$constraints,
    ) {
        $this->loaderConstraints = $constraints;
        $this->classMapLoader = static function (string $appPath): array {
            /** @var non-empty-string */
            $path = $appPath;

            /**
             * @psalm-suppress UnresolvableInclude
             * @var array<class-string, non-empty-string>
             */
            return require withPath($path, 'vendor/composer/autoload_classmap.php');
        };
    }

    /**
     * @param callable(non-empty-string):array<class-string, non-empty-string> $classMapLoader
     */
    public function withClassMapLoader(callable $classMapLoader): ComposerClassmapClassLoader
    {
        $self = clone $this;
        $self->classMapLoader = $classMapLoader;

        return $self;
    }

    /**
     * {@inheritdoc}
     */
    public function load(): \Traversable
    {
        $classmap = ($this->classMapLoader)($this->appPath);

        $loaderConstraints = $this->loaderConstraints;

        if (\count($loaderConstraints) === 0) {
            $loaderConstraints = [
                new class implements LoaderConstraint {
                    /**
                     * {@inheritdoc}
                     */
                    public function shouldLoad(string $fqcn): bool
                    {
                        return true;
                    }
                }
            ];
        }

        foreach ($classmap as $fqcn => $_location) {
            foreach ($loaderConstraints as $loaderConstraint) {
                if ($loaderConstraint->shouldLoad($fqcn)) {
                    yield $fqcn;

                    continue 2;
                }
            }
        }
    }
}
