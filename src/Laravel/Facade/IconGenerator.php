<?php
namespace Laradic\IconGenerator\Laravel\Facade;

use Illuminate\Support\Facades\Facade;

class IconGenerator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laradic.icon-generator';
    }

}
