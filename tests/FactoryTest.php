<?php
namespace Laradic\Tests\IconGenerator;

use Laradic\IconGenerator\Factory;
use Laradic\IconGenerator\Font;
use Laradic\IconGenerator\IconGenerator;

class FactoryTest extends TestCase
{
    public function test_is_instantiatable()
    {
        $factory = new Factory();
        static::assertInstanceOf(Factory::class, $factory);
    }

    public function test_can_manage_fonts()
    {
        $factory = new Factory();
        $fontName = 'font-awesome';
        $font = \Mockery::mock('Laradic\IconGenerator\Font');
        $font->shouldReceive('getName')->once()->andReturnValues([$fontName]);
        $font->shouldReceive('getIconData')->twice()->andReturnValues([require __DIR__ . '/font-awesome-icon-data.php']);
        $factory->addFont($font);
        $this->assertInstanceOf('Laradic\IconGenerator\Font', $factory->getFont($fontName));

        $gen = $factory->createGenerator($fontName);
        $gen->addColor('#666')->setIcons('car')->setSizes(32)->setOutDir(getcwd())->generate();
    }

    public function test_can_create_generator_for_font()
    {
        $factory = new Factory();
        $factory->addFont($fontName = 'font-awesome', $font = \Mockery::mock('Laradic\IconGenerator\Font'));
        $generator = $factory->createGenerator($fontName);
        $this->assertInstanceOf('Laradic\\IconGenerator\\IconGenerator', $generator);

        // __DIR__ . '/../resources/font-awesome/fontawesome-webfont.ttf'
//        $factory->addFont(new Font'font-awesome2', __DIR__ . '/../resources/font-awesome/fontawesome-webfont.ttf');
//        static::assertTrue($factory->hasFont($fontName));
//        static::assertTrue($factory->hasFont($fontName));
//        $factory->removeFont($font);
//        $factory->removeFont($fontName);
//        static::assertFalse($factory->hasFont($fontName));
    }
}
