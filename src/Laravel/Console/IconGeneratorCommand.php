<?php
namespace Laradic\IconGenerator\Laravel\Console;

use Illuminate\Console\Command;

class IconGeneratorCommand extends Command
{
    protected $signature = 'laradic:icon:generate {--list}';

    protected $description = 'Generate icons';

    public function fire()
    {
        $this->warn('You should extend this command to register more fonts!');
    }
}
