<?php namespace XoopsModules\Xnews;

/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

/**
 * Class Registryfile
 */
class Registryfile
{
    public $filename; // filename to manage

    /**
     * @param null $file
     */
    public function __constructor($file = null)
    {
        $this->setfile($file);
    }

    /**
     * @param null $file
     */
    public function setfile($file = null)
    {
        if ($file) {
            $this->filename = XOOPS_UPLOAD_PATH . '/' . $file;
        }
    }

    /**
     * @param null $file
     * @return bool|string
     */
    public function getfile($file = null)
    {
        $fw = '';
        if (!$file) {
            $fw = $this->filename;
        } else {
            $fw = XOOPS_UPLOAD_PATH . '/' . $file;
        }
        if (file_exists($fw)) {
            return file_get_contents($fw);
        } else {
            return '';
        }
    }

    /**
     * @param      $content
     * @param null $file
     * @return bool
     */
    public function savefile($content, $file = null)
    {
        $fw = '';
        if (!$file) {
            $fw = $this->filename;
        } else {
            $fw = XOOPS_UPLOAD_PATH . '/' . $file;
        }
        if (file_exists($fw)) {
            @unlink($fw);
        }
        $fp = fopen($fw, 'w') || exit(_ERRORS);
        fwrite($fp, $content);
        fclose($fp);

        return true;
    }
}
