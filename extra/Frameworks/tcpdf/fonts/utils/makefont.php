<?php
//============================================================+
// File name   : makefont.php
// Begin       : 2004-12-31
// Last Update : 2008-12-06
// Version     : 1.2.004
// License     : GNU LGPL (http://www.gnu.org/copyleft/lesser.html)
// 	----------------------------------------------------------------------------
// 	Copyright (C) 2008  Nicola Asuni - Tecnick.com S.r.l.
//
// 	This program is free software: you can redistribute it and/or modify
// 	it under the terms of the GNU Lesser General Public License as published by
// 	the Free Software Foundation, either version 2.1 of the License, or
// 	(at your option) any later version.
//
// 	This program is distributed in the hope that it will be useful,
// 	but WITHOUT ANY WARRANTY; without even the implied warranty of
// 	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// 	GNU Lesser General Public License for more details.
//
// 	You should have received a copy of the GNU Lesser General Public License
// 	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
// 	See LICENSE.TXT file for more information.
//  ----------------------------------------------------------------------------
//
// Description : Utility to generate font definition files fot TCPDF
//
// Authors: Nicola Asuni, Olivier Plathey, Steven Wittens
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com S.r.l.
//               Via della Pace, 11
//               09044 Quartucciu (CA)
//               ITALY
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Utility to generate font definition files fot TCPDF.
 * @author Nicola Asuni, Olivier Plathey, Steven Wittens
 * @copyright 2004-2009 Nicola Asuni - Tecnick.com S.r.l (www.tecnick.com) Via Della Pace, 11 - 09044 - Quartucciu (CA) - ITALY - www.tecnick.com - info@tecnick.com
 * @package com.tecnick.tcpdf
 * @link http://www.tcpdf.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
*/

/**
 *
 * @param string $fontfile path to font file (TTF, OTF or PFB).
 * @param string $fmfile font metrics file (UFM or AFM).
 * @param boolean $embedded Set to false to not embed the font, true otherwise (default).
 * @param string $enc Name of the encoding table to use. Omit this parameter for TrueType Unicode, OpenType Unicode and symbolic fonts like Symbol or ZapfDingBats.
 * @param array $patch Optional modification of the encoding
 */
