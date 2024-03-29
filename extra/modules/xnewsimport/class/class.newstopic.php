<?php
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
 * @copyright    XOOPS Project http://sourceforge.net/projects/xoops/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package      xNews
 * @since        1.6.0
 * @author       XOOPS Development Team, hthouzard
 * @version      $Id $
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

require_once XOOPS_ROOT_PATH . '/class/xoopsstory.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
require_once XNI_MODULE_PATH . '/include/functions.php';

/**
 * Class xni_NewsTopic
 */
class xni_NewsTopic extends \XoopsTopic
{
    public $menu;
    public $topic_description;
    public $topic_frontpage;
    public $topic_rssurl;
    public $topic_color;

    /**
     * xni_NewsTopic constructor.
     * @param int    $topicid
     * @param string $subprefix
     */
    public function __construct($topicid = 0, $subprefix = '')
    {
        $this->db    = \XoopsDatabaseFactory::getDatabaseConnection();
        $this->table = $this->db->prefix($subprefix . 'topics');
        if (is_array($topicid)) {
            $this->makeTopic($topicid);
        } elseif (0 != $topicid) {
            $this->getTopic((int)$topicid);
        } else {
            $this->topic_id = $topicid;
        }
    }

    /**
     * @param int    $none
     * @param int    $seltopic
     * @param string $selname
     * @param string $onchange
     * @param bool   $checkRight
     * @param string $perm_type
     * @return null|string
     */
    public function MakeMyTopicSelBox($none = 0, $seltopic = -1, $selname = '', $onchange = '', $checkRight = false, $perm_type = 'xni_view')
    {
        $perms = '';
        if ($checkRight) {
            global $xoopsUser;
            /** @var \XoopsModuleHandler $moduleHandler */
            $moduleHandler    = xoops_getHandler('module');
            $newsModule       = $moduleHandler->getByDirname(XNI_MODULE_DIR_NAME);
            $groups           = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
            $grouppermHandler = xoops_getHandler('groupperm');
            $topics           = $grouppermHandler->getItemIds($perm_type, $groups, $newsModule->getVar('mid'));
            if (count($topics) > 0) {
                $topics = implode(',', $topics);
                $perms  = ' AND topic_id IN (' . $topics . ') ';
            } else {
                return null;
            }
        }

        if (-1 != $seltopic) {
            return $this->makeMySelBox('topic_title', 'topic_title', $seltopic, $none, $selname, $onchange, $perms);
        } elseif (!empty($this->topic_id)) {
            return $this->makeMySelBox('topic_title', 'topic_title', $this->topic_id, $none, $selname, $onchange, $perms);
        }

        return $this->makeMySelBox('topic_title', 'topic_title', 0, $none, $selname, $onchange, $perms);
    }

    /**
     * makes a nicely ordered selection box
     *
     * @param        $title
     * @param string $order
     * @param int    $preset_id is used to specify a preselected item
     * @param int    $none      set $none to 1 to add a option with value 0
     * @param string $sel_name
     * @param string $onchange
     * @param        $perms
     * @return string
     */
    public function makeMySelBox($title, $order, $preset_id, $none, $sel_name, $onchange, $perms)
    {
        $myts      = \MyTextSanitizer::getInstance();
        $outbuffer = '';
        $outbuffer = "<select name='" . $sel_name . "'";
        if ('' != $onchange) {
            $outbuffer .= " onchange='" . $onchange . "'";
        }
        $outbuffer .= ">\n";
        $sql       = 'SELECT topic_id, ' . $title . ' FROM ' . $this->table . ' WHERE (topic_pid=0)' . $perms;
        if ('' != $order) {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        if ($none) {
            $outbuffer .= "<option value='0'>----</option>\n";
        }
        while (list($catid, $name) = $this->db->fetchRow($result)) {
            $sel = '';
            if ($catid == $preset_id) {
                $sel = " selected='selected'";
            }
            $name      = $myts->displayTarea($name);
            $outbuffer .= "<option value='$catid'$sel>$name</option>\n";
            $sel       = '';
            $arr       = $this->getChildTreeArray($catid, $order, $perms);
            foreach ($arr as $option) {
                $option['prefix'] = str_replace('.', '--', $option['prefix']);
                $catpath          = $option['prefix'] . '&nbsp;' . $myts->displayTarea($option[$title]);

                if ($option['topic_id'] == $preset_id) {
                    $sel = " selected='selected'";
                }
                $outbuffer .= "<option value='" . $option['topic_id'] . "'$sel>$catpath</option>\n";
                $sel       = '';
            }
        }
        $outbuffer .= "</select>\n";

        return $outbuffer;
    }

    /**
     * @param int    $sel_id
     * @param string $order
     * @param string $perms
     * @param array  $parray
     * @param string $r_prefix
     * @return array
     */
    public function getChildTreeArray($sel_id = 0, $order = '', $perms = '', $parray = [], $r_prefix = '')
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE (topic_pid=' . $sel_id . ')' . $perms;
        if ('' != $order) {
            $sql .= " ORDER BY $order";
        }
        $result = $this->db->query($sql);
        $count  = $this->db->getRowsNum($result);
        if (0 == $count) {
            return $parray;
        }
        while (false !== ($row = $this->db->fetchArray($result))) {
            $row['prefix'] = $r_prefix . '.';
            array_push($parray, $row);
            $parray = $this->getChildTreeArray($row['topic_id'], $order, $perms, $parray, $row['prefix']);
        }

        return $parray;
    }

