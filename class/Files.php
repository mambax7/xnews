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

use XoopsModules\Xnews;

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

// require_once XNEWS_MODULE_PATH . '/class/Mimetype.php';

/**
 * Class Files
 */
class Files
{
    public $helper;
    public $db;
    public $table;
    public $fileid;
    public $filerealname;
    public $storyid;
    public $date;
    public $mimetype;
    public $downloadname;
    public $counter;

    /**
     * Files constructor.
     * @param int $fileid
     */
    public function __construct($fileid = -1)
    {
        $this->helper        = Xnews\Helper::getInstance();
        $this->db           = \XoopsDatabaseFactory::getDatabaseConnection();
        $this->table        = $this->db->prefix('nw_stories_files');
        $this->storyid      = 0;
        $this->filerealname = '';
        $this->date         = 0;
        $this->mimetype     = '';
        $this->downloadname = 'downloadfile';
        $this->counter      = 0;
        if (is_array($fileid)) {
            $this->makeFile($fileid);
        } elseif ($fileid != -1) {
            $this->getFile((int)$fileid);
        }
    }

    /**
     * @param      $folder
     * @param      $filename
     * @param bool $trimname
     * @return string
     */
    public function createUploadName($folder, $filename, $trimname = false)
    {
        $workingfolder = $folder;
        if ('/' !== xoops_substr($workingfolder, strlen($workingfolder) - 1, 1)) {
            $workingfolder .= '/';
        }
        $ext  = basename($filename);
        $ext  = explode('.', $ext);
        $ext  = '.' . $ext[count($ext) - 1];
        $true = true;
        while ($true) {
            $ipbits = explode('.', $_SERVER['REMOTE_ADDR']);
            list($usec, $sec) = explode(' ', microtime());

            $usec = (integer)($usec * 65536);
            $sec  = ((integer)$sec) & 0xFFFF;

            if ($trimname) {
                $uid = sprintf('%06x%04x%04x', ($ipbits[0] << 24) | ($ipbits[1] << 16) | ($ipbits[2] << 8) | $ipbits[3], $sec, $usec);
            } else {
                $uid = sprintf('%08x-%04x-%04x', ($ipbits[0] << 24) | ($ipbits[1] << 16) | ($ipbits[2] << 8) | $ipbits[3], $sec, $usec);
            }
            if (!file_exists($workingfolder . $uid . $ext)) {
                $true = false;
            }
        }

        return $uid . $ext;
    }

    /**
     * @param string $filename
     * @return mixed|string
     */
    public function giveMimetype($filename = '')
    {
        $Mimetype    = new Mimetype();
        $workingfile = $this->downloadname;
        if ('' != xoops_trim($filename)) {
            $workingfile = $filename;

            return $Mimetype->getType($workingfile);
        } else {
            return '';
        }
    }

    /**
     * @param $storyid
     * @return array
     */
    public function getAllbyStory($storyid)
    {
        $ret    = [];
        $sql    = 'SELECT *';
        $sql    .= " FROM {$this->table}";
        $sql    .= ' WHERE storyid = ' . (int)$storyid;
        $result = $this->db->query($sql);
        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $ret[] = new Xnews\Files($myrow);
        }