function MakeFont($fontfile, $fmfile, $embedded=true, $enc='cp1252', $patch= [])
{
    //Generate a font definition file
    set_magic_quotes_runtime(0);
    ini_set('auto_detect_line_endings', '1');
    if (!file_exists($fontfile)) {
        die('Error: file not found: '.$fontfile);
    }
    if (!file_exists($fmfile)) {
        die('Error: file not found: '.$fmfile);
    }
    $cidtogidmap = '';
    $map = [];
    $diff = '';
    $dw = 0; // default width
    $ffext = strtolower(substr($fontfile, -3));
    $fmext = strtolower(substr($fmfile, -3));
    if ('afm' == $fmext) {
        if (('ttf' == $ffext) or ('otf' == $ffext)) {
            $type = 'TrueType';
        } elseif ('pfb' == $ffext) {
            $type = 'Type1';
        } else {
            die('Error: unrecognized font file extension: '.$ffext);
        }
        if ($enc) {
            $map = ReadMap($enc);
            foreach ($patch as $cc => $gn) {
                $map[$cc] = $gn;
            }
        }
        $fm = ReadAFM($fmfile, $map);
        if (isset($widths['.notdef'])) {
            $dw = $widths['.notdef'];
        }
        if ($enc) {
            $diff = MakeFontEncoding($map);
        }
        $fd = MakeFontDescriptor($fm, empty($map));
    } elseif ('ufm' == $fmext) {
        $enc = '';
        if (('ttf' == $ffext) or ('otf' == $ffext)) {
            $type = 'TrueTypeUnicode';
        } else {
            die('Error: not a TrueType font: '.$ffext);
        }
        $fm = ReadUFM($fmfile, $cidtogidmap);
        $dw = $fm['MissingWidth'];
        $fd = MakeFontDescriptor($fm, false);
    }
    //Start generation
    $s = '<?php'."\n";
    $s .= '$type=\''.$type."';\n";
    $s .= '$name=\''.$fm['FontName']."';\n";
    $s .= '$desc='.$fd.";\n";
    if (!isset($fm['UnderlinePosition'])) {
        $fm['UnderlinePosition'] = -100;
    }
    if (!isset($fm['UnderlineThickness'])) {
        $fm['UnderlineThickness'] = 50;
    }
    $s .= '$up='.$fm['UnderlinePosition'].";\n";
    $s .= '$ut='.$fm['UnderlineThickness'].";\n";
    if ($dw <= 0) {
        if (isset($fm['Widths'][32]) and ($fm['Widths'][32] > 0)) {
            // assign default space width
            $dw = $fm['Widths'][32];
        } else {
            $dw = 600;
        }
    }
    $s .= '$dw='.$dw.";\n";
    $w = MakeWidthArray($fm);
    $s .= '$cw='.$w.";\n";
    $s .= '$enc=\''.$enc."';\n";
    $s .= '$diff=\''.$diff."';\n";
    $basename = substr(basename($fmfile), 0, -4);
    if ($embedded) {
        //Embedded font
        if (('TrueType' == $type) or ('TrueTypeUnicode' == $type)) {
            CheckTTF($fontfile);
        }
        $f = fopen($fontfile, 'rb');
        if (!$f) {
            die('Error: Unable to open '.$fontfile);
        }
        $file = fread($f, filesize($fontfile));
        fclose($f);
        if ('Type1' == $type) {
            //Find first two sections and discard third one
            $header = (128 == ord($file{0}));
            if ($header) {
                //Strip first binary header
                $file = substr($file, 6);
            }
            $pos = strpos($file, 'eexec');
            if (!$pos) {
                die('Error: font file does not seem to be valid Type1');
            }
            $size1 = $pos + 6;
            if ($header and (128 == ord($file{$size1}))) {
                //Strip second binary header
                $file = substr($file, 0, $size1).substr($file, $size1+6);
            }
            $pos = strpos($file, '00000000');
            if (!$pos) {
                die('Error: font file does not seem to be valid Type1');
            }
            $size2 = $pos - $size1;
            $file = substr($file, 0, ($size1 + $size2));
        }
        $basename = strtolower($basename);
        if (function_exists('gzcompress')) {
            $cmp = $basename.'.z';
            SaveToFile($cmp, gzcompress($file, 9), 'b');
            $s .= '$file=\''.$cmp."';\n";
            print 'Font file compressed (' . $cmp . ")\n";
            if (!empty($cidtogidmap)) {
                $cmp = $basename.'.ctg.z';
                SaveToFile($cmp, gzcompress($cidtogidmap, 9), 'b');
                print 'CIDToGIDMap created and compressed (' . $cmp . ")\n";
                $s .= '$ctg=\''.$cmp."';\n";
            }
        } else {
            $s .= '$file=\''.basename($fontfile)."';\n";
            print "Notice: font file could not be compressed (zlib extension not available)\n";
            if (!empty($cidtogidmap)) {
                $cmp = $basename.'.ctg';
                $f = fopen($cmp, 'wb');
                fwrite($f, $cidtogidmap);
                fclose($f);
                print 'CIDToGIDMap created (' . $cmp . ")\n";
                $s .= '$ctg=\''.$cmp."';\n";
            }
        }
        if ('Type1' == $type) {
            $s .= '$size1='.$size1.";\n";
            $s .= '$size2='.$size2.";\n";
        } else {
            $s.='$originalsize='.filesize($fontfile).";\n";
        }
    } else {
        //Not embedded font
        $s .= '$file='."'';\n";
    }
    $s .= '?>';
    SaveToFile($basename.'.php', $s);
    print 'Font definition file generated (' . $basename . ".php)\n";
}

/**
 * Read the specified encoding map.
 * @param string $enc map name (see /enc/ folder for valid names).
 * @return array
 */
function ReadMap($enc)
{
    //Read a map file
    $file = __DIR__ . '/enc/' . strtolower($enc) . '.map';
    $a = file($file);
    if (empty($a)) {
        die('Error: encoding not found: '.$enc);
    }
    $cc2gn = [];
    foreach ($a as $l) {
        if ('!' == $l{0}) {
            $e = preg_split('/[ \\t]+/', rtrim($l));
            $cc = hexdec(substr($e[0], 1));
            $gn = $e[2];
            $cc2gn[$cc] = $gn;
        }
    }
    for ($i = 0; $i <= 255; $i++) {
        if (!isset($cc2gn[$i])) {
            $cc2gn[$i] = '.notdef';
        }
    }
    return $cc2gn;
}

/**
 * Read UFM file
 * @param $file
 * @param $cidtogidmap
 * @return array
 */
