<?php

namespace Laradic\IconGenerator\Laravel;


use Laradic\ServiceProvider\ServiceProvider;
use Laradic\IconGenerator\Factory;

class IconGeneratorServiceProvider extends ServiceProvider
{
    protected $findCommands = [ 'Console' ];

    public function register()
    {
        parent::register();
        $this->app->singleton('laradic.icon-generator', function ($app) {
            $generator = new Factory();
            $generator->addDefaultFonts();
            return $generator;
        });
    }
}
