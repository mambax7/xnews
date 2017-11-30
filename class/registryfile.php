<?php
defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

/**
 * Class nw_registryfile
 */
class nw_registryfile
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
