<?php
namespace Laradic\Tests\IconGenerator;

use Laradic\IconGenerator\Factory;
use Laradic\IconGenerator\Font;
use Laradic\IconGenerator\IconGenerator;

class FontTest extends TestCase
{
    public function test_is_instantiatable()
    {
        static::assertInstanceOf('Laradic\IconGenerator\Font', new Font('', ''));
    }

    public function test_data_extractor()
    {
        $font   = new Font('a', 'b');
        $called = false;
        $font->setDataExtractor(function ($fontInstance) use (&$called) {
            $called = true;
            $this->assertInstanceOf('Laradic\IconGenerator\Font', $fontInstance);
            return [ 'foo' => 'bar' ];
        });
        $this->assertEquals([ 'foo' => 'bar' ], $font->getIconData());
        $this->assertTrue($called);
    }

    public function test_constructor_params()
    {
        $font = new Font('a', 'b');
        $this->assertEquals('a', $font->getName());
        $this->assertEquals('b', $font->getPath());
    }
}
