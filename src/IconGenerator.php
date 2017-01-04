<?php
namespace Laradic\IconGenerator;


/**
 * This is the class IconGenerator.
 *
 * @package        Laradic\IconGenerator
 * @author         Radic
 * @copyright      Copyright (c) 2015, Radic. All rights reserved
 */
class IconGenerator
{
    /**
     * @var string
     */
    protected $outDir;

    /** @var Font */
    protected $font;

    /**
     * @var
     */
    protected $iconData;

    /**
     * @var array
     */
    protected $icons = [];

    /**
     * @var array
     */
    protected $sizes = [];

    /**
     * @var array
     */
    protected $colors = [];

    /**
     * IconGenerator constructor.
     *
     * @param \Laradic\IconGenerator\Factory $factory
     * @param \Laradic\IconGenerator\Font    $font
     */
    public function __construct(Font $font)
    {
        $this->font = $font;
    }

    /**
     * getFont method
     * @return \Laradic\IconGenerator\Font
     */
    public function getFont()
    {
        return $this->font;
    }

    /**
     * @param $icons
     *
     * @return $this
     */
    public function setIcons($icons)
    {
        $this->icons = is_array($icons) ? $icons : func_get_args();

        return $this;
    }

    /**
     * @param $sizes
     *
     * @return $this
     */
    public function setSizes($sizes)
    {
        $this->sizes = is_array($sizes) ? $sizes : func_get_args();

        return $this;
    }

    /**
     * @return $this
     */
    public function resetColors()
    {
        $this->colors = [];

        return $this;
    }

    public function reset()
    {
        $this->colors = [];
        $this->sizes  = [];
        $this->icons  = [];

        return $this;
    }

    /**
     * @param string      $r
     * @param string|null $g
     * @param string|null $b
     *
     * @return $this
     */
    public function addColor($r, $g = null, $b = null)
    {
        $this->colors[] = compact('r', 'g', 'b');

        return $this;
    }

    /**
     * @param $colors
     *
     * @return $this
     */
    public function setColors($colors)
    {
        $colors = is_array($colors) ? $colors : func_get_args();
        foreach ( $colors as $color ) {
            if ( is_string($color) ) {
                $this->addColor($color);
            } elseif ( is_array($color) ) {
                call_user_func_array([ $this, 'addColor' ], $color);
            } else {
                throw new \InvalidArgumentException('Not a valid color');
            }
        }

        return $this;
    }

    /**
     * Set the directory where to generate the icons
     *
     * @param $outDir string The absolute path to the
     *
     * @return $this
     */
    public function setOutDir($outDir)
    {
        $this->outDir = $outDir;
        return $this;
    }


    /**
     * Starts the generator and generates the PNG images
     *
     * @param string $prefix Optional filename prefix for all generated files
     *
     * @return string[] The generated file paths
     */
    public function generate($prefix = '')
    {

        if ( $this->iconData === null ) {
            $this->iconData = $this->font->getIconData();
        }
        if ( file_exists($this->outDir) === false ) {
            file_makeDirectory($this->outDir, 0755, true);
        }

        $generated = [];

        foreach ( $this->icons as $icon ) {
            $text = $this->iconData[ $icon ];

            foreach ( $this->sizes as $size ) {
                foreach ( $this->colors as $color ) {

                    if ( null === $color[ 'g' ] ) {
                        $fileNameColor = str_remove_left($color[ 'r' ], '#');
                        $color         = $this->hex2RGB($color[ 'r' ]);
                        if ( $color === false ) {
                            continue;
                        }
                    } else {
                        $fileNameColor = "r{$color['r']}g{$color['g']}b{$color['b']}";
                    }
                    $fileName = "{$prefix}{$icon}-{$size}x{$size}-{$fileNameColor}.png";
                    $fileName = $this->outDir . DIRECTORY_SEPARATOR . $fileName;
                    $this->create($text, $size, $color, $fileName);
                    $generated[] = $fileName;
                }
            }
        }

        return $generated;
    }


