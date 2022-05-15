# PHP Discovery

![test](https://github.com/kafkiansky/discovery/workflows/test/badge.svg?event=push)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/kafkiansky/discovery.svg?style=flat-square)](https://packagist.org/packages/kafkiansky/discovery)

### Contents

- [Installation](#installation)
- [Usage](#usage)
  - [ClassImplements](#class-implements)
  - [ClassExtends](#class-extends)
  - [ClassUses](#class-uses)
  - [All](#all)
  - [Any](#any)
  - [None](#none)
- [Loader constraints](#loader-constraints)
- [Composer loader](#composer-loader)
- [Array loader](#array-loader)
- [Testing](#testing)
- [License](#license)

## Installation

```bash
composer require kafkiansky/discovery
```

## Usage

It is often necessary to find all classes that implement specific interface(s), inherit a specific class or use specific traits.
This information could be used, in example, to implement autowiring in IOC containers. To avoid parsing the entire project, you could use classes information from the `autoload_classmap.php` file that the `composer` uses for [optimization](https://getcomposer.org/doc/articles/autoloader-optimization.md).

### Class Implements

Discover all classes that implement specific interface(s):

```php
<?php

use Kafkiansky\Discovery\Discovery;
use Kafkiansky\Discovery\CodeLocation\Composer\ComposerClassmapClassLoader;
use Kafkiansky\Discovery\Rules\ClassImplements;

$discovery = new Discovery(new ComposerClassmapClassLoader(__DIR__));

var_dump($discovery->discover(new ClassImplements(\Stringable::class))); // discover all interfaces that implement the Stringable interface.
```

Discover all classes that implement all of specific interfaces:

```php
<?php

use Kafkiansky\Discovery\Discovery;
use Kafkiansky\Discovery\CodeLocation\Composer\ComposerClassmapClassLoader;
use Kafkiansky\Discovery\Rules\ClassImplements;

$discovery = new Discovery(new ComposerClassmapClassLoader(__DIR__));

var_dump($discovery->discover(new ClassImplements([\Stringable::class, \ArrayAccess::class])));
```

Discover all classes that implement one of specific interfaces:

```php
<?php

use Kafkiansky\Discovery\Discovery;
use Kafkiansky\Discovery\CodeLocation\Composer\ComposerClassmapClassLoader;
use Kafkiansky\Discovery\Rules\ClassImplements;

$discovery = new Discovery(new ComposerClassmapClassLoader(__DIR__));

var_dump($discovery->discover(new ClassImplements(interfaces: [\Stringable::class, \ArrayAccess::class], implementsAll: false)));
```

### Class Extends

Discover all classes that extend specific class:

```php
<?php

use Kafkiansky\Discovery\Discovery;
use Kafkiansky\Discovery\CodeLocation\Composer\ComposerClassmapClassLoader;
use Kafkiansky\Discovery\Rules\ClassExtends;

$discovery = new Discovery(new ComposerClassmapClassLoader(__DIR__));

var_dump($discovery->discover(new ClassExtends(\Exception::class)));
```

### Class Uses

Discover all classes that use specific trait. This rule recursively searches for the desired trait among the parent traits and among the traits of traits:

```php
<?php

use Kafkiansky\Discovery\Discovery;
use Kafkiansky\Discovery\CodeLocation\Composer\ComposerClassmapClassLoader;
use Kafkiansky\Discovery\Rules\ClassUses;

$discovery = new Discovery(new ComposerClassmapClassLoader(__DIR__));

var_dump($discovery->discover(new ClassUses(SomeTrait::class)));
```

### All

Discover all classes that satisfy list of rules:

```php
<?php

use Kafkiansky\Discovery\Discovery;
use Kafkiansky\Discovery\CodeLocation\Composer\ComposerClassmapClassLoader;
use Kafkiansky\Discovery\Rules\All;
use Kafkiansky\Discovery\Rules\ClassExtends;
use Kafkiansky\Discovery\Rules\ClassImplements;

$discovery = new Discovery(new ComposerClassmapClassLoader(__DIR__));

var_dump($discovery->discover(
    new All(new ClassImplements(\Stringable::class), new ClassExtends(\Exception::class))
));
```

### Any

Discover all classes that satisfy at least one rule from list:

```php
<?php

use Kafkiansky\Discovery\Discovery;
use Kafkiansky\Discovery\CodeLocation\Composer\ComposerClassmapClassLoader;
use Kafkiansky\Discovery\Rules\Any;
use Kafkiansky\Discovery\Rules\ClassExtends;
use Kafkiansky\Discovery\Rules\ClassImplements;

$discovery = new Discovery(new ComposerClassmapClassLoader(__DIR__));

var_dump($discovery->discover(
    new Any(new ClassImplements(\Stringable::class), new ClassExtends(\Exception::class))
));
```

### None

Discover all classes that do not satisfy any rule:

```php
<?php

use Kafkiansky\Discovery\Discovery;
use Kafkiansky\Discovery\CodeLocation\Composer\ComposerClassmapClassLoader;
use Kafkiansky\Discovery\Rules\None;
use Kafkiansky\Discovery\Rules\ClassExtends;
use Kafkiansky\Discovery\Rules\ClassImplements;

$discovery = new Discovery(new ComposerClassmapClassLoader(__DIR__));

var_dump($discovery->discover(
    new None(new ClassImplements(\Stringable::class), new ClassExtends(\Exception::class))
));
```

## Loader constraints

If you want to specify which classes can be loaded, you can use the `LoaderConstraint` interface implementation.
Out of the box comes the `Kafkiansky\Discovery\CodeLocation\Composer\LoadOnlyApplicationCode` class that load only classes that matches namespaces from `autoload.psr4` section of your `composer.json` file.

```php
<?php

use Kafkiansky\Discovery\Discovery;
use Kafkiansky\Discovery\CodeLocation\Composer\ComposerClassmapClassLoader;
use Kafkiansky\Discovery\Rules\ClassImplements;
use Kafkiansky\Discovery\CodeLocation\Composer\LoadOnlyApplicationCode;

$applicationRoot = __DIR__;

$discovery = new Discovery(
    new ComposerClassmapClassLoader($applicationRoot, new LoadOnlyApplicationCode($applicationRoot))
);

var_dump($discovery->discover(new ClassImplements(\Stringable::class)));
```

## Composer loader

By default, the `ComposerClassmapClassLoader` search the `autoload_classmap.php` in `vendor/composer/autoload_classmap.php` path and require it.
If you want pass your own classmap loader, overload the default loader using `withClassMapLoader` method:

```php
<?php

use Kafkiansky\Discovery\Discovery;
use Kafkiansky\Discovery\CodeLocation\Composer\ComposerClassmapClassLoader;
use Kafkiansky\Discovery\Rules\ClassExtends;
use Kafkiansky\Discovery\CodeLocation\Composer\LoadOnlyApplicationCode;
use Kafkiansky\Discovery\FileNotFound;

$applicationRoot = __DIR__;

$loader = new ComposerClassmapClassLoader($applicationRoot);
$loader = $loader->withClassMapLoader(function (string $path): array {
    return [FileNotFound::class => $path . '/src/FileNotFound.php'];
});

$discovery = new Discovery($loader);

var_dump($discovery->discover(new ClassExtends(\Exception::class)));
```

## Array loader

For testing purposes you may use the `Kafkiansky\Discovery\CodeLocation\ArrayClassLoader`:

```php
<?php

use Kafkiansky\Discovery\Discovery;
use Kafkiansky\Discovery\CodeLocation\Composer\ComposerClassmapClassLoader;
use Kafkiansky\Discovery\Rules\ClassExtends;
use Kafkiansky\Discovery\CodeLocation\ArrayClassLoader;
use Kafkiansky\Discovery\FileNotFound;

$discovery = new Discovery(new ArrayClassLoader([
    FileNotFound::class,
]));

var_dump($discovery->discover(new ClassExtends(\Exception::class)));
```

## Testing

``` bash
$ composer test
```  

## License

The MIT License (MIT). See [License File](LICENSE) for more information.