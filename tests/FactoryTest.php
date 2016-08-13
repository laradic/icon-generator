<?php
namespace Laradic\Tests\IconGenerator;

use Laradic\IconGenerator\Factory;
use Laradic\IconGenerator\Font;

class FactoryTest extends TestCase
{
    public function test_is_instantiatable()
    {
        $factory = new Factory();
        static::assertInstanceOf(Factory::class, $factory);
    }

    public function test_can_add_remove_fonts()
    {
        $factory = new Factory();
        $factory->addFont($font = new Font('test-font'));
        $factory->hasFont('test-font');
    }

    public function test_allow_name_or_instance()
    {
        $factory = new Factory();
        $factory->addFont($font = new Font('test-font'));
        static::assertTrue($factory->hasFont($font));
        static::assertTrue($factory->hasFont('test-font'));
        $factory->removeFont($font);
        $factory->removeFont('test-font');
        static::assertFalse($factory->hasFont('test-font'));
    }
}
