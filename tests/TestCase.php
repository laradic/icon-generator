<?php
namespace Laradic\Tests\IconGenerator;
define('DS', DIRECTORY_SEPARATOR);

use Laradic\IconGenerator\Factory;
use Laradic\Testing\Native\AbstractTestCase;

abstract class TestCase extends AbstractTestCase
{
    protected $fonts = [
        'font-aweswome'
    ];

    protected function setUp()
    {
        parent::setUp();

        $groups = [ 'accessibility', 'general_enclosed', 'general', 'social' ];
        foreach ( $groups as $group ) {
            $this->fonts[ 'foundation' ] = __DIR__ . "/../resources/foundation/{$group}_foundicons.ttf";
        }
    }



}