function ReadUFM($file, &$cidtogidmap)
{
    //Prepare empty CIDToGIDMap
    $cidtogidmap = str_pad('', (256 * 256 * 2), "\x00");
    //Read a font metric file
    $a = file($file);
    if (empty($a)) {
        die('File not found');
    }
    $widths = [];
    $fm = [];
    foreach ($a as $l) {
        $e = explode(' ', rtrim($l));
        if (count($e) < 2) {
            continue;
        }
        $code = $e[0];
        $param = $e[1];
        if ('U' == $code) {
            // U 827 ; WX 0 ; N squaresubnosp ; G 675 ;
            //Character metrics
            $cc = (int)$e[1];
            if ($cc != -1) {
                $gn = $e[7];
                $w = $e[4];
                $glyph = $e[10];
                $widths[$cc] = $w;
                if ($cc == ord('X')) {
                    $fm['CapXHeight'] = $e[13];
                }
                // Set GID
                if (($cc >= 0) and ($cc < 0xFFFF) and $glyph) {
                    $cidtogidmap{($cc * 2)} = chr($glyph >> 8);
                    $cidtogidmap{(($cc * 2) + 1)} = chr($glyph & 0xFF);
                }
            }
            if (('.notdef' == $gn) and (!isset($fm['MissingWidth']))) {
                $fm['MissingWidth'] = $w;
            }
        } elseif ('FontName' == $code) {
            $fm['FontName'] = $param;
        } elseif ('Weight' == $code) {
            $fm['Weight'] = $param;
        } elseif ('ItalicAngle' == $code) {
            $fm['ItalicAngle'] = (double)$param;
        } elseif ('Ascender' == $code) {
            $fm['Ascender'] = (int)$param;
        } elseif ('Descender' == $code) {
            $fm['Descender'] = (int)$param;
        } elseif ('UnderlineThickness' == $code) {
            $fm['UnderlineThickness'] = (int)$param;
        } elseif ('UnderlinePosition' == $code) {
            $fm['UnderlinePosition'] = (int)$param;
        } elseif ('IsFixedPitch' == $code) {
            $fm['IsFixedPitch'] = ('true' == $param);
        } elseif ('FontBBox' == $code) {
            $fm['FontBBox'] = [$e[1], $e[2], $e[3], $e[4]];
        } elseif ('CapHeight' == $code) {
            $fm['CapHeight'] = (int)$param;
        } elseif ('StdVW' == $code) {
            $fm['StdVW'] = (int)$param;
        }
    }
    if (!isset($fm['MissingWidth'])) {
        $fm['MissingWidth'] = 600;
    }
    if (!isset($fm['FontName'])) {
        die('FontName not found');
    }
    $fm['Widths'] = $widths;
    return $fm;
}

/**
 * Read AFM file
 * @param $file
 * @param $map
 * @return array
 */
