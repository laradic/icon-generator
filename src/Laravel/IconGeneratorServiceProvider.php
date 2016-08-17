<?php

namespace Laradic\IconGenerator\Laravel;


use Laradic\ServiceProvider\ServiceProvider;
use Laradic\IconGenerator\Factory;

class IconGeneratorServiceProvider extends ServiceProvider
{
    protected $scanDirs = true;

    protected $findCommands = [ 'Console' ];

    public function register()
    {
        parent::register();
        $this->app->singleton('laradic.icon-generator', function ($app) {
            return new Factory();
        });
    }
}
