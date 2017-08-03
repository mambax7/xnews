<?php

/**
 * This file is part of WideImage.
 *
 * WideImage is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation; either version 2.1 of the License, or
 * (at your option) any later version.
 *
 * WideImage is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with WideImage; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 **/
class wiTrueColorImage extends wiImage
{
    public function __construct($handle)
    {
        parent::__construct($handle);
        $this->alphaBlending(false);
        $this->saveAlpha(true);
    }

    public static function create($width, $height)
    {
        return new wiTrueColorImage(imagecreatetruecolor($width, $height));
    }

    public function doCreate($width, $height)
    {
        return self::create($width, $height);
    }

    public function isTrueColor()
    {
        return true;
    }

    public function alphaBlending($mode)
    {
        return imagealphablending($this->handle, $mode);
    }

    public function saveAlpha($on)
    {
        return imagesavealpha($this->handle, $on);
    }

    public function allocateColorAlpha($R, $G = null, $B = null, $A = null)
    {
        if (is_array($R)) {
            return imagecolorallocatealpha($this->handle, $R['red'], $R['green'], $R['blue'], $R['alpha']);
        } else {
            return imagecolorallocatealpha($this->handle, $R, $G, $B, $A);
        }
    }

    public function asPalette($nColors = 255, $dither = null, $matchPalette = true)
    {
        $nColors = intval($nColors);
        if ($nColors < 1) {
            $nColors = 1;
        } elseif ($nColors > 255) {
            $nColors = 255;
        }

        if ($dither === null) {
            $dither = $this->isTransparent();
        }

        $temp = $this->copy();
        imagetruecolortopalette($temp->handle, $dither, $nColors);

        if ($matchPalette === true) {
            imagecolormatch($this->handle, $temp->handle);
        }

        if ($this->isTransparent()) {
            $trgb = $this->getTransparentColorRGB();
            $tci  = $temp->getClosestColor($trgb);
            $temp->setTransparentColor($tci);
        }

        $temp->releaseHandle();

        return new wiPaletteImage($temp->handle);
    }

    public function getClosestColorAlpha($R, $G = null, $B = null, $A = null)
    {
        if (is_array($R)) {
            return imagecolorclosestalpha($this->handle, $R['red'], $R['green'], $R['blue'], $R['alpha']);
        } else {
            return imagecolorclosestalpha($this->handle, $R, $G, $B, $A);
        }
    }

    public function getExactColorAlpha($R, $G = null, $B = null, $A = null)
    {
        if (is_array($R)) {
            return imagecolorexactalpha($this->handle, $R['red'], $R['green'], $R['blue'], $R['alpha']);
        } else {
            return imagecolorexactalpha($this->handle, $R, $G, $B, $A);
        }
    }

    public function getChannels()
    {
        $args = func_get_args();
        if (count($args) == 1 && is_array($args[0])) {
            $args = $args[0];
        }

        return wiOpFactory::get('CopyChannelsTrueColor')->execute($this, $args);
    }

    public function copyNoAlpha()
    {
        $prev   = $this->saveAlpha(false);
        $result = wiImage::loadFromString($this->asString('png'));
        $this->saveAlpha($prev);

        //$result->releaseHandle();
        return $result;
    }

    public function asTrueColor()
    {
        return $this->copy();
    }
}