function ReadAFM($file, &$map)
{
    //Read a font metric file
    $a = file($file);
    if (empty($a)) {
        die('File not found');
    }
    $widths = [];
    $fm = [];
    $fix = [
        'Edot'=>'Edotaccent',
        'edot'=>'edotaccent',
        'Idot'=>'Idotaccent',
        'Zdot'=>'Zdotaccent',
        'zdot'=>'zdotaccent',
        'Odblacute' => 'Ohungarumlaut',
        'odblacute' => 'ohungarumlaut',
        'Udblacute'=>'Uhungarumlaut',
        'udblacute'=>'uhungarumlaut',
        'Gcedilla'=>'Gcommaaccent'
        ,'gcedilla'=>'gcommaaccent',
        'Kcedilla'=>'Kcommaaccent',
        'kcedilla'=>'kcommaaccent',
        'Lcedilla'=>'Lcommaaccent',
        'lcedilla'=>'lcommaaccent',
        'Ncedilla'=>'Ncommaaccent',
        'ncedilla'=>'ncommaaccent',
        'Rcedilla'=>'Rcommaaccent',
        'rcedilla'=>'rcommaaccent',
        'Scedilla'=>'Scommaaccent',
        'scedilla'=>'scommaaccent',
        'Tcedilla'=>'Tcommaaccent',
        'tcedilla'=>'tcommaaccent',
        'Dslash'=>'Dcroat',
        'dslash'=>'dcroat',
        'Dmacron'=>'Dcroat',
        'dmacron'=>'dcroat',
        'combininggraveaccent'=>'gravecomb',
        'combininghookabove'=>'hookabovecomb',
        'combiningtildeaccent'=>'tildecomb',
        'combiningacuteaccent'=>'acutecomb',
        'combiningdotbelow'=>'dotbelowcomb',
        'dongsign'=>'dong'
    ];
    foreach ($a as $l) {
        $e = explode(' ', rtrim($l));
        if (count($e) < 2) {
            continue;
        }
        $code = $e[0];
        $param = $e[1];
        if ('C' == $code) {
            //Character metrics
            $cc = (int)$e[1];
            $w = $e[4];
            $gn = $e[7];
            if ('20AC' == substr($gn, -4)) {
                $gn = 'Euro';
            }
            if (isset($fix[$gn])) {
                //Fix incorrect glyph name
                foreach ($map as $c => $n) {
                    if ($n == $fix[$gn]) {
                        $map[$c] = $gn;
                    }
                }
            }
            if (empty($map)) {
                //Symbolic font: use built-in encoding
                $widths[$cc] = $w;
            } else {
                $widths[$gn] = $w;
                if ('X' == $gn) {
                    $fm['CapXHeight'] = $e[13];
                }
            }
            if ('.notdef' == $gn) {
                $fm['MissingWidth'] = $w;
            }
        } elseif ('FontName' == $code) {
            $fm['FontName'] = $param;
        } elseif ('Weight' == $code) {
            $fm['Weight'] = $param;
        } elseif ('ItalicAngle' == $code) {
            $fm['ItalicAngle'] = (double)$param;
        } elseif ('Ascender' == $code) {
            $fm['Ascender'] = (int)$param;
        } elseif ('Descender' == $code) {
            $fm['Descender'] = (int)$param;
        } elseif ('UnderlineThickness' == $code) {
            $fm['UnderlineThickness'] = (int)$param;
        } elseif ('UnderlinePosition' == $code) {
            $fm['UnderlinePosition'] = (int)$param;
        } elseif ('IsFixedPitch' == $code) {
            $fm['IsFixedPitch'] = ('true' == $param);
        } elseif ('FontBBox' == $code) {
            $fm['FontBBox'] = [$e[1], $e[2], $e[3], $e[4]];
        } elseif ('CapHeight' == $code) {
            $fm['CapHeight'] = (int)$param;
        } elseif ('StdVW' == $code) {
            $fm['StdVW'] = (int)$param;
        }
    }
    if (!isset($fm['FontName'])) {
        die('FontName not found');
    }
    if (!empty($map)) {
        if (!isset($widths['.notdef'])) {
            $widths['.notdef'] = 600;
        }
        if (!isset($widths['Delta']) and isset($widths['increment'])) {
            $widths['Delta'] = $widths['increment'];
        }
        //Order widths according to map
        for ($i = 0; $i <= 255; $i++) {
            if (!isset($widths[$map[$i]])) {
                print 'Warning: character ' . $map[$i] . " is missing\n";
                $widths[$i] = $widths['.notdef'];
            } else {
                $widths[$i] = $widths[$map[$i]];
            }
        }
    }
    $fm['Widths'] = $widths;
    return $fm;
}

function MakeFontDescriptor($fm, $symbolic=false)
{
    //Ascent
    $asc = (isset($fm['Ascender']) ? $fm['Ascender'] : 1000);
    $fd = "array('Ascent'=>".$asc;
    //Descent
    $desc = (isset($fm['Descender']) ? $fm['Descender'] : -200);
    $fd .= ",'Descent'=>".$desc;
    //CapHeight
    if (isset($fm['CapHeight'])) {
        $ch = $fm['CapHeight'];
    } elseif (isset($fm['CapXHeight'])) {
        $ch = $fm['CapXHeight'];
    } else {
        $ch = $asc;
    }
    $fd .= ",'CapHeight'=>".$ch;
    //Flags
    $flags = 0;
    if (isset($fm['IsFixedPitch']) and $fm['IsFixedPitch']) {
        $flags += 1<<0;
    }
    if ($symbolic) {
        $flags += 1<<2;
    } else {
        $flags += 1<<5;
    }
    if (isset($fm['ItalicAngle']) and (0 != $fm['ItalicAngle'])) {
        $flags += 1<<6;
    }
    $fd .= ",'Flags'=>".$flags;
    //FontBBox
    if (isset($fm['FontBBox'])) {
        $fbb = $fm['FontBBox'];
    } else {
        $fbb = [0, ($desc - 100), 1000, ($asc + 100)];
    }
    $fd .= ",'FontBBox'=>'[".$fbb[0].' '.$fbb[1].' '.$fbb[2].' '.$fbb[3]."]'";
    //ItalicAngle
    $ia = (isset($fm['ItalicAngle']) ? $fm['ItalicAngle'] : 0);
    $fd .= ",'ItalicAngle'=>".$ia;
    //StemV
    if (isset($fm['StdVW'])) {
        $stemv = $fm['StdVW'];
    } elseif (isset($fm['Weight']) and eregi('(bold|black)', $fm['Weight'])) {
        $stemv = 120;
    } else {
        $stemv = 70;
    }
    $fd .= ",'StemV'=>".$stemv;
    //MissingWidth
    if (isset($fm['MissingWidth'])) {
        $fd .= ",'MissingWidth'=>".$fm['MissingWidth'];
    }
    $fd .= ')';
    return $fd;
}

