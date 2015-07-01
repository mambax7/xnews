<?php
defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

include_once XNEWS_MODULE_PATH . "/class/class.mimetype.php";

class nw_sFiles
{
    var $xnews;
    var $db;
    var $table;
    var $fileid;
    var $filerealname;
    var $storyid;
    var $date;
    var $mimetype;
    var $downloadname;
    var $counter;

    function __construct($fileid = -1)
    {
        $this->xnews = XnewsXnews::getInstance();
        $this->db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->table = $this->db->prefix('nw_stories_files');
        $this->storyid = 0;
        $this->filerealname = '';
        $this->date = 0;
        $this->mimetype = '';
        $this->downloadname = 'downloadfile';
        $this->counter = 0;
        if (is_array($fileid)){
            $this->makeFile($fileid);
        } elseif($fileid != -1) {
            $this->getFile(intval($fileid));
        }
    }

    function createUploadName($folder, $filename, $trimname = false)
    {
        $workingfolder = $folder;
        if(xoops_substr($workingfolder, strlen($workingfolder) - 1, 1) <> '/') {
            $workingfolder .= '/';
        }
        $ext = basename($filename);
        $ext = explode('.', $ext);
        $ext = '.' . $ext[count($ext)-1];
        $true=true;
        while($true) {
            $ipbits = explode('.', $_SERVER['REMOTE_ADDR']);
            list($usec, $sec) = explode(' ', microtime());

            $usec = (integer) ($usec * 65536);
            $sec = ((integer) $sec) & 0xFFFF;

            if ($trimname) {
                $uid = sprintf("%06x%04x%04x",($ipbits[0] << 24) | ($ipbits[1] << 16) | ($ipbits[2] << 8) | $ipbits[3], $sec, $usec);
            } else {
                $uid = sprintf("%08x-%04x-%04x",($ipbits[0] << 24) | ($ipbits[1] << 16) | ($ipbits[2] << 8) | $ipbits[3], $sec, $usec);
            }
            if (!file_exists($workingfolder . $uid . $ext)) {
                $true = false;
            }
        }
        return $uid.$ext;
    }

    function giveMimetype($filename = '')
    {
        $nw_cmimetype = new nw_cmimetype();
        $workingfile = $this->downloadname;
        if (xoops_trim($filename) != '') {
            $workingfile = $filename;
            return $nw_cmimetype->getType($workingfile);
        } else {
            return '';
        }
    }

    function getAllbyStory($storyid)
    {
       $ret = array();
       $sql = "SELECT *";
       $sql .= " FROM {$this->table}";
       $sql .= " WHERE storyid = " . intval($storyid);
       $result = $this->db->query($sql);
       while($myrow = $this->db->fetchArray($result)) {
            $ret[] = new nw_sFiles($myrow);
       }
       return $ret;
    }

    function getFile($id)
    {
       $sql = "SELECT *";
       $sql .= " FROM {$this->table}";
       $sql .= " WHERE fileid = " . intval($id);
       $array = $this->db->fetchArray($this->db->query($sql));
       $this->makeFile($array);
    }

    function makeFile($array)
    {
        foreach($array as $key=>$value) {
            $this->$key = $value;
        }
    }

