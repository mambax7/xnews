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
class wiPaletteImage extends wiImage
{
    public static function create($width, $height)
    {
        return new wiPaletteImage(imagecreate($width, $height));
    }

    public function doCreate($width, $height)
    {
        return self::create($width, $height);
    }

    public function isTrueColor()
    {
        return false;
    }

    public function asPalette()
    {
        return $this->copy();
    }

    protected function copyAsNew($trueColor = false)
    {
        $width  = $this->getWidth();
        $height = $this->getHeight();

        if ($trueColor) {
            $new = wiTrueColorImage::create($width, $height);
        } else {
            $new = wiPaletteImage::create($width, $height);
        }

        // copy transparency of source to target
        if ($this->isTransparent()) {
            $rgb = $this->getTransparentColorRGB();
            if (is_array($rgb)) {
                $tci = $new->allocateColor($rgb['red'], $rgb['green'], $rgb['blue']);
                $new->fill(0, 0, $tci);
                $new->setTransparentColor($tci);
            }
        }

        imagecopy($new->getHandle(), $this->handle, 0, 0, 0, 0, $width, $height);

        return $new;
    }

    public function asTrueColor()
    {
        $width  = $this->getWidth();
        $height = $this->getHeight();
        $new    = wiTrueColorImage::create($width, $height);
        if ($this->isTransparent()) {
            $new->copyTransparencyFrom($this);
        }
        imagecopy($new->getHandle(), $this->handle, 0, 0, 0, 0, $width, $height);

        return $new;
    }

    public function getChannels()
    {
        $args = func_get_args();
        if (1 == count($args) && is_array($args[0])) {
            $args = $args[0];
        }

        return wiOpFactory::get('CopyChannelsPalette')->execute($this, $args);
    }

    public function copyNoAlpha()
    {
        return wiImage::loadFromString($this->asString('png'));
    }
}
