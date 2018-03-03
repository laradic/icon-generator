<?php
/**
 * Part of the Laradic PHP Packages.
 *
 * Copyright (c) 2018. Robin Radic.
 *
 * The license can be found in the package and online at https://laradic.mit-license.org.
 *
 * @copyright Copyright 2018 (c) Robin Radic
 * @license https://laradic.mit-license.org The MIT License
 */

namespace Laradic\IconGenerator\Laravel\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class IconGeneratorCommand extends Command
{
    protected $signature = 'laradic:icon 
                            {font : The icon font you would like to use}
                            {outDir=resources/assets/icons : Path to the directory the icons should be generated in. } ';

    protected $description = 'Generate icons';

    private $generators;

    public function handle()
    {
        $font = $this->argument('font');
        if (false === $this->getFactory()->hasFont($font)) {
            return $this->error("Could not find the font [{$font}]");
        }

        $o = [];
        foreach ([ 'icons', 'sizes', 'colors' ] as $key) {
            $o[ $key ] = $this->option($key);
        }

        $outDir = $this->getOutDir();

        $generator = $this->getGenerator($font)
            ->setIcons($o[ 'icons' ])
            ->setSizes($o[ 'sizes' ])// @todo need to fix rgb handling, won't work this way
            ->setOutDir($outDir);

        foreach ($o[ 'colors' ] as $color) {
            $generator->addColor($color);
        }
        $filePaths = $generator->generate();

        $html = '';
        $styl = 'list-style-icons: {';
        foreach ($filePaths as $filePath) {
            if (file_exists($filePath)) {
                $image       = file_get_contents($filePath);
                $imageData   = base64_encode($image);
                $imageName = path_get_filename_without_extension($filePath);
                $html .= "<img alt='{$imageName}' src='data:image/png;base64,{$imageData}' />{$imageName}<br>\n";
                $matches = [];
                $nameSegments = preg_match("/(.*)-(\d\d)x\d\d-([\w\d]{6})/", $imageName, $matches);
                $styl .= "";
            }
        }
        file_put(path_join($outDir, 'index.html'), "<html><body>{$html}</body></html>");

        $this->info('Generated ' . count($filePaths) . ' images');
        $this->info('Generated index.html preview file');
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
        if ( ! isset($this->generators)) {
            $this->generators = [];
            return $this->getGenerator($fontName);
        } elseif ( ! isset($this->generators[ $fontName ])) {
            $this->generators[ $fontName ] = $this->getFactory()->createGenerator($fontName);
        }
        return $this->generators[ $fontName ];
    }

    protected function getOutDir()
    {
        $outDir = $this->argument('outDir');
        if ( ! $outDir) {
            $outDir = getcwd();
            $this->error('???path');
            exit;
        } else {
            if (path_is_relative($outDir)) {
                $outDir = path_join(getcwd(), $outDir);
            } else {
            }
        }

        if ($outDir === app()->basePath() && false === $this->confirm('Are you use you want to generate the icons in the root project folder?', false)) {
            $this->error('Error in generate path');
            exit;
        }

        return $outDir;
    }


}
