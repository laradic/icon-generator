<!---
title: Icon Generator 
author: Robin Radic
-->
Laradic Icon Generator
====================

[![Source](http://img.shields.io/badge/source-laradic/icon--generator-blue.svg?style=flat-square)](https://github.com/laradic/icon-generator)
[![License](http://img.shields.io/badge/license-MIT-green.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)
![Framework](http://img.shields.io/badge/framework-any-brightgreen.svg?style=flat-square)

Icon Generator can create PNG images from several icon fonts like FontAwesome, Foundation Icons, etc.
For example, useful for **favicon generation**. 

The package follows the FIG standards PSR-1, PSR-2, and PSR-4 to ensure a high level of interoperability between shared PHP code.

Installation
------------

```bash
composer require "laradic/icon-generator:~1.0"
```

Quick Overview
-------------
**Full documenation** @ [la.radic.nl](https://la.radic.nl)

#### Using the default generators
```php
require __DIR__ . '/vendor/autoload.php';

use Laradic\IconGenerator\Factory;
use Laradic\IconGenerator\IconGenerator;

$icons = new Factory;
$icons->addDefaultFonts();
$generator = $icons->createGenerator('font-awesome');
$generator->setIcons('android', 'car', 'html5', 'github');

// 16x16px, 32x32px, etc
$generator->setSizes(16, 32, 64, 128);

// add some material colors
$generator->addColor('#ef5350'); // red 400
$generator->addColor('#42A5F5'); // blue 400
$generator->addColor('#424242'); // grey 800
$generator->addColor(251, 94, 11); // RGB Also supported

$generator->setOutDir(__DIR__ . '/../generated');

$generator->generate($prefix);

$generator->generate();
```


#### Using the default generators
```php
require __DIR__ . '/vendor/autoload.php';

use Laradic\IconGenerator\Factory;
use Laradic\IconGenerator\IconGenerator;

$icons = new Factory;
$icons->addDefaultFonts();
$generate = function (IconGenerator $generator, $prefix = '') {

    // 16x16px, 32x32px, etc
    $generator->setSizes(16, 32, 64, 128);

    // add some material colors
    $generator->addColor('#ef5350'); // red 400
    $generator->addColor('#42A5F5'); // blue 400
    $generator->addColor('#424242'); // grey 800

    $generator->setOutDir(__DIR__ . '/../generated');

    $generator->generate($prefix);
};


$fa = $icons->createGenerator('font-awesome');
$fa->setIcons('android', 'car', 'html5', 'github');
$generate($fa, 'fa-');


$found = $icons->createGenerator('foundation-general');
$found->setIcons([ 'checkmark', 'remove', 'mail', 'calendar' ]);
$generate($found, 'found-');
```


