<?php
namespace Laradic\IconGenerator;


/**
 * This is the class Factory.
 *
 * @package        Laradic\IconGenerator
 * @author         Radic
 * @copyright      Copyright (c) 2015, Radic. All rights reserved
 */
class Factory
{
    /**
     * fonts method
     *
     * @var Font[]
     */
    protected $fonts = [ ];

    /**
     * generatorClass method
     *
     * @var
     */
    protected $generatorClass = IconGenerator::class;

    /**
     * remove method
     *
     * @param string|Font $font The name of the font or instance
     */
    public function removeFont($font)
    {
        unset($this->fonts[ (string)$font ]);
    }

    /**
     * resolveFontName method
     *
     * @param $font
     *
     * @return mixed
     */
    protected function resolveFontName($font)
    {
        return $font instanceof Font ? $font->getName() : $font;
    }

    /**
     * Add a font by instance or create a new font instance and add it. Also use the second parameter for the later.
     *
     * @param string|\Laradic\IconGenerator\Font $font Either the font name if you want the factory to create the Font instance, or a Font instance
     * @param string|null                        $path If you let the factory create the Font instance, you need to specify the absolute path to the .ttf font file
     *
     * @return \Laradic\IconGenerator\Font
     */
    public function addFont($font, $path = null)
    {
        if ( !$font instanceof Font ) {
            if ( null === $path ) {
                throw new \InvalidArgumentException('The $path parameter has to be set when not providing a Font instance');
            }
            $font = new Font($font, $path);
        }
        $this->fonts[ $font->getName() ] = $font;

        return $font;
    }

    /**
     * hasFont method
     *
     * @param $font
     *
     * @return bool
     */
    public function hasFont($font)
    {
        return array_key_exists((string)$font, $this->fonts);
    }

    /**
     * Get all font names
     *
     * @return array
     */
    public function fonts()
    {
        return array_keys($this->fonts);
    }

    /**
     * getFont method
     *
     * @param $font
     *
     * @return \Laradic\IconGenerator\Font
     */
    public function getFont($font)
    {
        if ( false === $this->hasFont($font) ) {
            throw new \InvalidArgumentException("Font [{$font}] could not be found");
        }

        return $this->fonts[ $font ];
    }

    /**
     * createGenerator method
     *
     * @param $font
     *
     * @return IconGenerator
     */
    public function createGenerator($font)
    {
        return new $this->generatorClass($this, $this->getFont($font));
    }

    /**
     * Set the generatorClass value
     *
     * @param mixed $generatorClass
     *
     * @return Factory
     */
    public function setGeneratorClass($generatorClass)
    {
        $this->generatorClass = $generatorClass;
        return $this;
    }

    public function reset()
    {
        $this->fonts          = [ ];
        $this->generatorClass = IconGenerator::class;
    }

    /**
     * addDefaultFonts method
     */
    public function addDefaultFonts()
    {
        $this->addDefaultFontAwesomeFont();
        $this->addDefaultFoundationIconsFont();
    }

    /**
     * addDefaultFontAwesomeFont method
     */
    protected function addDefaultFontAwesomeFont()
    {
        $font = $this->addFont('font-awesome', __DIR__ . '/../resources/font-awesome/fontawesome-webfont.ttf');
        $font->setDataExtractor(function () {
            $vars = file_get(__DIR__ . '/../resources/font-awesome/_variables.scss');
            preg_match_all('/\$fa-var-(.*?):\s"\\\(.*?)\";/', $vars, $matches);
            return collect($matches[ 2 ])->transform(function ($item) {
                return "&#x{$item};";
            })->combine($matches[ 1 ])->flip()->toArray();
        });
    }

    /**
     * addDefaultFoundationIconsFont method
     */
    protected function addDefaultFoundationIconsFont()
    {
        $groups = [ 'accessibility', 'general_enclosed', 'general', 'social' ];
        foreach ( $groups as $group ) {


            $font = $this->addFont("foundation-{$group}", __DIR__ . "/../resources/foundation/{$group}_foundicons.ttf");
            $font->setDataExtractor(function () use ($group) {
                $vars = file_get(__DIR__ . "/../resources/foundation/{$group}_foundicons.scss");
                preg_match_all('/@include i-class\((.*?),"(.*?)"\);/', $vars, $matches);
                return collect($matches[ 2 ])->transform(function ($item) {
                    return "&#xf{$item};";
                })->combine($matches[ 1 ])->flip()->toArray();
            });
        }
    }


}
