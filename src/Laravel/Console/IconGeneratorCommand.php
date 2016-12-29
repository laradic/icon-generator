<?php
namespace Laradic\IconGenerator\Laravel\Console;

use Laradic\Console\Command;
use Laradic\Support\Path;
use Symfony\Component\Console\Input\InputOption;

class IconGeneratorCommand extends Command
{
    protected $signature = 'laradic:icon:generate 
                            {font : The icon font you would like to use}
                            {outDir=resources/assets/icons : Path to the directory the icons should be generated in. } ';

    protected $description = 'Generate icons';

    private $generators;

    public function fire()
    {
        $font = $this->argument('font');
        if ( false === $this->getFactory()->hasFont($font) ) {
            return $this->error("Could not find the font [{$font}]");
        }

        $o = [];
        foreach ( [ 'icons', 'sizes', 'colors' ] as $key ) {
            $o[ $key ] = $this->option($key);
        }

        $generator = $this->getGenerator($font)
            ->setIcons($o[ 'icons' ])
            ->setSizes($o[ 'sizes' ]) // @todo need to fix rgb handling, won't work this way
            ->setOutDir($this->getOutDir());

        foreach ( $o[ 'colors' ] as $color ) {
            $generator->addColor($color);
        }

        $this->info('Done. Generated ' . $generator->generate() . ' images');
    }


    protected function configure()
    {
        $this->addUsage('font-awesome ./ -i car -i book -s 16 -s 32 -s 128 -c 424242 -c 42A5F5');
        parent::configure();
        $this->addOption('icons', 'i', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Icons to generate. One or more icon names');
        $this->addOption('sizes', 's', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'The sizes to generate. One or more numeric values');
        $this->addOption('colors', 'c', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'The colors to generate. Either RGB or HEX');
        $this->addOption('list', 'l', InputOption::VALUE_NONE, 'List all available fonts');
    }

    /** @return \Laradic\IconGenerator\Factory */
    protected function getFactory()
    {
        return app('laradic.icon-generator');
    }

    /**
     * createGenerator method
     *
     * @param $fontName
     *
     * @return \Laradic\IconGenerator\IconGenerator
     */
    protected function getGenerator($fontName)
    {
        if ( !isset($this->generators) ) {
            $this->generators = [];
            return $this->getGenerator($fontName);
        } elseif ( !isset($this->generators[ $fontName ]) ) {
            $this->generators[ $fontName ] = $this->getFactory()->createGenerator($fontName);
        }
        return $this->generators[ $fontName ];
    }

    protected function getOutDir()
    {
        $outDir = $this->argument('outDir');
        if ( !$outDir ) {
            $outDir = getcwd();
            $this->error('???path');
            exit;
        } else {
            if ( path_is_relative($outDir) ) {
                $outDir = path_join(getcwd(), $outDir);
            } else {
            }
        }

        if ( $outDir === app()->basePath() && false === $this->confirm('Are you use you want to generate the icons in the root project folder?', false) ) {
            $this->error('Error in generate path');
            exit;
        }

        return $outDir;
    }


}
