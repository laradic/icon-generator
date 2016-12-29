<!---
title: Icon Generator 
author: Robin Radic
-->
Laradic Icon Generator
====================

[![Source](http://img.shields.io/badge/source-laradic/icon--generator-blue.svg?style=flat-square)](https://github.com/laradic/icon-generator)
[![License](http://img.shields.io/badge/license-MIT-green.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)
![Framework](http://img.shields.io/badge/framework-any-brightgreen.svg?style=flat-square)

Laradic Icon Generator can create PNG images from several icon fonts like FontAwesome, Foundation Icons, etc.
For example, useful for **favicon generation**.

This package does **not** require Laravel but it does provide a `ServiceProvider`, `Facade` access and a `Console` command.

The package follows the FIG standards PSR-1, PSR-2, and PSR-4 to ensure a high level of interoperability between shared PHP code.

Installation
------------

```bash
composer require "laradic/icon-generator:~1.0"
```

Quick Overview
--------------


**Full documenation** @ [la.radic.nl](https://la.radic.nl)

### Using the API
The factory is the place where you can register custom fonts. Laradic Icon Generator comes packed
with [Font Awesome](http://fontawesome.io) and [Foundation Icons](http://zurb.com/playground/foundation-icons).

When the font you want to use has been added to the factory you can create a `IconGenerator` for it which is responsible for generating the images.

```php
/** @var \Laradic\IconGenerator\IconGenerator $generator */
$generator = (new \Laradic\IconGenerator\Factory)
                ->addDefaultFonts()
                ->createGenerator('font-awesome');

$generator->setIcons('android', 'car')
            ->setSizes(16, 32, 64, 128)
            ->addColor('#42A5F5') // blue 400
            ->addColor('#424242') // grey 800
            ->addColor(251, 94, 11) // RGB Also supported
            ->setOutDir(__DIR__ . '/generated-icons')
            ->generate();
```

There's also a **shorthand** method available `\Laradic\IconGenerator\Factory::generate($font, array $icons, array $sizes, array $colors, $outDir = null)`
```php
(new Factory())
    ->addDefaultFonts()
    ->generate($font, array $icons, array $sizes, array $colors, $outDir = null);
```

### Laravel
When using the Laravel Service Provider, the factory is bound as singleton in the container.
So the example above can also be written like this.

#### Binding
```php
$generator = app('laradic.icon-generator')
                ->addDefaultFonts()
                ->createGenerator('font-awesome');
// other code remains the same
```

#### Facade
Optionally, you could add the facade and use that for accessing the factory.
```php
$generator = IconGenerator::addDefaultFonts()->createGenerator('font-awesome');
```

#### Command
Another way to create fonts is using the command.
```bash
Usage:
  laradic:icon:generate [options] [--] <font> [<outDir>]
  laradic:icon:generate font-awesome ./ -i car -i book -s 16 -s 32 -s 128 -c 424242 -c 42A5F5

Arguments:
  font                  The icon font you would like to use
  outDir                Path to the directory the icons should be generated in. [default: "resources/assets/icons"]

Options:
  -i, --icons=ICONS     Icons to generate. One or more icon names (multiple values allowed)
  -s, --sizes=SIZES     The sizes to generate. One or more numeric values (multiple values allowed)
  -c, --colors=COLORS   The colors to generate. Either RGB or HEX (multiple values allowed)
  -l, --list            List all available fonts
```