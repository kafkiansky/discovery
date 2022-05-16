<?php

declare(strict_types=1);

namespace Kafkiansky\Discovery\Tests\Cache;

use Kafkiansky\Discovery\Cache\NameUsingRuleHash;
use Kafkiansky\Discovery\Rules\ClassExtends;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class NameUsingRuleHashTest extends TestCase
{
    public function testIdGeneratedUsingRuleHash(): void
    {
        $rule = new ClassExtends(\Exception::class);

        self::assertEquals(
            \sha1(\serialize($rule)),
            (new NameUsingRuleHash())($rule)
        );
    }
}
