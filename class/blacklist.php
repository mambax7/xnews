<?php
defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

/**
 * Class nw_blacklist
 */
class nw_blacklist
{
    public $keywords;  // Holds keywords

    /**
     * Get all the keywords
     */
    public function getAllKeywords()
    {
        $myts = MyTextSanitizer::getInstance();
        //
        $ret      = $tbl_black_list = [];
        $filename = XOOPS_UPLOAD_PATH . '/nw_black_list.php';
        if (file_exists($filename)) {
            require_once $filename;
            foreach ($tbl_black_list as $onekeyword) {
                if ('' != xoops_trim($onekeyword)) {
                    $onekeyword       = $myts->htmlSpecialChars($onekeyword);
                    $ret[$onekeyword] = $onekeyword;
                }
            }
        }
        asort($ret);
        $this->keywords = $ret;

        return $this->keywords;
    }

    /**
     * Remove one or many keywords from the list
     * @param $keyword
     */
    public function delete($keyword)
    {
        if (is_array($keyword)) {
            foreach ($keyword as $onekeyword) {
                if (isset($this->keywords[$onekeyword])) {
                    unset($this->keywords[$onekeyword]);
                }
            }
        } else {
            if (isset($this->keywords[$keyword])) {
                unset($this->keywords[$keyword]);
            }
        }
    }

    /**
     * Add one or many keywords
     * @param $keyword
     */
    public function addkeywords($keyword)
    {
        $myts = MyTextSanitizer::getInstance();
        //
        if (is_array($keyword)) {
            foreach ($keyword as $onekeyword) {
                $onekeyword = xoops_trim($myts->htmlSpecialChars($onekeyword));
                if ('' != $onekeyword) {
                    $this->keywords[$onekeyword] = $onekeyword;
                }
            }
        } else {
            $keyword = xoops_trim($myts->htmlSpecialChars($keyword));
            if ('' != $keyword) {
                $this->keywords[$keyword] = $keyword;
            }
        }
    }

    /**
     * Remove, from a list, all the blacklisted words
     * @param $keywords
     * @return array
     */
    public function remove_blacklisted($keywords)
    {
        $ret       = [];
        $tmp_array = array_values($this->keywords);
        foreach ($keywords as $onekeyword) {
            $add = true;
            foreach ($tmp_array as $onebanned) {
                if (preg_match('/' . $onebanned . '/i', $onekeyword)) {
                    $add = false;
                    break;
                }
            }
            if ($add) {
                $ret[] = $onekeyword;
            }
        }

        return $ret;
    }

    /**
     * Save keywords
     */
    public function store()
    {
        $filename = XOOPS_UPLOAD_PATH . '/nw_black_list.php';
        if (file_exists($filename)) {
            unlink($filename);
        }
        $fd = fopen($filename, 'w') || exit('Error unable to create the blacklist file');
        fwrite($fd, "<?php\n");
        fwrite($fd, '$tbl_black_list=array(' . "\n");
        foreach ($this->keywords as $onekeyword) {
            fwrite($fd, '"' . $onekeyword . "\",\n");
        }
        fwrite($fd, "'');\n");
        fwrite($fd, '?' . ">\n");
        fclose($fd);
    }
}
