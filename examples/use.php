<?php
/**
 * Part of the Radic PHP packages.
 *
 * License and copyright information bundled with this package in the LICENSE file
 */
require __DIR__ . '/../../../../vendor/autoload.php';

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




