<?php

declare(strict_types=1);

use Kafkiansky\Discovery\CodeLocation\ArrayClassLoader;
use Kafkiansky\Discovery\CodeLocation\Composer\ComposerClassmapClassLoader;
use Kafkiansky\Discovery\CodeLocation\Composer\OnlyApplicationCodeLoaderConstraint;
use Kafkiansky\Discovery\Discovery;
use Kafkiansky\Discovery\DiscoveryRule;
use Kafkiansky\Discovery\Rules\All;
use Kafkiansky\Discovery\Rules\Any;
use Kafkiansky\Discovery\Rules\ClassImplements;
use Kafkiansky\Discovery\Rules\None;

require_once __DIR__.'/../vendor/autoload.php';

### Load from prepared classes
$discovery = new Discovery(new ArrayClassLoader([
    All::class,
    Any::class,
    None::class,
    Discovery::class,
    OnlyApplicationCodeLoaderConstraint::class,
]));

print_r($discovery->discover(new ClassImplements(DiscoveryRule::class)));

### Load from composer autoload classmap

$discovery = new Discovery(new ComposerClassmapClassLoader(__DIR__.'/../'));

print_r($discovery->discover(new ClassImplements(DiscoveryRule::class)));
