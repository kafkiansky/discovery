<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Rules;

use Kafkiansky\Discovery\DiscoveryRule;

final class None implements DiscoveryRule
{
    /**
     * @var DiscoveryRule[]
     */
    private array $rules;

    public function __construct(DiscoveryRule ...$rules)
    {
        $this->rules = $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function satisfy(string $fqcn): bool
    {
        foreach ($this->rules as $rule) {
            if ($rule->satisfy($fqcn)) {
                return false;
            }
        }

        return true;
    }
}