    /**
     * @param $var
     * @return mixed
     */
    public function getVar($var)
    {
        if (method_exists($this, $var)) {
            return call_user_func([$this, $var]);
        }

        return $this->$var;
    }

    /**
     * Get the total number of topics in the base
     * @param bool $checkRight
     * @return |null
     */
    public function getAllTopicsCount($checkRight = true)
    {
        $perms = '';
        if ($checkRight) {
            global $xoopsUser;
            /** @var \XoopsModuleHandler $moduleHandler */
            $moduleHandler    = xoops_getHandler('module');
            $newsModule       = $moduleHandler->getByDirname(XNI_MODULE_DIR_NAME);
            $groups           = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
            $grouppermHandler = xoops_getHandler('groupperm');
            $topics           = $grouppermHandler->getItemIds('xni_submit', $groups, $newsModule->getVar('mid'));
            if (count($topics) > 0) {
                $topics = implode(',', $topics);
                $perms  = ' WHERE topic_id IN (' . $topics . ') ';
            } else {
                return null;
            }
        }

        $sql   = 'SELECT count(topic_id) AS cpt FROM ' . $this->table . $perms;
        $array = $this->db->fetchArray($this->db->query($sql));

        return $array['cpt'];
    }

    /**
     * @param string $table_name
     * @param bool   $checkRight
     * @param string $permission
     * @return array
     */
    public function getAllTopics($table_name = 'nw_topics', $checkRight = true, $permission = 'xni_view')
    {
        $topics_arr = [];
        $db         = \XoopsDatabaseFactory::getDatabaseConnection();
        $table      = $db->prefix($table_name);
        $sql        = 'SELECT * FROM ' . $table;
        if ($checkRight) {
            $topics = xni_MygetItemIds($permission);
            if (0 == count($topics)) {
                return [];
            }
            $topics = implode(',', $topics);
            $sql    .= ' WHERE topic_id IN (' . $topics . ')';
        }
        $sql    .= ' ORDER BY topic_title';
        $result = $db->query($sql);
        while (false !== ($array = $db->fetchArray($result))) {
            $topic = new self();
            $topic->makeTopic($array);
            $topics_arr[$array['topic_id']] = $topic;
            unset($topic);
        }

        return $topics_arr;
    }

    /**
     * Returns the number of published news per topic
     */
    public function getnwCountByTopic()
    {
        $ret    = [];
        $sql    = 'SELECT count(storyid) AS cpt, topicid FROM ' . $this->db->prefix('xni_stories') . ' WHERE (published > 0 AND published <= ' . time() . ') AND (expired = 0 OR expired > ' . time() . ') GROUP BY topicid';
        $result = $this->db->query($sql);
        while (false !== ($row = $this->db->fetchArray($result))) {
            $ret[$row['topicid']] = $row['cpt'];
        }

        return $ret;
    }