function MakeWidthArray($fm)
{
    //Make character width array
    $s = 'array(';
    $cw = $fm['Widths'];
    $els = [];
    $c = 0;
    foreach ($cw as $i => $w) {
        if (is_numeric($i)) {
            $els[] = ((0 == (($c++) % 10)) ? "\n" : '') . $i . '=>' . $w;
        }
    }
    $s .= implode(',', $els);
    $s .= ')';
    return $s;
}

function MakeFontEncoding($map)
{
    //Build differences from reference encoding
    $ref = ReadMap('cp1252');
    $s = '';
    $last = 0;
    for ($i = 32; $i <= 255; $i++) {
        if ($map[$i] != $ref[$i]) {
            if ($i != $last+1) {
                $s .= $i.' ';
            }
            $last = $i;
            $s .= '/'.$map[$i].' ';
        }
    }
    return rtrim($s);
}

function SaveToFile($file, $s, $mode='t')
{
    $f = fopen($file, 'w'.$mode);
    if (!$f) {
        die('Can\'t write to file '.$file);
    }
    fwrite($f, $s, strlen($s));
    fclose($f);
}

function ReadShort($f)
{
    $a = unpack('n1n', fread($f, 2));
    return $a['n'];
}

function ReadLong($f)
{
    $a = unpack('N1N', fread($f, 4));
    return $a['N'];
}

function CheckTTF($file)
{
    //Check if font license allows embedding
    $f = fopen($file, 'rb');
    if (!$f) {
        die('Error: unable to open '.$file);
    }
    //Extract number of tables
    fseek($f, 4, SEEK_CUR);
    $nb = ReadShort($f);
    fseek($f, 6, SEEK_CUR);
    //Seek OS/2 table
    $found = false;
    for ($i = 0; $i < $nb; $i++) {
        if ('OS/2' == fread($f, 4)) {
            $found = true;
            break;
        }
        fseek($f, 12, SEEK_CUR);
    }
    if (!$found) {
        fclose($f);
        return;
    }
    fseek($f, 4, SEEK_CUR);
    $offset = ReadLong($f);
    fseek($f, $offset, SEEK_SET);
    //Extract fsType flags
    fseek($f, 8, SEEK_CUR);
    $fsType = ReadShort($f);
    $rl = 0 != ($fsType & 0x02);
    $pp = 0 != ($fsType & 0x04);
    $e = 0 != ($fsType & 0x08);
    fclose($f);
    if ($rl and (!$pp) and (!$e)) {
        print "Warning: font license does not allow embedding\n";
    }
}

$arg = $GLOBALS['argv'];
if (count($arg) >= 3) {
    ob_start();
    array_shift($arg);
    if (3 == count($arg)) {
        $arg[3] = $arg[2];
        $arg[2] = true;
    } else {
        if (!isset($arg[2])) {
            $arg[2] = true;
        }
        if (!isset($arg[3])) {
            $arg[3] = 'cp1252';
        }
    }
    if (!isset($arg[4])) {
        $arg[4] = [];
    }
    MakeFont($arg[0], $arg[1], $arg[2], $arg[3], $arg[4]);
    $t = ob_get_clean();
    print preg_replace('!<BR( /)?>!i', "\n", $t);
} else {
    print "Usage: makefont.php <ttf/otf/pfb file> <afm/ufm file> <encoding> <patch>\n";
}