    function store()
    {
        $myts =& MyTextSanitizer::getInstance();
        //
        $fileRealName = $myts->addSlashes($this->filerealname);
        $downloadname = $myts->addSlashes($this->downloadname);
        $date = time();
        $mimetype = $myts->addSlashes($this->mimetype);
        $counter = intval($this->counter);
        $storyid = intval($this->storyid);

        if (!isset($this->fileid)) {
            $newid = intval($this->db->genId($this->table . "_fileid_seq"));
            $sql = "INSERT INTO {$this->table} (fileid, storyid, filerealname, date, mimetype, downloadname, counter)";
            $sql .= " VALUES ({$newid}, {$storyid}, '{$fileRealName}', '{$date}','{$mimetype}', '{$downloadname}', {$counter})";
            $this->fileid=$newid;
        } else {
            $sql = "UPDATE {$this->table} SET storyid = {$storyid}, filerealname = '{$fileRealName}', date = {$date}, mimetype = '{$mimetype}', downloadname = '{$downloadname}',counter = {$counter}}";
            $sql .= " WHERE fileid={$this->getFileid()}";
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        return true;
    }

    function delete($workdir = XNEWS_ATTACHED_FILES_PATH)
    {
        $sql = "DELETE";
        $sql .= " FROM {$this->table}";
        $sql .= " WHERE fileid = {$this->getFileid()}";
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        if (file_exists($workdir . "/" . $this->downloadname)) {
            unlink($workdir . "/" . $this->downloadname);
            //DNPROSSI - Added thumb deletion
            if (strstr($this->getMimetype(), 'image')) {
// IN PROGRESS
// IN PROGRESS
// IN PROGRESS
                unlink($workdir . "/thumb_" . $this->downloadname);
            }
        }
       	return true;
    }

    function updateCounter()
    {
        $sql = "UPDATE {$this->table} SET counter = counter + 1";
        $sql .= " WHERE fileid = {$this->getFileid()}";
        if (!$result = $this->db->queryF($sql)) {
            return false;
        }
        return true;
    }

    // ****************************************************************************************************************
    // All the Sets
    // ****************************************************************************************************************
    function setFileRealName($filename)
    {
        $this->filerealname = $filename;
    }

    function setStoryid($id)
    {
        $this->storyid=intval($id);
    }

    function setMimetype($value)
    {
        $this->mimetype = $value;
    }

    function setDownloadname($value)
    {
        $this->downloadname = $value;
    }

    // ****************************************************************************************************************
    // All the Gets
    // ****************************************************************************************************************
    function getFileid()
    {
        return intval($this->fileid);
    }

    function getStoryid()
    {
        return intval($this->storyid);
    }

    function getCounter()
    {
        return intval($this->counter);
    }

    function getDate()
    {
        return intval($this->date);
    }

    function getFileRealName($format="S")
    {
        $myts =& MyTextSanitizer::getInstance();
        //
        switch($format) {
            case "S":
            case "Show":
                $filerealname = $myts->htmlSpecialChars($this->filerealname);
                break;
            case "E":
            case "Edit":
                $filerealname = $myts->htmlSpecialChars($this->filerealname);
                break;
            case "P":
            case "Preview":
                $filerealname = $myts->htmlSpecialChars($myts->stripSlashesGPC($this->filerealname));
                break;
            case "F":
            case "InForm":
                $filerealname = $myts->htmlSpecialChars($myts->stripSlashesGPC($this->filerealname));
                break;
        }
        return $filerealname;
    }

    function getMimetype($format="S")
    {
       $myts =& MyTextSanitizer::getInstance();
       //
       switch($format) {
            case "S":
            case "Show":
                $filemimetype = $myts->htmlSpecialChars($this->mimetype);
                break;
            case "E":
            case "Edit":
                $filemimetype = $myts->htmlSpecialChars($this->mimetype);
                break;
            case "P":
            case "Preview":
                $filemimetype = $myts->htmlSpecialChars($myts->stripSlashesGPC($this->mimetype));
                break;
            case "F":
            case "InForm":
                $filemimetype = $myts->htmlSpecialChars($myts->stripSlashesGPC($this->mimetype));
                break;
       }
       return $filemimetype;
    }

    function getDownloadname($format="S")
    {
       $myts =& MyTextSanitizer::getInstance();
       //
       switch($format) {
            case "S":
            case "Show":
                $filedownname = $myts->htmlSpecialChars($this->downloadname);
                break;
            case "E":
            case "Edit":
                $filedownname = $myts->htmlSpecialChars($this->downloadname);
                break;
            case "P":
            case "Preview":
                $filedownname = $myts->htmlSpecialChars($myts->stripSlashesGPC($this->downloadname));
                break;
            case "F":
            case "InForm":
                $filedownname = $myts->htmlSpecialChars($myts->stripSlashesGPC($this->downloadname));
                break;
       }
       return $filedownname;
    }

    // Deprecated
    function getCountbyStory($storyid)
    {
        $sql = "SELECT count(fileid) as cnt";
        $sql .= " FROM {$this->table}";
        $sql .= " WHERE storyid = " . intval($storyid)."";
        $result = $this->db->query($sql);
        $myrow = $this->db->fetchArray($result);
        return $myrow['cnt'];
    }

    function getCountbyStories($stories)
    {
        $ret = array();
        if(count($stories) > 0) {
            $sql = "SELECT storyid, count(fileid) as cnt";
            $sql .= " FROM {$this->db->prefix('nw_stories_files')}";
            $sql .= " WHERE storyid IN (" .  implode(',', $stories) . ") GROUP BY storyid";
            $result = $this->db->query($sql);
            while($myrow = $this->db->fetchArray($result)) {
                $ret[$myrow['storyid']] = $myrow['cnt'];
            }
        }
        return $ret;
    }
}