    /**
     * Returns some stats about a topic
     * @param $topicid
     * @return array
     */
    public function getTopicMiniStats($topicid)
    {
        $ret          = [];
        $sql          = 'SELECT count(storyid) AS cpt1, sum(counter) AS cpt2 FROM ' . $this->db->prefix('xni_stories') . ' WHERE (topicid=' . $topicid . ') AND (published>0 AND published <= ' . time() . ') AND (expired = 0 OR expired > ' . time() . ')';
        $result       = $this->db->query($sql);
        $row          = $this->db->fetchArray($result);
        $ret['count'] = $row['cpt1'];
        $ret['reads'] = $row['cpt2'];

        return $ret;
    }

    /**
     * @param $value
     */
    public function setMenu($value)
    {
        $this->menu = $value;
    }

    /**
     * @param $value
     */
    public function setTopic_color($value)
    {
        $this->topic_color = $value;
    }

    /**
     * @param $topicid
     */
    public function getTopic($topicid)
    {
        $sql   = 'SELECT * FROM ' . $this->table . ' WHERE topic_id=' . $topicid . '';
        $array = $this->db->fetchArray($this->db->query($sql));
        $this->makeTopic($array);
    }

    /**
     * @param $array
     */
    public function makeTopic($array)
    {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @return bool
     */
    public function store()
    {
        $myts              = \MyTextSanitizer::getInstance();
        $title             = '';
        $imgurl            = '';
        $topic_description = $myts->censorString($this->topic_description);
        $topic_description = $myts->addSlashes($topic_description);
        $topic_rssurl      = $myts->addSlashes($this->topic_rssurl);
        $topic_color       = $myts->addSlashes($this->topic_color);

        if (isset($this->topic_title) && '' != $this->topic_title) {
            $title = $myts->addSlashes($this->topic_title);
        }
        if (isset($this->topic_imgurl) && '' != $this->topic_imgurl) {
            $imgurl = $myts->addSlashes($this->topic_imgurl);
        }
        if (!isset($this->topic_pid) || !is_numeric($this->topic_pid)) {
            $this->topic_pid = 0;
        }
        $topic_frontpage = (int)$this->topic_frontpage;
        $insert          = false;
        if (empty($this->topic_id)) {
            $insert         = true;
            $this->topic_id = $this->db->genId($this->table . '_topic_id_seq');
            $sql            = sprintf("INSERT INTO `%s` (topic_id, topic_pid, topic_imgurl, topic_title, menu, topic_description, topic_frontpage, topic_rssurl, topic_color) VALUES (%u, %u, '%s', '%s', %u, '%s', %d, '%s', '%s')", $this->table, (int)$this->topic_id, (int)$this->topic_pid, $imgurl,
                                      $title, (int)$this->menu, $topic_description, $topic_frontpage, $topic_rssurl, $topic_color);
        } else {
            $sql = sprintf("UPDATE `%s` SET topic_pid = %u, topic_imgurl = '%s', topic_title = '%s', menu=%d, topic_description='%s', topic_frontpage=%d, topic_rssurl='%s', topic_color='%s' WHERE topic_id = %u", $this->table, (int)$this->topic_pid, $imgurl, $title, (int)$this->menu,
                           $topic_description, $topic_frontpage, $topic_rssurl, $topic_color, (int)$this->topic_id);
        }
        if (!$result = $this->db->query($sql)) {
            // TODO: Replace with something else
            //ErrorHandler::show('0022');
            echo 'Error - 0022';
        } else {
            if ($insert) {
                $this->topic_id = $this->db->getInsertId();
            }
        }

        if (true === $this->use_permission) {
            $xt            = new \XoopsTree($this->table, 'topic_id', 'topic_pid');
            $parent_topics = $xt->getAllParentId($this->topic_id);
            if (!empty($this->m_groups) && is_array($this->m_groups)) {
                foreach ($this->m_groups as $m_g) {
                    $moderate_topics = \XoopsPerms::getPermitted($this->mid, 'ModInTopic', $m_g);
                    $add             = true;
                    // only grant this permission when the group has this permission in all parent topics of the created topic
                    foreach ($parent_topics as $p_topic) {
                        if (!in_array($p_topic, $moderate_topics)) {
                            $add = false;
                            continue;
                        }
                    }
                    if (true === $add) {
                        $xp = new \XoopsPerms();
                        $xp->setModuleId($this->mid);
                        $xp->setName('ModInTopic');
                        $xp->setItemId($this->topic_id);
                        $xp->store();
                        $xp->addGroup($m_g);
                    }
                }
            }
            if (!empty($this->s_groups) && is_array($this->s_groups)) {
                foreach ($this->s_groups as $s_g) {
                    $submit_topics = \XoopsPerms::getPermitted($this->mid, 'SubmitInTopic', $s_g);
                    $add           = true;
                    foreach ($parent_topics as $p_topic) {
                        if (!in_array($p_topic, $submit_topics)) {
                            $add = false;
                            continue;
                        }
                    }
                    if (true === $add) {
                        $xp = new \XoopsPerms();
                        $xp->setModuleId($this->mid);
                        $xp->setName('SubmitInTopic');
                        $xp->setItemId($this->topic_id);
                        $xp->store();
                        $xp->addGroup($s_g);
                    }
                }
            }
            if (!empty($this->r_groups) && is_array($this->r_groups)) {
                foreach ($this->s_groups as $r_g) {
                    $read_topics = \XoopsPerms::getPermitted($this->mid, 'ReadInTopic', $r_g);
                    $add         = true;
                    foreach ($parent_topics as $p_topic) {
                        if (!in_array($p_topic, $read_topics)) {
                            $add = false;
                            continue;
                        }
                    }
                    if (true === $add) {
                        $xp = new \XoopsPerms();
                        $xp->setModuleId($this->mid);
                        $xp->setName('ReadInTopic');
                        $xp->setItemId($this->topic_id);
                        $xp->store();
                        $xp->addGroup($r_g);
                    }
                }
            }
        }

        return true;
    }

    /**
     * @param $value
     */
    public function Settopic_rssurl($value)
    {
        $this->topic_rssurl = $value;
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function topic_rssurl($format = 'S')
    {
        $myts = \MyTextSanitizer::getInstance();
        switch ($format) {
            case 'S':
                $topic_rssurl = $myts->displayTarea($this->topic_rssurl);
                break;
            case 'P':
                $topic_rssurl = $myts->previewTarea($this->topic_rssurl);
                break;
            case 'F':
            case 'E':
                $topic_rssurl = $myts->htmlSpecialChars($this->topic_rssurl);
                break;
        }

        return $topic_rssurl;
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function topic_color($format = 'S')
    {
        $myts = \MyTextSanitizer::getInstance();
        switch ($format) {
            case 'S':
                $topic_color = $myts->displayTarea($this->topic_color);
                break;
            case 'P':
                $topic_color = $myts->previewTarea($this->topic_color);
                break;
            case 'F':
            case 'E':
                $topic_color = $myts->htmlSpecialChars($this->topic_color);
                break;
        }

        return $topic_color;
    }

    /**
     * @return mixed
     */
    public function menu()
    {
        return $this->menu;
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function topic_description($format = 'S')
    {
        $myts = \MyTextSanitizer::getInstance();
        switch ($format) {
            case 'S':
                $topic_description = $myts->displayTarea($this->topic_description, 1);
                break;
            case 'P':
                $topic_description = $myts->previewTarea($this->topic_description);
                break;
            case 'F':
            case 'E':
                $topic_description = $myts->htmlSpecialChars($myts->stripSlashesGPC($this->topic_description));
                break;
        }

        return $topic_description;
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function topic_imgurl($format = 'S')
    {
        if ('' == trim($this->topic_imgurl)) {
            $this->topic_imgurl = 'blank.png';
        }
        $myts = \MyTextSanitizer::getInstance();
        switch ($format) {
            case 'S':
                $imgurl = $myts->htmlSpecialChars($this->topic_imgurl);
                break;
            case 'E':
                $imgurl = $myts->htmlSpecialChars($this->topic_imgurl);
                break;
            case 'P':
                $imgurl = $myts->stripSlashesGPC($this->topic_imgurl);
                $imgurl = $myts->htmlSpecialChars($imgurl);
                break;
            case 'F':
                $imgurl = $myts->stripSlashesGPC($this->topic_imgurl);
                $imgurl = $myts->htmlSpecialChars($imgurl);
                break;
        }

        return $imgurl;
    }

    /**
     * @param $topic
     * @param $topicstitles
     * @return |null
     */
    public function getTopicTitleFromId($topic, &$topicstitles)
    {
        $myts = \MyTextSanitizer::getInstance();
        $sql  = 'SELECT topic_id, topic_title, topic_imgurl FROM ' . $this->table . ' WHERE ';
        if (!is_array($topic)) {
            $sql .= ' topic_id=' . (int)$topic;
        } else {
            if (count($topic) > 0) {
                $sql .= ' topic_id IN (' . implode(',', $topic) . ')';
            } else {
                return null;
            }
        }
        $result = $this->db->query($sql);
        while (false !== ($row = $this->db->fetchArray($result))) {
            $topicstitles[$row['topic_id']] = ['title' => $myts->displayTarea($row['topic_title']), 'picture' => XNI_TOPICS_FILES_URL . '/' . $row['topic_imgurl']];
        }

        return $topicstitles;
    }

    /**
     * @param bool $frontpage
     * @param bool $perms
     * @return array|string
     */
    public function &getTopicsList($frontpage = false, $perms = false)
    {
        $sql = 'SELECT topic_id, topic_pid, topic_title, topic_color FROM ' . $this->table . ' WHERE 1 ';
        if ($frontpage) {
            $sql .= ' AND topic_frontpage=1';
        }
        if ($perms) {
            $topicsids = [];
            $topicsids = xni_MygetItemIds();
            if (0 == count($topicsids)) {
                return '';
            }
            $topics = implode(',', $topicsids);
            $sql    .= ' AND topic_id IN (' . $topics . ')';
        }
        $result = $this->db->query($sql);
        $ret    = [];
        $myts   = \MyTextSanitizer::getInstance();
        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $ret[$myrow['topic_id']] = ['title' => $myts->displayTarea($myrow['topic_title']), 'pid' => $myrow['topic_pid'], 'color' => $myrow['topic_color']];
        }

        return $ret;
    }

    /**
     * @param $value
     */
    public function setTopicDescription($value)
    {
        $this->topic_description = $value;
    }

    /**
     * @return mixed
     */
    public function topic_frontpage()
    {
        return $this->topic_frontpage;
    }

    /**
     * @param $value
     */
    public function setTopicFrontpage($value)
    {
        $this->topic_frontpage = (int)$value;
    }

    /**
     * Set a variabe in on of the request variables
     *
     * @access    public
     * @param  string $name      Name
     * @param  string $value     Value
     * @param  string $hash      Hash
     * @param  bool   $overwrite Boolean
     * @return string  Previous value
     */
    public function setVar($name, $value = null, $hash = 'method', $overwrite = true)
    {
        //If overwrite is true, makes sure the variable hasn't been set yet
        if (!$overwrite && array_key_exists($name, $_REQUEST)) {
            return $_REQUEST[$name];
        }

        // Get the request hash value
        $hash = mb_strtoupper($hash);
        if ('METHOD' === $hash) {
            $hash = mb_strtoupper($_SERVER['REQUEST_METHOD']);
        }

        $previous = array_key_exists($name, $_REQUEST) ? $_REQUEST[$name] : null;

        switch ($hash) {
            case 'GET':
                $_GET[$name]     = $value;
                $_REQUEST[$name] = $value;
                break;
            case 'POST':
                $_POST[$name]    = $value;
                $_REQUEST[$name] = $value;
                break;
            case 'COOKIE':
                $_COOKIE[$name]  = $value;
                $_REQUEST[$name] = $value;
                break;
            case 'FILES':
                $_FILES[$name] = $value;
                break;
            case 'ENV':
                $_ENV['name'] = $value;
                break;
            case 'SERVER':
                $_SERVER['name'] = $value;
                break;
        }

        return $previous;
    }
}
