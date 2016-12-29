<?php
/**
 * Part of the Radic PHP packages.
 *
 * License and copyright information bundled with this package in the LICENSE file
 */
require __DIR__ . '/../../../vendor/autoload.php';

use Laradic\IconGenerator\Factory;
use Laradic\IconGenerator\IconGenerator;

$icons = new Factory;
$icons->addDefaultFonts();
$generate = function (IconGenerator $generator, $prefix = '') {

    // 16x16px, 32x32px, etc
    $generator->setSizes(250);

    // add colors
    $generator->addColor('#6C8EBF'); // red 400

    $generator->setOutDir(__DIR__ . '/generated-icons');

    $generator->generate($prefix);
};


$fa = $icons->createGenerator('font-awesome');
$fa->setIcons('search');
$generate($fa, 'fa-');


//$found = $icons->createGenerator('foundation-general');
//$found->setIcons([ 'checkmark', 'remove', 'mail', 'calendar' ]);
//$generate($found, 'found-');




