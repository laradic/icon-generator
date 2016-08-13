<?php
namespace Laradic\IconGenerator;

use Closure;

class Font
{
    /**
     * the font's name
     *
     * @var string
     */
    protected $name;

    /**
     * dataResolver method
     *
     * @var Closure
     */
    protected $dataExtractor;

    protected $path;

    protected $iconData;

    /**
     * Font constructor.
     *
     * @param string $name
     */
    public function __construct($name, $path)
    {
        $this->name = $name;
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the dataResolver value
     *
     * @param \Closure $dataExtractor
     *
     * @return Font
     */
    public function setDataExtractor($dataExtractor)
    {
        $this->dataExtractor = $dataExtractor;
        return $this;
    }

    public function getIconData()
    {
        if ( null === $this->iconData ) {
            $this->iconData = call_user_func($this->dataExtractor, $this);
        }
        return $this->iconData;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    public function __toString()
    {
        return $this->name;
    }


}
