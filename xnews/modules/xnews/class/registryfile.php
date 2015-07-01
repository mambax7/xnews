<?php
defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

class nw_registryfile
{
    var $filename; // filename to manage

    function __constructor($file = null)
    {
        $this->setfile($file);
    }

    function setfile($file = null)
    {
        if ($file) {
            $this->filename = XOOPS_UPLOAD_PATH . '/' . $file;
        }
    }

    function getfile($file = null)
    {
        $fw = '';
        if(!$file) {
            $fw = $this->filename;
        } else {
            $fw = XOOPS_UPLOAD_PATH . '/' . $file;
        }
        if(file_exists($fw)) {
            return file_get_contents($fw);
        } else {
            return '';
        }
    }

    function savefile($content, $file = null)
    {
        $fw = '';
        if(!$file) {
            $fw = $this->filename;
        } else {
            $fw = XOOPS_UPLOAD_PATH.'/' . $file;
        }
        if(file_exists($fw)) {
            @unlink($fw);
        }
        $fp = fopen($fw, 'w') or die(_ERRORS);
        fwrite($fp, $content);
        fclose($fp);
        return true;
    }
}
