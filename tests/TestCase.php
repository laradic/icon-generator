<?php
namespace Laradic\Tests\IconGenerator;

define('DS', DIRECTORY_SEPARATOR);

use Laradic\Filesystem\Filesystem;
use Laradic\IconGenerator\Factory;
use Laradic\Testing\Native\AbstractTestCase;

abstract class TestCase extends AbstractTestCase
{
    protected $fonts = [
        'font-aweswome',
    ];

    protected function setUp()
    {
        parent::setUp();

        $groups = [ 'accessibility', 'general_enclosed', 'general', 'social' ];
        foreach ( $groups as $group ) {
            $this->fonts[ 'foundation' ] = __DIR__ . "/../resources/foundation/{$group}_foundicons.ttf";
        }

        Filesystem::create()->ensureDirectory($this->getTempPath(), true, 0777);
    }

    protected function getTempPath()
    {
        return __DIR__ . '/tmp';
    }

    protected function tearDown()
    {

        if ( is_dir($this->getTempPath()) ) {
            Filesystem::create()->deleteDirectory($this->getTempPath());
        }
        parent::tearDown();
    }

    protected function getFontAwesomeMock($fontName = 'font-awesome')
    {
        $font = \Mockery::mock('Laradic\IconGenerator\Font');
        $font->shouldReceive('getName')->zeroOrMoreTimes()->andReturnValues([$fontName]);
        $font->shouldReceive('getIconData')->zeroOrMoreTimes()->andReturnValues([require __DIR__ . '/font-awesome-icon-data.php']);
        $font->shouldReceive('getPath')->zeroOrMoreTimes()->andReturnValues([__DIR__ . '/../resources/fonts/font-awesome/fontawesome-webfont.ttf']);
        return $font;
    }


}