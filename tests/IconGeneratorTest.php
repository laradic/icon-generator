<?php
namespace Laradic\Tests\IconGenerator;

use Laradic\Filesystem\Filesystem;
use Laradic\IconGenerator\Factory;
use Laradic\IconGenerator\Font;
use Laradic\IconGenerator\IconGenerator;

class IconGeneratorTest extends TestCase
{
    public function test_is_instantiatable()
    {
        $factory = new Factory();
        static::assertInstanceOf(Factory::class, $factory);
    }

    protected function getGenerator($reset = true)
    {
        $factory = new Factory();
        $font    = $this->getFontAwesomeMock($fontName = 'font-awesome');
        $factory->addFont($font);
        $generator = $factory->createGenerator($fontName);
        $generator->setColors('#666', '#42A5F5', '#000', [0,255,255], [123,123,123])
            ->setIcons('car', 'book', 'puzzle-piece')
            ->setSizes(32, 60, 128)
            ->setOutDir($this->getTempPath());
        return $reset ? $generator->reset() : $generator;
    }

    public function test_can_generate_icon()
    {
        $generated = $this->getGenerator()
            ->addColor('#666')
            ->setIcons('car')
            ->setSizes(32)
            ->setOutDir($this->getTempPath())
            ->generate();

        $this->assertEquals($this->getTempPath() . DIRECTORY_SEPARATOR . 'car-32x32-666.png', $generated[ 0 ]);
        $this->assertFileExists($generated[ 0 ]);
    }

    public function test_can_generate_icons()
    {
        $generated = $this->getGenerator(false)->generate();

        foreach ( $generated as $g ) {
            $this->assertFileExists($g);
        }
    }
}
