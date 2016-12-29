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
        $font = $this->getFontAwesomeMock($fontName = 'font-awesome');
        $factory->addFont($font);
        $this->assertInstanceOf('Laradic\IconGenerator\Font', $factory->getFont($fontName));
        $gen = $factory->createGenerator($fontName);
        $gen->addColor('#666')
            ->setIcons('car')
            ->setSizes(32)
            ->setOutDir($this->getTempPath())
            ->generate();
    }

    public function test_can_create_generator_for_font()
    {
        $factory = new Factory();
        $factory->addFont($fontName = 'font-awesome', $font = \Mockery::mock('Laradic\IconGenerator\Font'));
        $generator = $factory->createGenerator($fontName);
        $this->assertInstanceOf('Laradic\\IconGenerator\\IconGenerator', $generator);
        $this->assertInstanceOf('Laradic\IconGenerator\Font', $generator->getFont());
    }

    public function test_can_generate_icons()
    {
        $factory = new Factory();
        $factory->addFont($this->getFontAwesomeMock($fontName = 'font-awesome'));
        $generated = $factory->generate('font-awesome', 'book', 15, '#666', $this->getTempPath());
        $this->assertFileExists($generated[0]);
    }
}