    /**
     * @param       $text
     * @param       $outputSize
     * @param array $color
     * @param       $fileName
     */
    protected function create($text, $outputSize, array $color, $fileName)
    {
        // Set the content-type
        if(php_sapi_name() !== 'cli') {
            header('Content-Type: image/png');
        }

        #$outputSize = 64;

        #$font = __DIR__ . '/font.ttf';
        #$fileName = __DIR__ . '/out.png';
        $size     = $width = $height = $outputSize * 3;
        $fontSize = $outputSize;
        $padding  = (int)ceil(($outputSize / 25));

        // Create the image
        $im = imagecreatetruecolor($width, $height);
        imagealphablending($im, false);

        // Create some colors
        $fontC = imagecolorallocate($im, $color[ 'r' ], $color[ 'g' ], $color[ 'b' ]);
        $bgc   = imagecolorallocatealpha($im, 255, 0, 255, 127);
        imagefilledrectangle($im, 0, 0, $width, $height, $bgc);
        imagealphablending($im, true);

        // Add the text
        list($fontX, $fontY) = $this->ImageTTFCenter($im, $text, $this->font->getPath(), $fontSize);
        imagettftext($im, $fontSize, 0, $fontX, $fontY, $fontC, $this->font->getPath(), $text);

        // Using imagepng() results in clearer text compared with imagejpeg()
        imagealphablending($im, false);
        imagesavealpha($im, true);
        $this->imagetrim($im, $bgc, $padding);
        $this->imagecanvas($im, $outputSize, $bgc, $padding);
        imagepng($im, $fileName);
        imagedestroy($im);
    }

    /**
     * @param     $image
     * @param     $text
     * @param     $font
     * @param     $size
     * @param int $angle
     *
     * @return array
     */
    protected function ImageTTFCenter($image, $text, $font, $size, $angle = 45)
    {
        $xi = imagesx($image);
        $yi = imagesy($image);
        // First we create our bounding box for the first text
        $box = imagettfbbox($size, $angle, $font, $text);
        $xr  = abs(max($box[ 2 ], $box[ 4 ]));
        $yr  = abs(max($box[ 5 ], $box[ 7 ]));
        // compute centering
        $x = intval(($xi - $xr) / 2);
        $y = intval(($yi + $yr) / 2);

        //echo $x;echo '|';	echo $y;exit;
        return [ $x, $y ];
    }

    /**
     * @param      $im
     * @param      $bg
     * @param null $pad
     */
    protected function imagetrim(&$im, $bg, $pad = null)
    {
        // Calculate padding for each side.
        if ( isset($pad) ) {
            $pp = explode(' ', $pad);
            if ( isset($pp[ 3 ]) ) {
                $p = [ (int)$pp[ 0 ], (int)$pp[ 1 ], (int)$pp[ 2 ], (int)$pp[ 3 ] ];
            } else {
                if ( isset($pp[ 2 ]) ) {
                    $p = [ (int)$pp[ 0 ], (int)$pp[ 1 ], (int)$pp[ 2 ], (int)$pp[ 1 ] ];
                } else {
                    if ( isset($pp[ 1 ]) ) {
                        $p = [ (int)$pp[ 0 ], (int)$pp[ 1 ], (int)$pp[ 0 ], (int)$pp[ 1 ] ];
                    } else {
                        $p = array_fill(0, 4, (int)$pp[ 0 ]);
                    }
                }
            }
        } else {
            $p = array_fill(0, 4, 0);
        }
        // Get the image width and height.
        $imw = imagesx($im);
        $imh = imagesy($im);
        // Set the X variables.
        $xmin = $imw;
        $xmax = 0;
        // Start scanning for the edges.
        for ( $iy = 0; $iy < $imh; $iy++ ) {
            $first = true;
            for ( $ix = 0; $ix < $imw; $ix++ ) {
                $ndx = imagecolorat($im, $ix, $iy);
                if ( $ndx != $bg ) {
                    if ( $xmin > $ix ) {
                        $xmin = $ix;
                    }
                    if ( $xmax < $ix ) {
                        $xmax = $ix;
                    }
                    if ( !isset($ymin) ) {
                        $ymin = $iy;
                    }
                    $ymax = $iy;
                    if ( $first ) {
                        $ix    = $xmax;
                        $first = false;
                    }
                }
            }
        }
        // The new width and height of the image. (not including padding)
        $imw = 1 + $xmax - $xmin; // Image width in pixels
        $imh = 1 + $ymax - $ymin; // Image height in pixels
        // Make another image to place the trimmed version in.
        $im2 = imagecreatetruecolor($imw + $p[ 1 ] + $p[ 3 ], $imh + $p[ 0 ] + $p[ 2 ]);
        // Make the background of the new image the same as the background of the old one.
        $bg2 = imagecolorallocatealpha($im2, ($bg >> 16) & 0xFF, ($bg >> 8) & 0xFF, $bg & 0xFF, 127);
        imagefill($im2, 0, 0, $bg2);
        imagealphablending($im2, true);
        // Copy it over to the new image.
        imagecopy($im2, $im, $p[ 3 ], $p[ 0 ], $xmin, $ymin, $imw, $imh);
        // To finish up, we replace the old image which is referenced.
        imagealphablending($im2, false);
        imagesavealpha($im2, true);
        $im = $im2;
        //imagedestroy($im2);
    }

