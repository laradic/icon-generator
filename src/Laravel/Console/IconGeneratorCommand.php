<?php
namespace Laradic\IconGenerator\Laravel\Console;

use Illuminate\Console\Command;

class IconGeneratorCommand extends Command
{
    protected $signature = 'laradic:icon:generate {--list}';

    protected $description = 'Generate icons';

    /**
     * @var \Laradic\IconGenerator\Factory|mixed
     */
    protected $generator;

    /**
     * IconGeneratorCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->generator = $this->getLaravel()->make('laradic.icon-generator');
    }


    public function fire()
    {
        $this->warn('You should extend this command to register more fonts!');
        $fonts = $this->generator->fonts();
        $font = $this->choice('Which font should i use?', $fonts);
    }
}