        return $ret;
    }

    /**
     * @param $id
     */
    public function getFile($id)
    {
        $sql   = 'SELECT *';
        $sql   .= " FROM {$this->table}";
        $sql   .= ' WHERE fileid = ' . (int)$id;
        $array = $this->db->fetchArray($this->db->query($sql));
        $this->makeFile($array);
    }

    /**
     * @param $array
     */
    public function makeFile($array)
    {
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @return bool
     */
    public function store()
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $fileRealName = $myts->addSlashes($this->filerealname);
        $downloadname = $myts->addSlashes($this->downloadname);
        $date         = time();
        $mimetype     = $myts->addSlashes($this->mimetype);
        $counter      = (int)$this->counter;
        $storyid      = (int)$this->storyid;

        if (!isset($this->fileid)) {
            $newid        = (int)$this->db->genId($this->table . '_fileid_seq');
            $sql          = "INSERT INTO {$this->table} (fileid, storyid, filerealname, date, mimetype, downloadname, counter)";
            $sql          .= " VALUES ({$newid}, {$storyid}, '{$fileRealName}', '{$date}','{$mimetype}', '{$downloadname}', {$counter})";
            $this->fileid = $newid;
        } else {
            $sql = "UPDATE {$this->table} SET storyid = {$storyid}, filerealname = '{$fileRealName}', date = {$date}, mimetype = '{$mimetype}', downloadname = '{$downloadname}',counter = {$counter}}";
            $sql .= " WHERE fileid={$this->getFileid()}";
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $workdir
     * @return bool
     */
    public function delete($workdir = XNEWS_ATTACHED_FILES_PATH)
    {
        $sql = 'DELETE';
        $sql .= " FROM {$this->table}";
        $sql .= " WHERE fileid = {$this->getFileid()}";
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        if (file_exists($workdir . '/' . $this->downloadname)) {
            unlink($workdir . '/' . $this->downloadname);
            //DNPROSSI - Added thumb deletion
            if (false !== strpos($this->getMimetype(), 'image')) {
                // IN PROGRESS
                // IN PROGRESS
                // IN PROGRESS
                unlink($workdir . '/thumb_' . $this->downloadname);
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function updateCounter()
    {
        $sql = "UPDATE {$this->table} SET counter = counter + 1";
        $sql .= " WHERE fileid = {$this->getFileid()}";
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        return true;
    }

    // ****************************************************************************************************************
    // All the Sets
    // ****************************************************************************************************************
    /**
     * @param $filename
     */
    public function setFileRealName($filename)
    {
        $this->filerealname = $filename;
    }

    /**
     * @param $id
     */
    public function setStoryid($id)
    {
        $this->storyid = (int)$id;
    }

    /**
     * @param $value
     */
    public function setMimetype($value)
    {
        $this->mimetype = $value;
    }

    /**
     * @param $value
     */
    public function setDownloadname($value)
    {
        $this->downloadname = $value;
    }

    // ****************************************************************************************************************
    // All the Gets
    // ****************************************************************************************************************
    /**
     * @return int
     */
    public function getFileid()
    {
        return (int)$this->fileid;
    }

    /**
     * @return int
     */
    public function getStoryid()
    {
        return (int)$this->storyid;
    }

    /**
     * @return int
     */
    public function getCounter()
    {
        return (int)$this->counter;
    }

    /**
     * @return int
     */
    public function getDate()
    {
        return (int)$this->date;
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function getFileRealName($format = 'S')
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        switch ($format) {
            case 'S':
            case 'Show':
                $filerealname = $myts->htmlSpecialChars($this->filerealname);
                break;
            case 'E':
            case 'Edit':
                $filerealname = $myts->htmlSpecialChars($this->filerealname);
                break;
            case 'P':
            case 'Preview':
                $filerealname = $myts->htmlSpecialChars($myts->stripSlashesGPC($this->filerealname));
                break;
            case 'F':
            case 'InForm':
                $filerealname = $myts->htmlSpecialChars($myts->stripSlashesGPC($this->filerealname));
                break;
        }

        return $filerealname;
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function getMimetype($format = 'S')
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        switch ($format) {
            case 'S':
            case 'Show':
                $filemimetype = $myts->htmlSpecialChars($this->mimetype);
                break;
            case 'E':
            case 'Edit':
                $filemimetype = $myts->htmlSpecialChars($this->mimetype);
                break;
            case 'P':
            case 'Preview':
                $filemimetype = $myts->htmlSpecialChars($myts->stripSlashesGPC($this->mimetype));
                break;
            case 'F':
            case 'InForm':
                $filemimetype = $myts->htmlSpecialChars($myts->stripSlashesGPC($this->mimetype));
                break;
        }

        return $filemimetype;
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function getDownloadname($format = 'S')
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        switch ($format) {
            case 'S':
            case 'Show':
                $filedownname = $myts->htmlSpecialChars($this->downloadname);
                break;
            case 'E':
            case 'Edit':
                $filedownname = $myts->htmlSpecialChars($this->downloadname);
                break;
            case 'P':
            case 'Preview':
                $filedownname = $myts->htmlSpecialChars($myts->stripSlashesGPC($this->downloadname));
                break;
            case 'F':
            case 'InForm':
                $filedownname = $myts->htmlSpecialChars($myts->stripSlashesGPC($this->downloadname));
                break;
        }

        return $filedownname;
    }

    // Deprecated

    /**
     * @param $storyid
     * @return mixed
     */
    public function getCountbyStory($storyid)
    {
        $sql    = 'SELECT count(fileid) AS cnt';
        $sql    .= " FROM {$this->table}";
        $sql    .= ' WHERE storyid = ' . (int)$storyid . '';
        $result = $this->db->query($sql);
        $myrow  = $this->db->fetchArray($result);

        return $myrow['cnt'];
    }

    /**
     * @param $stories
     * @return array
     */
    public function getCountbyStories($stories)
    {
        $ret = [];
        if (count($stories) > 0) {
            $sql    = 'SELECT storyid, count(fileid) AS cnt';
            $sql    .= " FROM {$this->db->prefix('nw_stories_files')}";
            $sql    .= ' WHERE storyid IN (' . implode(',', $stories) . ') GROUP BY storyid';
            $result = $this->db->query($sql);
            while (false !== ($myrow = $this->db->fetchArray($result))) {
                $ret[$myrow['storyid']] = $myrow['cnt'];
            }
        }

        return $ret;
    }
}