    /**
     * @param $im
     * @param $size
     * @param $bg
     * @param $padding
     */
    protected function imagecanvas(&$im, $size, $bg, $padding)
    {
        $srcW = imagesx($im);
        $srcH = imagesy($im);

        $srcRatio = $srcW / $srcH;

        $im2 = imagecreatetruecolor($size, $size);
        $bg2 = imagecolorallocatealpha($im2, ($bg >> 16) & 0xFF, ($bg >> 8) & 0xFF, $bg & 0xFF, 127);
        //imagefilledrectangle($im2, 0, 0, $size,$size, $bg2);
        imagefill($im2, 0, 0, $bg2);
        imagealphablending($im2, true);

        // init
        $dstX = $dstY = $srcX = $srcY = 0;
        $dstW = $dstH = $size;
        // if source size is smaller than output size
        if ( $srcW < $size && $srcH < $size ) {
            $dstW = $srcW;
            $dstH = $srcH;
        } // if source is bigger than output
        else {
            // use padding
            // if horizontal long
            if ( $srcW > $srcH ) {
                $dstW = $size - $padding;
                $dstH = (int)(($dstW / $srcW) * $srcH);
            } // if vertically long or equal(square)
            else {
                $dstH = $size - $padding;
                $dstW = (int)(($dstH / $srcH) * $srcW);
            }
        }

        $dstX = (int)(($size - $dstW) / 2);
        $dstY = (int)(($size - $dstH) / 2);

        // imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
        imagecopyresampled($im2, $im, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH);

        imagealphablending($im2, false);
        imagesavealpha($im2, true);
        $im = $im2;
        //imagedestroy($im2);
    }

    /**
     * Convert a hexa decimal color code to its RGB equivalent
     *
     * @param string  $hexStr         (hexadecimal color value)
     * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
     * @param string  $seperator      (to separate RGB values. Applicable only if second parameter is true.)
     *
     * @return array or string (depending on second parameter. Returns False if invalid hex color value)
     * @link http://php.net/manual/en/function.hexdec.php
     */
    protected function hex2RGB($hexStr, $returnAsString = false, $seperator = ',')
    {
        $hexStr   = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
        $rgbArray = [];
        if ( strlen($hexStr) == 6 ) { //If a proper hex code, convert using bitwise operation. No overhead... faster
            $colorVal        = hexdec($hexStr);
            $rgbArray[ 'r' ] = 0xFF & ($colorVal >> 0x10);
            $rgbArray[ 'g' ] = 0xFF & ($colorVal >> 0x8);
            $rgbArray[ 'b' ] = 0xFF & $colorVal;
        } elseif ( strlen($hexStr) == 3 ) { //if shorthand notation, need some string manipulations
            $rgbArray[ 'r' ] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
            $rgbArray[ 'g' ] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
            $rgbArray[ 'b' ] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
        } else {
            return false; //Invalid hex color code
        }

        return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
    }


}
