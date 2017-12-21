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
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author     XOOPS Development Team
 */

use WideImage\WideImage;

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

require_once XOOPS_ROOT_PATH . '/include/comment_constants.php';
require_once XNEWS_MODULE_PATH . '/include/functions.php';
require_once XNEWS_MODULE_PATH . '/class/deprecate/xnewsstory.php';

/**
 * Class nw_NewsStory
 */
class nw_NewsStory extends XnewsDeprecateStory
{
    public $xnews;
    public $db;
    public $newstopic; // XnewsDeprecateTopic object
    public $rating; // news rating
    public $votes; // Number of votes
    public $description; // META, desciption
    public $keywords; // META, keywords
    public $picture;
    public $topic_imgurl;
    public $topic_title;
    public $tags;
    //var $imagerows;
    //var $pdfrows;

    /**
     * Constructor
     * @param int $storyid
     */
    public function __construct($storyid = -1)
    {
        $this->xnews       = XnewsXnews::getInstance();
        $this->db          = XoopsDatabaseFactory::getDatabaseConnection();
        $this->table       = $this->db->prefix('nw_stories');
        $this->topicstable = $this->db->prefix('nw_topics');
        if (is_array($storyid)) {
            $this->makeStory($storyid);
        } elseif ($storyid != -1) {
            $this->getStory((int)$storyid);
        }
    }

    /**
     * Returns the number of stories published before a date
     * @param        $timestamp
     * @param        $expired
     * @param string $topicsList
     * @return
     */
    public function GetCountStoriesPublishedBefore($timestamp, $expired, $topicsList = '')
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $sql = 'SELECT count(*) as cpt';
        $sql .= " FROM {$this->db->prefix('nw_stories')}";
        $sql .= ' WHERE published <= ' . $timestamp;
        if ($expired) {
            $sql .= ' AND (expired > 0 AND expired <= ' . time() . ')';
        }
        if (strlen(trim($topicsList)) > 0) {
            $sql .= " AND topicid IN ({$topicsList})";
        }
        $result = $this->db->query($sql);
        list($count) = $this->db->fetchRow($result);

        return $count;
    }

    /**
     * Load the specified story from the database
     * @param $storyid
     */
    public function getStory($storyid)
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $sql   = 'SELECT s.*, t.*';
        $sql   .= " FROM {$this->table} s, {$this->db->prefix('nw_topics')} t";
        $sql   .= ' WHERE (storyid = ' . (int)$storyid . ') AND (s.topicid = t.topic_id)';
        $array = $this->db->fetchArray($this->db->query($sql));
        $this->makeStory($array);
    }

    /**
     * Delete stories that were published before a given date
     * @param        $timestamp
     * @param        $expired
     * @param string $topicsList
     * @return bool
     */
    public function DeleteBeforeDate($timestamp, $expired, $topicsList = '')
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $sql = 'SELECT storyid';
        $sql .= " FROM {$this->db->prefix('nw_stories')}";
        $sql .= ' WHERE published <= ' . $timestamp;
        if ($expired) {
            $sql .= ' (AND expired>0 AND expired<=' . time() . ')';
        }
        if (strlen(trim($topicsList)) > 0) {
            $sql .= ' AND topicid IN (' . $topicsList . ')';
        }
        $result = $this->db->query($sql);
        while ($myrow = $this->db->fetchArray($result)) {
            // Delete comments
            xoops_comment_delete($this->xnews->getModule()->getVar('mid'), $myrow['storyid']);
            // Delete notifications
            xoops_notification_deletebyitem($this->xnews->getModule()->getVar('mid'), 'story', $myrow['storyid']);
            // Delete votes
            $this->db->queryF("DELETE FROM {$this->db->prefix('nw_stories_votedata')} WHERE storyid = " . $myrow['storyid']);
            // Remove files and records related to the files
            $result2 = $this->db->query("SELECT * FROM {$this->db->prefix('nw_stories_files')} WHERE storyid = " . $myrow['storyid']);
            while ($myrow2 = $this->db->fetchArray($result2)) {
                $name = XOOPS_ROOT_PATH . '/uploads/' . $myrow2['downloadname'];
                if (file_exists($name)) {
                    unlink($name);
                }
                $this->db->query("DELETE FROM {$this->db->prefix('nw_stories_files')} WHERE fileid = " . $myrow2['fileid']);
            }
            $this->db->queryF("DELETE FROM {$this->db->prefix('nw_stories')} WHERE storyid = " . $myrow['storyid']); // Delete the story
        }

        return true;
    }

    /**
     * @param      $storyid
     * @param bool $next
     * @param bool $checkRight
     * @return array|null
     */
    public function _searchPreviousOrNextArticle($storyid, $next = true, $checkRight = false)
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $ret     = [];
        $storyid = (int)$storyid;
        if ($next) {
            $sql     = 'SELECT storyid, title';
            $sql     .= " FROM {$this->db->prefix('nw_stories')}";
            $sql     .= ' WHERE (published > 0 AND published <= ' . time() . ') AND (expired = 0 OR expired > ' . time() . ') AND storyid > ' . $storyid;
            $orderBy = ' ORDER BY storyid ASC';
        } else {
            $sql     = 'SELECT storyid, title';
            $sql     .= " FROM {$this->db->prefix('nw_stories')}";
            $sql     .= ' WHERE (published > 0 AND published <= ' . time() . ') AND (expired = 0 OR expired > ' . time() . ') AND storyid < ' . $storyid;
            $orderBy = ' ORDER BY storyid DESC';
        }
        if ($checkRight) {
            $topics = nw_MygetItemIds('nw_view');
            if (count($topics) > 0) {
                $sql .= ' AND topicid IN (' . implode(',', $topics) . ')';
            } else {
                return null;
            }
        }
        $sql .= $orderBy;

        $result = $this->db->query($sql, 1);
        if ($result) {
            $myts = \MyTextSanitizer::getInstance();
            while ($row = $this->db->fetchArray($result)) {
                $ret = ['storyid' => $row['storyid'], 'title' => $myts->htmlSpecialChars($row['title'])];
            }
        }

        return $ret;
    }

    /**
     * @param      $storyid
     * @param bool $checkRight
     * @return array|null
     */
    public function getNextArticle($storyid, $checkRight = false)
    {
        return $this->_searchPreviousOrNextArticle($storyid, true, $checkRight);
    }

    /**
     * @param      $storyid
     * @param bool $checkRight
     * @return array|null
     */
    public function getPreviousArticle($storyid, $checkRight = false)
    {
        return $this->_searchPreviousOrNextArticle($storyid, false, $checkRight);
    }

    /**
     * Returns published stories according to some options
     * @param int    $limit
     * @param int    $start
     * @param bool   $checkRight
     * @param int    $topic
     * @param int    $ihome
     * @param bool   $asObject
     * @param string $order
     * @param bool   $topic_frontpage
     * @return array
     */
    public function getAllPublished($limit = 0, $start = 0, $checkRight = false, $topic = 0, $ihome = 0, $asObject = true, $order = 'published', $topic_frontpage = false)
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $ret = [];
        $sql = 'SELECT s.*, t.*';
        $sql .= " FROM {$this->db->prefix('nw_stories')} s, {$this->db->prefix('nw_topics')} t";
        $sql .= ' WHERE (s.published > 0 AND s.published <= ' . time() . ') AND (s.expired = 0 OR s.expired > ' . time() . ') AND (s.topicid=t.topic_id) ';
        if (0 != $topic) {
            if (!is_array($topic)) {
                if ($checkRight) {
                    $topics = nw_MygetItemIds('nw_view');
                    if (!in_array($topic, $topics)) {
                        return null;
                    } else {
                        $sql .= ' AND s.topicid=' . (int)$topic . ' AND (s.ihome=1 OR s.ihome=0)';
                    }
                } else {
                    $sql .= ' AND s.topicid=' . (int)$topic . ' AND (s.ihome=1 OR s.ihome=0)';
                }
            } else {
                if ($checkRight) {
                    $topics = nw_MygetItemIds('nw_view');
                    $topic  = array_intersect($topic, $topics);
                }
                if (count($topic) > 0) {
                    $sql .= ' AND s.topicid IN (' . implode(',', $topic) . ')';
                } else {
                    return null;
                }
            }
        } else {
            if ($checkRight) {
                $topics = nw_MygetItemIds('nw_view');
                if (count($topics) > 0) {
                    $sql .= ' AND s.topicid IN (' . implode(',', $topics) . ')';
                } else {
                    return null;
                }
            }
            if (0 == (int)$ihome) {
                $sql .= ' AND s.ihome=0';
            }
        }
        if ($topic_frontpage) {
            $sql .= ' AND t.topic_frontpage=1';
        }
        $sql    .= " ORDER BY s.$order DESC";
        $result = $this->db->query($sql, (int)$limit, (int)$start);

        while ($myrow = $this->db->fetchArray($result)) {
            if ($asObject) {
                $ret[] = new nw_NewsStory($myrow);
            } else {
                $ret[$myrow['storyid']] = $myts->htmlSpecialChars($myrow['title']);
            }
        }

        return $ret;
    }

    /**
     * Retourne la liste des articles aux archives (pour une période donnée)
     * @param        $publish_start
     * @param        $publish_end
     * @param bool   $checkRight
     * @param bool   $asObject
     * @param string $order
     * @return array|null
     */
    public function getArchive($publish_start, $publish_end, $checkRight = false, $asObject = true, $order = 'published')
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $ret = [];
        $sql = 'SELECT s.*, t.*';
        $sql .= " FROM {$this->db->prefix('nw_stories')} s, {$this->db->prefix('nw_topics')} t";
        $sql .= " WHERE (s.topicid = t.topic_id) AND (s.published > {$publish_start} AND s.published <= {$publish_end}) AND (expired = 0 OR expired > " . time() . ') ';

        if ($checkRight) {
            $topics = nw_MygetItemIds('nw_view');
            if (count($topics) > 0) {
                $sql .= ' AND topicid IN (' . implode(',', $topics) . ')';
            } else {
                return null;
            }
        }
        $sql    .= " ORDER BY $order DESC";
        $result = $this->db->query($sql);
        while ($myrow = $this->db->fetchArray($result)) {
            if ($asObject) {
                $ret[] = new nw_NewsStory($myrow);
            } else {
                $ret[$myrow['storyid']] = $myts->htmlSpecialChars($myrow['title']);
            }
        }

        return $ret;
    }

    /**
     * Get the today's most readed article
     *
     * @param int     $limit      records limit
     * @param int     $start      starting record
     * @param boolean $checkRight Do we need to check permissions (by topics) ?
     * @param int     $topic      limit the job to one topic
     * @param int     $ihome      Limit to articles published in home page only ?
     * @param boolean $asObject   Do we have to return an array of objects or a simple array ?
     * @param string  $order      Fields to sort on
     * @return array
     */
    public function getBigStory($limit = 0, $start = 0, $checkRight = false, $topic = 0, $ihome = 0, $asObject = true, $order = 'counter')
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $ret   = [];
        $tdate = mktime(0, 0, 0, date('n'), date('j'), date('Y'));
        $sql   = 'SELECT s.*, t.*';
        $sql   .= " FROM {$this->db->prefix('nw_stories')} s, {$this->db->prefix('nw_topics')} t";
        $sql   .= " WHERE (s.topicid=t.topic_id) AND (published > {$tdate} AND published < " . time() . ') AND (expired > ' . time() . ' OR expired = 0) ';

        if (0 != (int)$topic) {
            if (!is_array($topic)) {
                $sql .= ' AND topicid=' . (int)$topic . ' AND (ihome=1 OR ihome=0)';
            } else {
                if (count($topic) > 0) {
                    $sql .= ' AND topicid IN (' . implode(',', $topic) . ')';
                } else {
                    return null;
                }
            }
        } else {
            if ($checkRight) {
                $topics = nw_MygetItemIds('nw_view');
                if (count($topics) > 0) {
                    $sql .= ' AND topicid IN (' . implode(',', $topics) . ')';
                } else {
                    return null;
                }
            }
            if (0 == (int)$ihome) {
                $sql .= ' AND ihome=0';
            }
        }
        $sql    .= " ORDER BY $order DESC";
        $result = $this->db->query($sql, (int)$limit, (int)$start);
        while ($myrow = $this->db->fetchArray($result)) {
            if ($asObject) {
                $ret[] = new nw_NewsStory($myrow);
            } else {
                $ret[$myrow['storyid']] = $myts->htmlSpecialChars($myrow['title']);
            }
        }

        return $ret;
        // DNPROSSI SEO
        $seo_enabled = $this->xnews->getConfig('seo_enable');
        if (0 != $seo_enabled) {
            $xoopsTpl->assign('urlrewrite', true);
        } else {
            $xoopsTpl->assign('urlrewrite', false);
        }
    }

    /**
     * Get all articles published by an author
     *
     * @param int     $uid        author's id
     * @param boolean $checkRight whether to check the user's rights to topics
     * @param bool    $asObject
     * @return array
     */
    public function getAllPublishedByAuthor($uid, $checkRight = false, $asObject = true)
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $ret = [];
        $sql = "SELECT {$this->db->prefix('nw_stories')}.*, {$this->db->prefix('nw_topics')}.topic_title, {$this->db->prefix('nw_topics')}.topic_color";
        $sql .= " FROM {$this->db->prefix('nw_stories')}, {$this->db->prefix('nw_topics')}";
        $sql .= " WHERE ({$this->db->prefix('nw_stories')}.topicid = {$this->db->prefix('nw_topics')}.topic_id) AND (published > 0 AND published <= " . time() . ') AND (expired = 0 OR expired > ' . time() . ')';
        $sql .= ' AND uid = ' . (int)$uid;
        if ($checkRight) {
            $topics = nw_MygetItemIds('nw_view');
            $topics = implode(',', $topics);
            if ('' != xoops_trim($topics)) {
                $sql .= " AND topicid IN ({$topics})";
            }
        }
        $sql    .= " ORDER BY {$this->db->prefix('nw_topics')}.topic_title ASC, {$this->db->prefix('nw_stories')}.published DESC";
        $result = $this->db->query($sql);
        while ($myrow = $this->db->fetchArray($result)) {
            if ($asObject) {
                $ret[] = new nw_NewsStory($myrow);
            } else {
                if ($myrow['nohtml']) {
                    $html = 0;
                } else {
                    $html = 1;
                }
                if ($myrow['nosmiley']) {
                    $smiley = 0;
                } else {
                    $smiley = 1;
                }
                //DNPROSSI - dobr
                if ($myrow['dobr']) {
                    $dobr = 0;
                } else {
                    $dobr = 1;
                }
                $ret[$myrow['storyid']] = [
                    'title'       => $myts->displayTarea($myrow['title'], $html, $smiley, 1),
                    'topicid'     => (int)$myrow['topicid'],
                    'storyid'     => (int)$myrow['storyid'],
                    'hometext'    => $myts->displayTarea($myrow['hometext'], $html, $smiley, 1, 0, $dobr),
                    'counter'     => (int)$myrow['counter'],
                    'created'     => (int)$myrow['created'],
                    'topic_title' => $myts->displayTarea($myrow['topic_title'], $html, $smiley, 1),
                    'topic_color' => $myts->displayTarea($myrow['topic_color']),
                    'published'   => (int)$myrow['published'],
                    'rating'      => (float )$myrow['rating'],
                    'votes'       => (int)$myrow['votes']
                ];
            }
        }

        return $ret;
    }

    /**
     * Get all expired stories
     * @param int  $limit
     * @param int  $start
     * @param int  $topic
     * @param int  $ihome
     * @param bool $asObject
     * @return array
     */
    public function getAllExpired($limit = 0, $start = 0, $topic = 0, $ihome = 0, $asObject = true)
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $ret = [];
        $sql = 'SELECT *';
        $sql .= " FROM {$this->db->prefix('nw_stories')}";
        $sql .= ' WHERE expired <= ' . time() . ' AND expired > 0';
        if (!empty($topic)) {
            $sql .= ' AND topicid = ' . (int)$topic . ' AND (ihome = 1 OR ihome = 0)';
        } else {
            if (0 == (int)$ihome) {
                $sql .= ' AND ihome=0';
            }
        }
        $sql    .= ' ORDER BY expired DESC';
        $result = $this->db->query($sql, (int)$limit, (int)$start);
        while ($myrow = $this->db->fetchArray($result)) {
            if ($asObject) {
                $ret[] = new nw_NewsStory($myrow);
            } else {
                $ret[$myrow['storyid']] = $myts->htmlSpecialChars($myrow['title']);
            }
        }

        return $ret;
    }

    /**
     * Returns an array of object containing all the news to be automatically published.
     * @param int  $limit
     * @param bool $asObject
     * @param int  $start
     * @return array
     */
    public function getAllAutoStory($limit = 0, $asObject = true, $start = 0)
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $ret    = [];
        $sql    = 'SELECT *';
        $sql    .= " FROM {$this->db->prefix('nw_stories')}";
        $sql    .= ' WHERE published > ' . time() . ' ORDER BY published ASC';
        $result = $this->db->query($sql, (int)$limit, (int)$start);
        while ($myrow = $this->db->fetchArray($result)) {
            if ($asObject) {
                $ret[] = new nw_NewsStory($myrow);
            } else {
                $ret[$myrow['storyid']] = $myts->htmlSpecialChars($myrow['title']);
            }
        }

        return $ret;
    }

    /**
     * Get all submitted stories awaiting approval
     *
     * @param int     $limit      Denotes where to start the query
     * @param boolean $asObject   true will returns the stories as an array of objects, false will return storyid => title
     * @param boolean $checkRight whether to check the user's rights to topics
     * @param int     $start
     * @return array
     */
    public function getAllSubmitted($limit = 0, $asObject = true, $checkRight = false, $start = 0)
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $ret      = [];
        $criteria = new CriteriaCompo(new Criteria('published', 0));
        if ($checkRight) {
            if (!is_object($GLOBALS['xoopsUser'])) {
                return $ret;
            }
            $allowedtopics = nw_MygetItemIds('nw_approve');
            $criteria2     = new CriteriaCompo();
            foreach ($allowedtopics as $key => $topicid) {
                $criteria2->add(new Criteria('topicid', $topicid), 'OR');
            }
            $criteria->add($criteria2);
        }
        $sql    = 'SELECT s.*, t.*';
        $sql    .= " FROM {$this->db->prefix('nw_stories')} s, {$this->db->prefix('nw_topics')} t ";
        $sql    .= " {$criteria->renderWhere()} AND (s.topicid = t.topic_id) ORDER BY created DESC";
        $result = $this->db->query($sql, (int)$limit, (int)$start);
        while ($myrow = $this->db->fetchArray($result)) {
            if ($asObject) {
                $ret[] = new nw_NewsStory($myrow);
            } else {
                $ret[$myrow['storyid']] = $myts->htmlSpecialChars($myrow['title']);
            }
        }

        return $ret;
    }

    /**
     * Used in the module's admin to know the number of expired, automated or pubilshed news
     *
     * @param int  $storyType
     *                               1 = Expired,
     *                               2 = Automated
     *                               3 = New submissions
     *                               4 = Last published stories
     * @param bool $checkRight       verify permissions or not ?
     * @return int
     */
    public function getAllStoriesCount($storyType = 1, $checkRight = false)
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $sql = 'SELECT count(*) as cpt';
        $sql .= " FROM {$this->db->prefix('nw_stories')}";
        $sql .= ' WHERE ';
        switch ($storyType) {
            case 1: // Expired
                $sql .= '(expired <= ' . time() . ' AND expired >0)';
                break;
            case 2: // Automated
                $sql .= '(published > ' . time() . ')';
                break;
            case 3: // New submissions
                $sql .= '(published = 0)';
                break;
            case 4: // Last published stories
                $sql .= '(published > 0 AND published <= ' . time() . ') AND (expired = 0 OR expired > ' . time() . ')';
                break;
        }
        if ($checkRight) {
            $topics = nw_MygetItemIds('nw_view');
            if (count($topics) > 0) {
                $sql .= ' AND topicid IN (' . implode(',', $topics) . ')';
            } else {
                return 0;
            }
        }
        $result = $this->db->query($sql);
        $myrow  = $this->db->fetchArray($result);

        return $myrow['cpt'];
    }

    /**
     * Get a list of stories (as objects) related to a specific topic
     * @param     $topicid
     * @param int $limit
     * @return array
     */
    public function getByTopic($topicid, $limit = 0)
    {
        $ret    = [];
        $sql    = 'SELECT *';
        $sql    .= " FROM {$this->db->prefix('nw_stories')}";
        $sql    .= 'WHERE topicid = ' . (int)$topicid . ' ORDER BY published DESC';
        $result = $this->db->query($sql, (int)$limit, 0);
        while ($myrow = $this->db->fetchArray($result)) {
            $ret[] = new nw_NewsStory($myrow);
        }

        return $ret;
    }

    /**
     * Count the number of news published for a specific topic
     * @param int  $topicid
     * @param bool $checkRight
     * @return null
     */
    public function countPublishedByTopic($topicid = 0, $checkRight = false)
    {
        $sql = 'SELECT COUNT(*)';
        $sql .= " FROM {$this->db->prefix('nw_stories')}";
        $sql .= ' WHERE published > 0 AND published <= ' . time() . ' AND (expired = 0 OR expired > ' . time() . ')';
        if (!empty($topicid)) {
            $sql .= ' AND topicid = ' . (int)$topicid;
        } else {
            $sql .= ' AND ihome = 0';
            if ($checkRight) {
                $topics = nw_MygetItemIds('nw_view');
                if (count($topics) > 0) {
                    $sql .= ' AND topicid IN (' . implode(',', $topics) . ')';
                } else {
                    return null;
                }
            }
        }
        $result = $this->db->query($sql);
        list($count) = $this->db->fetchRow($result);

        return $count;
    }

    /**
     * Internal function
     */
    public function adminlink()
    {
        //<img src='" . XNEWS_MODULE_URL . "/assets/images/leftarrow22.png' border='0' alt='" . _MA_NW_PREVIOUS_ARTICLE . "'></a>";
        $ret2 = "<a href='" . XNEWS_MODULE_URL . '/submit.php?op=edit&amp;storyid=' . $this->storyid() . "' title='" . _EDIT . "'>";
        $ret2 .= "<img src='" . XNEWS_MODULE_URL . "/assets/images/edit_block.png' width='22px' height='22px' border='0' alt='" . _EDIT . "'></a>&nbsp;&nbsp;&nbsp;";
        $ret2 .= "<a href='" . XNEWS_MODULE_URL . '/admin/index.php?op=delete&amp;storyid=' . $this->storyid() . "' title='" . _DELETE . "'>";
        $ret2 .= "<img src='" . XNEWS_MODULE_URL . "/assets/images/delete_block.png' width='24px' height='24px' border='0' alt='" . _DELETE . "'></a>&nbsp;&nbsp;&nbsp;";

        //$ret = "&nbsp;[ <a href='" . XNEWS_MODULE_URL . "/submit.php?op=edit&amp;storyid=".$this->storyid()."'>"._EDIT."</a> | <a href='".XNEWS_MODULE_URL . "/admin/index.php?op=delete&amp;storyid=".$this->storyid()."'>"._DELETE."</a> ]&nbsp;";
        return $ret2;
    }

    /**
     * Get the topic image url
     * @param string $format
     * @return
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
     * @param string $format
     * @return mixed
     */
    public function topic_title($format = 'S')
    {
        $myts = \MyTextSanitizer::getInstance();
        switch ($format) {
            case 'S':
                $title = $myts->htmlSpecialChars($this->topic_title);
                break;
            case 'E':
                $title = $myts->htmlSpecialChars($this->topic_title);
                break;
            case 'P':
                $title = $myts->stripSlashesGPC($this->topic_title);
                $title = $myts->htmlSpecialChars($title);
                break;
            case 'F':
                $title = $myts->stripSlashesGPC($this->topic_title);
                $title = $myts->htmlSpecialChars($title);
                break;
        }

        return $title;
    }

    //DNPROSSI - Added picture substitute for topic images with article image

    /**
     * @return string
     */
    public function imglink()
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $topic_display = $this->xnews->getConfig('topicdisplay');
        //DNPROSSI SEO
        $seo_enabled = $this->xnews->getConfig('seo_enable');
        $ret         = '';
        $margin      = '';
        if ('left' === $this->topicalign()) {
            $margin = "style='padding-right: 8px;'";
        } else {
            $margin = "style='padding-left: 8px; padding-right: 5px'";
        }

        if ('' == xoops_trim($this->picture())) {
            if ('' != $this->topic_imgurl() && file_exists(XNEWS_TOPICS_FILES_PATH . '/' . $this->topic_imgurl())) {
                if (1 == $topic_display) {
                    //DNPROSSI SEO
                    $cat_path = '';
                    if (0 != $seo_enabled) {
                        $cat_path = nw_remove_accents($this->topic_title());
                    }
                    $ret = "<a href='" . nw_seo_UrlGenerator(_MA_NW_SEO_TOPICS, $this->topicid(), $cat_path) . "'>";
                    $ret .= "<img src='" . XNEWS_TOPICS_FILES_URL . '/' . $this->topic_imgurl() . "' alt='";
                    $ret .= $this->topic_title() . "' hspace='10' vspace='10' align='";
                    $ret .= $this->topicalign() . "'" . $margin . '></a>';
                } else {
                    $ret = "<img src='" . XNEWS_TOPICS_FILES_URL . '/' . $this->topic_imgurl() . "' alt='" . $this->topic_title() . "' hspace='10' vspace='10' align='" . $this->topicalign() . "'" . $margin . '>';
                }
            }
        } else {
            if (1 == $topic_display) {
                //DNPROSSI SEO
                $cat_path = '';
                if (0 != $seo_enabled) {
                    $cat_path = nw_remove_accents($this->topic_title());
                }
                $ret = "<a href='" . nw_seo_UrlGenerator(_MA_NW_SEO_TOPICS, $this->topicid(), $cat_path) . "'>";
                $ret .= "<img src='" . XNEWS_TOPICS_FILES_URL . '/' . $this->picture() . "' alt='";
                $ret .= $this->topic_title() . "' hspace='10' vspace='10' align='";
                $ret .= $this->topicalign() . "'" . $margin . '></a>';
            } else {
                $ret = "<img src='" . XNEWS_TOPICS_FILES_URL . '/' . $this->picture() . "' alt='" . $this->topic_title() . "' hspace='10' vspace='10' align='" . $this->topicalign() . "'" . $margin . '>';
            }
        }

        return $ret;
    }

    /**
     * @return string
     */
    public function storylink()
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $seo_enabled = $this->xnews->getConfig('seo_enable');
        $ret         = '';
        $story_path  = '';
        if (0 != $seo_enabled) {
            $story_path = nw_remove_accents($this->title());
        }
        $ret = "<a href='" . nw_seo_UrlGenerator(_MA_NW_SEO_ARTICLES, $this->storyid(), $story_path) . "'>" . $this->title() . '</a>';

        return $ret;
    }

    /**
     * @return mixed
     */
    public function dobr()
    {
        return $this->dobr;
    }

    /**
     * @param int $value
     */
    public function setDobr($value = 0)
    {
        $this->dobr = $value;
    }

    /**
     * @return string
     */
    public function textlink()
    {
        $topic_display = $this->xnews->getConfig('topicdisplay');
        //DNPROSSI SEO
        $seo_enabled = $this->xnews->getConfig('seo_enable');
        $ret         = '';
        $cat_path    = '';
        if (1 == $topic_display) {
            if (0 != $seo_enabled) {
                $cat_path = nw_remove_accents($this->topic_title());
            }
            $ret = "<a href='" . nw_seo_UrlGenerator(_MA_NW_SEO_TOPICS, $this->topicid(), $cat_path) . "'>" . $this->topic_title() . '</a>';
        }

        return $ret;
    }

    /**
     * Function used to prepare an article to be showed
     * @param $filescount
     * @return array
     */
    public function prepare2show($filescount)
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $infotips = $this->xnews->getConfig('infotips');
        //DNPROSSI SEO
        $seo_enabled          = $this->xnews->getConfig('seo_enable');
        $story                = [];
        $story['id']          = $this->storyid();
        $story['poster']      = $this->uname();
        $story['author_name'] = $this->uname();
        $story['author_uid']  = $this->uid();
        if (false !== $story['poster']) {
            $story['poster'] = "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $this->uid() . "'>" . $story['poster'] . '</a>';
        } else {
            if (3 != $this->xnews->getConfig('displayname')) {
                $story['poster'] = $GLOBALS['xoopsConfig']['anonymous'];
            }
        }
        if ($this->xnews->getConfig('ratenews')) {
            $story['rating'] = number_format($this->rating(), 2);
            if (1 == $this->votes) {
                $story['votes'] = _MA_NW_ONEVOTE;
            } else {
                $story['votes'] = sprintf(_MA_NW_NUMVOTES, $this->votes);
            }
        }
        $story['posttimestamp']     = $this->published();
        $story['posttime']          = formatTimestamp($story['posttimestamp'], $this->xnews->getConfig('dateformat'));
        $story['topic_description'] = $myts->displayTarea($this->topic_description);

        $auto_summary = '';
        $tmp          = '';
        $auto_summary = $this->auto_summary($this->bodytext(), $tmp);

        $story['text'] = $this->hometext();
        $story['text'] = str_replace('[summary]', $auto_summary, $story['text']);

        $introcount = strlen($story['text']);
        $fullcount  = strlen($this->bodytext());
        $totalcount = $introcount + $fullcount;

        $morelink = '';
        if ($fullcount > 1) {
            $story_path = '';
            //DNPROSSI SEO
            if (0 != $seo_enabled) {
                $story_path = nw_remove_accents($this->title());
            }
            $morelink .= "<a href='" . nw_seo_UrlGenerator(_MA_NW_SEO_ARTICLES, $this->storyid(), $story_path) . "'>";
            $morelink .= _MA_NW_READMORE . '</a>';
            //$morelink .= " | ".sprintf(_MA_NW_BYTESMORE, $totalcount);
            if (XOOPS_COMMENT_APPROVENONE != $this->xnews->getConfig('com_rule')) {
                $morelink .= ' | ';
            }
        }
        if (XOOPS_COMMENT_APPROVENONE != $this->xnews->getConfig('com_rule')) {
            $ccount     = $this->comments();
            $story_path = '';
            //DNPROSSI SEO
            if (0 != $seo_enabled) {
                $story_path = nw_remove_accents($this->title());
            }
            if (0 == $ccount) {
                $morelink .= _MA_NW_NO_COMMENT;
            } else {
                $morelink .= "<a href='" . nw_seo_UrlGenerator(_MA_NW_SEO_ARTICLES, $this->storyid(), $story_path);
                if (1 == $ccount) {
                    $morelink .= "'>" . _MA_NW_ONECOMMENT . '</a>';
                } else {
                    $morelink .= "'>";
                    $morelink .= sprintf(_MA_NW_NUMCOMMENTS, $ccount);
                    $morelink .= '</a>';
                }
            }
        }
        $story['morelink']  = $morelink;
        $story['adminlink'] = '';

        $approveprivilege = 0;
        if (nw_is_admin_group()) {
            $approveprivilege = 1;
        }

        if (1 == $this->xnews->getConfig('authoredit') && (is_object($GLOBALS['xoopsUser']) && $GLOBALS['xoopsUser']->getVar('uid') == $this->uid())) {
            $approveprivilege = 1;
        }
        if ($approveprivilege) {
            $story['adminlink'] = $this->adminlink();
        }
        $story['mail_link'] = 'mailto:?subject=' . sprintf(_MA_NW_INTARTICLE, $GLOBALS['xoopsConfig']['sitename']) . '&amp;body=' . sprintf(_MA_NW_INTARTFOUND, $GLOBALS['xoopsConfig']['sitename']) . ':  ' . XNEWS_MODULE_URL . '/article.php?storyid=' . $this->storyid();
        $story['imglink']   = '';
        $story['align']     = '';
        if ($this->topicdisplay()) {
            $story['imglink'] = $this->imglink();
            $story['align']   = $this->topicalign();
        }
        if ($infotips > 0) {
            $story['infotips'] = ' title="' . nw_make_infotips($this->hometext()) . '"';
        } else {
            $story['infotips'] = '';
        }

        //DNPROSSI SEO
        $story_path = '';
        if (0 != $seo_enabled) {
            $story_path = nw_remove_accents($this->title());
        }
        $story['title'] = "<a href='" . nw_seo_UrlGenerator(_MA_NW_SEO_ARTICLES, $this->storyid(), $story_path) . "'>" . $this->title() . '</a>';
        $story['hits']  = $this->counter();
        if ($filescount > 0) {
            $story['files_attached'] = true;
            $story['attached_link']  = "<a href='" . XNEWS_MODULE_URL . "/article.php?storyid={$this->storyid()}' title='" . _MA_NW_ATTACHEDLIB . "'><img src='" . XNEWS_MODULE_URL . "/assets/images/attach.png' title='" . _MA_NW_ATTACHEDLIB . "'></a>";
        } else {
            $story['files_attached'] = false;
            $story['attached_link']  = '';
        }

        return $story;
    }

    /**
     * Returns the user's name of the current story according to the module's option "displayname"
     * @param int $uid
     * @return string
     */
    public function uname($uid = 0)
    {
        static $tblusers = [];
        $option = -1;
        if (0 == $uid) {
            $uid = $this->uid();
        }
        if (is_array($tblusers) && array_key_exists($uid, $tblusers)) {
            return $tblusers[$uid];
        }
        $option = $this->xnews->getConfig('displayname');
        if (!$option) {
            $option = 1;
        }

        switch ($option) {
            case 1: // Username
                $tblusers[$uid] = XoopsUser::getUnameFromId($uid);

                return $tblusers[$uid];

            case 2: // Display full name (if it is not empty)
                $memberHandler = xoops_getHandler('member');
                $thisuser      = $memberHandler->getUser($uid);
                if (is_object($thisuser)) {
                    $return = $thisuser->getVar('name');
                    if ('' == $return) {
                        $return = $thisuser->getVar('uname');
                    }
                } else {
                    $return = $GLOBALS['xoopsConfig']['anonymous'];
                }
                $tblusers[$uid] = $return;

                return $return;
            case 3: // Nothing
                $tblusers[$uid] = '';

                return '';
        }
    }

    /**
     * Function used to export news (in xml) and eventually the topics definitions
     * Warning, permissions are not exported !
     * @param int      $fromDate     Starting date
     * @param int      $toDate       Ending date
     * @param string   $topicsList
     * @param bool|int $usetopicsdef Should we also export topics definitions ?
     * @param          $topicsTable
     * @param boolean  $asObject     Return values as an object or not ?
     * @param string   $order
     * @return array
     * @internal param string $topiclist If not empty, a list of topics to limit to
     */
    public function NewsExport($fromDate, $toDate, $topicsList = '', $usetopicsdef = 0, &$topicsTable, $asObject = true, $order = 'published')
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $ret = [];
        if ($usetopicsdef) { // We firt begin by exporting topics definitions
            // Before all we must know wich topics to export
            $sql = 'SELECT distinct topicid';
            $sql .= " FROM {$this->db->prefix('nw_stories')}";
            $sql .= " WHERE (published >= {$fromDate} AND published <= {$toDate})";
            if (strlen(trim($topicsList)) > 0) {
                $sql .= " AND topicid IN ({$topicsList})";
            }
            $result = $this->db->query($sql);
            while ($myrow = $this->db->fetchArray($result)) {
                $topicsTable[] = $myrow['topicid'];
            }
        }

        // Now we can search for the stories
        $sql = 'SELECT s.*, t.*';
        $sql .= " FROM {$this->table} s, {$this->db->prefix('nw_topics')} t";
        $sql .= " WHERE (s.topicid = t.topic_id) AND (s.published >= {$fromDate} AND s.published <= {$toDate})";
        if (strlen(trim($topicsList)) > 0) {
            $sql .= " AND topicid IN ({$topicsList})";
        }
        $sql    .= " ORDER BY $order DESC";
        $result = $this->db->query($sql);
        while ($myrow = $this->db->fetchArray($result)) {
            if ($asObject) {
                $ret[] = new nw_NewsStory($myrow);
            } else {
                $ret[$myrow['storyid']] = $myts->htmlSpecialChars($myrow['title']);
            }
        }

        return $ret;
    }

    /**
     * Create or update an article
     * @param bool $approved
     * @return bool|int
     */
    public function store($approved = false)
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $counter     = isset($this->counter) ? $this->counter : 0;
        $title       = $myts->censorString($this->title);
        $title       = $myts->addSlashes($title);
        $hostname    = $myts->addSlashes($this->hostname);
        $type        = $myts->addSlashes($this->type);
        $hometext    = $myts->addSlashes($myts->censorString($this->hometext));
        $bodytext    = $myts->addSlashes($myts->censorString($this->bodytext));
        $description = $myts->addSlashes($myts->censorString($this->description));
        $keywords    = $myts->addSlashes($myts->censorString($this->keywords));
        $picture     = $myts->addSlashes($this->picture);
        $tags        = $myts->addSlashes($this->tags);
        $votes       = (int)$this->votes;
        $rating      = (float)$this->rating;
        if (!isset($this->nohtml) || 1 != $this->nohtml) {
            $this->nohtml = 0;
        }
        if (!isset($this->nosmiley) || 1 != $this->nosmiley) {
            $this->nosmiley = 0;
        }
        if (!isset($this->dobr) || 1 != $this->dobr) {
            $this->dobr = 0;
        }
        if (!isset($this->notifypub) || 1 != $this->notifypub) {
            $this->notifypub = 0;
        }
        if (!isset($this->topicdisplay) || 0 != $this->topicdisplay) {
            $this->topicdisplay = 1;
        }
        $expired = !empty($this->expired) ? $this->expired : 0;
        if (!isset($this->storyid)) {
            //$newpost = 1;
            $newstoryid = $this->db->genId($this->table . '_storyid_seq');
            $created    = time();
            $published  = $this->approved ? (int)$this->published : 0;
            //DNPROSSI - ADD TAGS FOR UPDATES - ADDED imagerows, pdfrows
            $sql = sprintf("INSERT INTO %s (storyid, uid, title, created, published, expired, hostname, nohtml, nosmiley, hometext, bodytext, counter, topicid, ihome, notifypub, story_type, topicdisplay, topicalign, comments, rating, votes, description, keywords, picture, dobr, tags, imagerows, pdfrows) VALUES (%u, %u, '%s', %u, %u, %u, '%s', %u, %u, '%s', '%s', %u, %u, %u, %u, '%s', %u, '%s', %u, %u, %u, '%s', '%s', '%s', '%u','%s', %u, %u)",
                           $this->table, $newstoryid, (int)$this->uid(), $title, $created, $published, $expired, $hostname, (int)$this->nohtml(), (int)$this->nosmiley(), $hometext, $bodytext, $counter, (int)$this->topicid(), (int)$this->ihome(), (int)$this->notifypub(), $type,
                           (int)$this->topicdisplay(), $this->topicalign, (int)$this->comments(), $rating, $votes, $description, $keywords, $picture, (int)$this->dobr(), $tags, (int)$this->imagerows(), (int)$this->pdfrows());
        } else {
            $sql        = sprintf("UPDATE %s SET title='%s', published=%u, expired=%u, nohtml=%u, nosmiley=%u, hometext='%s', bodytext='%s', topicid=%u, ihome=%u, topicdisplay=%u, topicalign='%s', comments=%u, rating=%u, votes=%u, uid=%u, description='%s', keywords='%s', picture='%s', dobr='%u', tags='%s', imagerows='%u', pdfrows='%u' WHERE storyid = %u",
                                  $this->table, $title, (int)$this->published(), $expired, (int)$this->nohtml(), (int)$this->nosmiley(), $hometext, $bodytext, (int)$this->topicid(), (int)$this->ihome(), (int)$this->topicdisplay(), $this->topicalign, (int)$this->comments(), $rating, $votes,
                                  (int)$this->uid(), $description, $keywords, $picture, (int)$this->dobr(), $tags, (int)$this->imagerows(), (int)$this->pdfrows(), (int)$this->storyid());
            $newstoryid = (int)$this->storyid();
        }
        if (!$this->db->queryF($sql)) {
            return false;
        }
        if (empty($newstoryid)) {
            $newstoryid    = $this->db->getInsertId();
            $this->storyid = $newstoryid;
        }

        return $newstoryid;
    }

    /**
     * @return mixed
     */
    public function picture()
    {
        return $this->picture;
    }

    //DNPROSSI - 1.71

    /**
     * @return mixed
     */
    public function imagerows()
    {
        return $this->imagerows;
    }

    /**
     * @param $imagerows
     */
    public function Setimagerows($imagerows)
    {
        $this->imagerows = $imagerows;
    }

    //DNPROSSI - 1.71

    /**
     * @return mixed
     */
    public function pdfrows()
    {
        return $this->pdfrows;
    }

    /**
     * @param $pdfrows
     */
    public function Setpdfrows($pdfrows)
    {
        $this->pdfrows = $pdfrows;
    }

    /**
     * @return mixed
     */
    public function rating()
    {
        return $this->rating;
    }

    /**
     * @return mixed
     */
    public function votes()
    {
        return $this->votes;
    }

    /**
     * @return mixed
     */
    public function tags()
    {
        return $this->tags;
    }

    /**
     * @param $tags
     */
    public function Settags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @param $data
     */
    public function Setpicture($data)
    {
        $this->picture = $data;
    }

    /**
     * @param $data
     */
    public function Setdescription($data)
    {
        $this->description = $data;
    }

    /**
     * @param $data
     */
    public function Setkeywords($data)
    {
        $this->keywords = $data;
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function description($format = 'S')
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        switch (strtoupper($format)) {
            case 'S':
                $description = $myts->htmlSpecialChars($this->description);
                break;
            case 'P':
            case 'F':
                $description = $myts->htmlSpecialChars($myts->stripSlashesGPC($this->description));
                break;
            case 'E':
                $description = $myts->htmlSpecialChars($this->description);
                break;
        }

        return $description;
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function keywords($format = 'S')
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        switch (strtoupper($format)) {
            case 'S':
                $keywords = $myts->htmlSpecialChars($this->keywords);
                break;
            case 'P':
            case 'F':
                $keywords = $myts->htmlSpecialChars($myts->stripSlashesGPC($this->keywords));
                break;
            case 'E':
                $keywords = $myts->htmlSpecialChars($this->keywords);
                break;
        }

        return $keywords;
    }

    /**
     * Returns a random number of news
     * @param int    $limit
     * @param int    $start
     * @param bool   $checkRight
     * @param int    $topic
     * @param int    $ihome
     * @param string $order
     * @param bool   $topic_frontpage
     * @return array
     */
    public function getRandomNews($limit = 0, $start = 0, $checkRight = false, $topic = 0, $ihome = 0, $order = 'published', $topic_frontpage = false)
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $ret = $rand_keys = $ret3 = [];
        $sql = 'SELECT storyid';
        $sql .= " FROM {$this->db->prefix('nw_stories')}";
        $sql .= ' WHERE (published > 0 AND published <= ' . time() . ') AND (expired = 0 OR expired > ' . time() . ')';
        if (0 != $topic) {
            if (!is_array($topic)) {
                if ($checkRight) {
                    $topics = nw_MygetItemIds('nw_view');
                    if (!in_array($topic, $topics)) {
                        return null;
                    } else {
                        $sql .= ' AND topicid=' . (int)$topic . ' AND (ihome=1 OR ihome=0)';
                    }
                } else {
                    $sql .= ' AND topicid=' . (int)$topic . ' AND (ihome=1 OR ihome=0)';
                }
            } else {
                if (count($topic) > 0) {
                    $sql .= ' AND topicid IN (' . implode(',', $topic) . ')';
                } else {
                    return null;
                }
            }
        } else {
            if ($checkRight) {
                $topics = nw_MygetItemIds('nw_view');
                if (count($topics) > 0) {
                    $topics = implode(',', $topics);
                    $sql    .= ' AND topicid IN (' . $topics . ')';
                } else {
                    return null;
                }
            }
            if (0 == (int)$ihome) {
                $sql .= ' AND ihome=0';
            }
        }
        if ($topic_frontpage) {
            $sql .= ' AND t.topic_frontpage=1';
        }
        $sql    .= " ORDER BY $order DESC";
        $result = $this->db->query($sql);

        while ($myrow = $this->db->fetchArray($result)) {
            $ret[] = $myrow['storyid'];
        }
        $cnt = count($ret);
        if ($cnt) {
            srand((double)microtime() * 10000000);
            if ($limit > $cnt) {
                $limit = $cnt;
            }
            $rand_keys = array_rand($ret, $limit);
            if ($limit > 1) {
                for ($i = 0; $i < $limit; $i++) {
                    $onestory = $ret[$rand_keys[$i]];
                    $ret3[]   = new nw_NewsStory($onestory);
                }
            } else {
                $ret3[] = new nw_NewsStory($ret[$rand_keys]);
            }
        }

        return $ret3;
    }

    /**
     * Returns statistics about the stories and topics
     * @param $limit
     * @return array
     */
    public function GetStats($limit)
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $ret = [];
        // Number of stories per topic, including expired and non published stories
        $ret2   = [];
        $sql    = 'SELECT count(s.storyid) as cpt, s.topicid, t.topic_title';
        $sql    .= " FROM {$this->db->prefix('nw_stories')} s, {$this->db->prefix('nw_topics')} t";
        $sql    .= ' WHERE s.topicid = t.topic_id GROUP BY s.topicid ORDER BY t.topic_title';
        $result = $this->db->query($sql);
        while ($myrow = $this->db->fetchArray($result)) {
            $ret2[$myrow['topicid']] = $myrow;
        }
        $ret['storiespertopic'] = $ret2;
        unset($ret2);
        // Total of reads per topic
        $ret2   = [];
        $sql    = 'SELECT Sum(counter) as cpt, topicid';
        $sql    .= " FROM {$this->db->prefix('nw_stories')} GROUP BY topicid ORDER BY topicid";
        $result = $this->db->query($sql);
        while ($myrow = $this->db->fetchArray($result)) {
            $ret2[$myrow['topicid']] = $myrow['cpt'];
        }
        $ret['readspertopic'] = $ret2;
        unset($ret2);
        // Attached files per topic
        $ret2   = [];
        $sql    = 'SELECT Count(*) as cpt, s.topicid';
        $sql    .= " FROM {$this->db->prefix('nw_stories_files')} f, {$this->db->prefix('nw_stories')} s";
        $sql    .= ' WHERE f.storyid = s.storyid GROUP BY s.topicid ORDER BY s.topicid';
        $result = $this->db->query($sql);
        while ($myrow = $this->db->fetchArray($result)) {
            $ret2[$myrow['topicid']] = $myrow['cpt'];
        }
        $ret['filespertopic'] = $ret2;
        unset($ret2);
        // Expired articles per topic
        $ret2   = [];
        $sql    = 'SELECT Count(storyid) as cpt, topicid';
        $sql    .= " FROM {$this->db->prefix('nw_stories')}";
        $sql    .= ' WHERE expired > 0 AND expired <= ' . time() . ' GROUP BY topicid ORDER BY topicid';
        $result = $this->db->query($sql);
        while ($myrow = $this->db->fetchArray($result)) {
            $ret2[$myrow['topicid']] = $myrow['cpt'];
        }
        $ret['expiredpertopic'] = $ret2;
        unset($ret2);
        // Number of unique authors per topic
        $ret2   = [];
        $sql    = 'SELECT Count(Distinct(uid)) as cpt, topicid';
        $sql    .= " FROM {$this->db->prefix('nw_stories')} GROUP BY topicid ORDER BY topicid";
        $result = $this->db->query($sql);
        while ($myrow = $this->db->fetchArray($result)) {
            $ret2[$myrow['topicid']] = $myrow['cpt'];
        }
        $ret['authorspertopic'] = $ret2;
        unset($ret2);
        // Most readed articles
        $ret2   = [];
        $sql    = 'SELECT s.storyid, s.uid, s.title, s.counter, s.topicid, t.topic_title';
        $sql    .= " FROM {$this->db->prefix('nw_stories')} s, {$this->db->prefix('nw_topics')} t";
        $sql    .= ' WHERE s.topicid = t.topic_id ORDER BY s.counter DESC';
        $result = $this->db->query($sql, (int)$limit);
        while ($myrow = $this->db->fetchArray($result)) {
            $ret2[$myrow['storyid']] = $myrow;
        }
        $ret['mostreadnews'] = $ret2;
        unset($ret2);
        // Less readed articles
        $ret2   = [];
        $sql    = 'SELECT s.storyid, s.uid, s.title, s.counter, s.topicid, t.topic_title';
        $sql    .= " FROM {$this->db->prefix('nw_stories')} s, {$this->db->prefix('nw_topics')} t";
        $sql    .= ' WHERE s.topicid = t.topic_id ORDER BY s.counter';
        $result = $this->db->query($sql, (int)$limit);
        while ($myrow = $this->db->fetchArray($result)) {
            $ret2[$myrow['storyid']] = $myrow;
        }
        $ret['lessreadnews'] = $ret2;
        unset($ret2);

        // Best rated articles
        $ret2   = [];
        $sql    = 'SELECT s.storyid, s.uid, s.title, s.rating, s.topicid, t.topic_title';
        $sql    .= " FROM {$this->db->prefix('nw_stories')} s, {$this->db->prefix('nw_topics')} t";
        $sql    .= ' WHERE s.topicid = t.topic_id ORDER BY s.rating DESC';
        $result = $this->db->query($sql, (int)$limit);
        while ($myrow = $this->db->fetchArray($result)) {
            $ret2[$myrow['storyid']] = $myrow;
        }
        $ret['besratednw'] = $ret2;
        unset($ret2);
        // Most readed authors
        $ret2   = [];
        $sql    = 'SELECT Sum(counter) as cpt, uid';
        $sql    .= " FROM {$this->db->prefix('nw_stories')} GROUP BY uid ORDER BY cpt DESC";
        $result = $this->db->query($sql, (int)$limit);
        while ($myrow = $this->db->fetchArray($result)) {
            $ret2[$myrow['uid']] = $myrow['cpt'];
        }
        $ret['mostreadedauthors'] = $ret2;
        unset($ret2);
        // Best rated authors
        $ret2   = [];
        $sql    = 'SELECT Avg(rating) as cpt, uid';
        $sql    .= " FROM {$this->db->prefix('nw_stories')}";
        $sql    .= ' WHERE votes > 0 GROUP BY uid ORDER BY cpt DESC';
        $result = $this->db->query($sql, (int)$limit);
        while ($myrow = $this->db->fetchArray($result)) {
            $ret2[$myrow['uid']] = $myrow['cpt'];
        }
        $ret['bestratedauthors'] = $ret2;
        unset($ret2);
        // Biggest contributors
        $ret2   = [];
        $sql    = 'SELECT Count(*) as cpt, uid';
        $sql    .= " FROM {$this->db->prefix('nw_stories')} GROUP BY uid ORDER BY cpt DESC";
        $result = $this->db->query($sql, (int)$limit);
        while ($myrow = $this->db->fetchArray($result)) {
            $ret2[$myrow['uid']] = $myrow['cpt'];
        }
        $ret['biggestcontributors'] = $ret2;
        unset($ret2);

        return $ret;
    }

    /**
     * Get the date of the older and most recent news
     * @param $older
     * @param $recent
     */
    public function GetOlderRecentnews(&$older, &$recent)
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $sql    = 'SELECT min(published) as minpublish, max(published) as maxpublish';
        $sql    .= " FROM {$this->db->prefix('nw_stories')}";
        $result = $this->db->query($sql);
        if (!$result) {
            $older = $recent = 0;
        } else {
            list($older, $recent) = $this->db->fetchRow($result);
        }
    }

    /*
     * Returns the author's IDs for the Who's who page
     */
    /**
     * @param bool $checkRight
     * @param int  $limit
     * @param int  $start
     * @return array|null
     */
    public function getWhosWho($checkRight = false, $limit = 0, $start = 0)
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $ret = [];
        $sql = 'SELECT distinct(uid) as uid';
        $sql .= " FROM {$this->db->prefix('nw_stories')}";
        $sql .= ' WHERE (published > 0 AND published <= ' . time() . ') AND (expired = 0 OR expired > ' . time() . ')';
        if ($checkRight) {
            $topics = nw_MygetItemIds('nw_view');
            if (count($topics) > 0) {
                $sql .= ' AND topicid IN (' . implode(',', $topics) . ')';
            } else {
                return null;
            }
        }
        $sql    .= ' ORDER BY uid';
        $result = $this->db->query($sql);
        while ($myrow = $this->db->fetchArray($result)) {
            $ret[] = $myrow['uid'];
        }

        return $ret;
    }

    /**
     * Returns the content of the summary and the titles requires for the list selector
     * @param $text
     * @param $titles
     * @return string
     */
    public function auto_summary($text, &$titles)
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $auto_summary = '';
        if ($this->xnews->getConfig('enhanced_pagenav')) {
            $expr_matches = [];
            $posdeb       = preg_match_all('/(\[pagebreak:|\[pagebreak).*\]/iU', $text, $expr_matches);
            if (count($expr_matches) > 0) {
                $delimiters  = $expr_matches[0];
                $arr_search  = ['[pagebreak:', '[pagebreak', ']'];
                $arr_replace = ['', '', ''];
                $cpt         = 1;
                if (isset($titles) && is_array($titles)) {
                    $titles[] = strip_tags(sprintf(_MA_NW_PAGE_AUTO_SUMMARY, 1, $this->title()));
                }
                $item         = "<a href='" . XNEWS_MODULE_URL . '/article.php?storyid=' . $this->storyid() . "&page=0'>" . sprintf(_MA_NW_PAGE_AUTO_SUMMARY, 1, $this->title()) . '</a><br>';
                $auto_summary .= $item;

                foreach ($delimiters as $item) {
                    $cpt++;
                    $item = str_replace($arr_search, $arr_replace, $item);
                    if ('' == xoops_trim($item)) {
                        $item = $cpt;
                    }
                    $titles[]     = strip_tags(sprintf(_MA_NW_PAGE_AUTO_SUMMARY, $cpt, $item));
                    $item         = "<a href='" . XNEWS_MODULE_URL . '/article.php?storyid=' . $this->storyid() . '&page=' . ($cpt - 1) . "'>" . sprintf(_MA_NW_PAGE_AUTO_SUMMARY, $cpt, $item) . '</a><br>';
                    $auto_summary .= $item;
                }
            }
        }

        return $auto_summary;
    }

    /**
     * @param string $format
     * @return mixed|string
     */
    public function hometext($format = 'Show')
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $html = $smiley = $xcodes = 1;
        $dobr = 0;
        if ($this->nohtml()) {
            $html = 0;
        }
        if ($this->nosmiley()) {
            $smiley = 0;
        }
        if ($this->dobr()) {
            $dobr = 1;
        }
        switch ($format) {
            case 'Show':
                $hometext     = $myts->displayTarea($this->hometext, $html, $smiley, 1, 1, $dobr);
                $tmp          = '';
                $auto_summary = $this->auto_summary($this->bodytext('Show'), $tmp);
                $hometext     = str_replace('[summary]', $auto_summary, $hometext);
                break;
            case 'Edit':
                $hometext = $myts->htmlSpecialChars($this->hometext);
                break;
            case 'Preview':
                $hometext = $myts->previewTarea($this->hometext, $html, $smiley, 1, 1, $dobr);
                break;
            case 'InForm':
                $hometext = $myts->stripSlashesGPC($this->hometext);
                $hometext = $myts->htmlSpecialChars($hometext);
                break;
        }

        return $hometext;
    }

    /**
     * @param string $format
     * @return mixed|string
     */
    public function bodytext($format = 'Show')
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $html   = 1;
        $smiley = 1;
        $xcodes = 1;
        $dobr   = 0;
        if ($this->nohtml()) {
            $html = 0;
        }
        if ($this->nosmiley()) {
            $smiley = 0;
        }
        if ($this->dobr()) {
            $dobr = 1;
        }
        switch ($format) {
            case 'Show':
                $bodytext     = $myts->displayTarea($this->bodytext, $html, $smiley, 1, 1, $dobr);
                $tmp          = '';
                $auto_summary = $this->auto_summary($bodytext, $tmp);
                $bodytext     = str_replace('[summary]', $auto_summary, $bodytext);
                break;
            case 'Edit':
                $bodytext = $myts->htmlSpecialChars($this->bodytext);
                break;
            case 'Preview':
                $bodytext = $myts->previewTarea($this->bodytext, $html, $smiley, 1, 1, $dobr);
                break;
            case 'InForm':
                $bodytext = $myts->stripSlashesGPC($this->bodytext);
                $bodytext = $myts->htmlSpecialChars($bodytext);
                break;
        }

        return $bodytext;
    }

    /**
     * Returns stories by Ids
     * @param        $ids
     * @param bool   $checkRight
     * @param bool   $asObject
     * @param string $order
     * @param bool   $onlyOnline
     * @return array|null
     */
    public function getStoriesByIds($ids, $checkRight = true, $asObject = true, $order = 'published', $onlyOnline = true)
    {
        $myts = \MyTextSanitizer::getInstance();
        //
        $limit = $start = 0;
        $ret   = [];
        $sql   = "SELECT s.*, t.* FROM {$this->db->prefix('nw_stories')} s, {$this->db->prefix('nw_topics')} t WHERE ";
        if (is_array($ids) && count($ids) > 0) {
            array_walk($ids, 'intval');
        }
        $sql .= ' s.storyid IN (' . implode(',', $ids) . ') ';

        if ($onlyOnline) {
            $sql .= ' AND (s.published > 0 AND s.published <= ' . time() . ') AND (s.expired = 0 OR s.expired > ' . time() . ') ';
        }
        $sql .= ' AND (s.topicid=t.topic_id) ';
        if ($checkRight) {
            $topics = nw_MygetItemIds('nw_view');
            if (count($topics) > 0) {
                $topics = implode(',', $topics);
                $sql    .= ' AND s.topicid IN (' . $topics . ')';
            } else {
                return null;
            }
        }
        $sql    .= " ORDER BY s.$order DESC";
        $result = $this->db->query($sql, $limit, $start);

        while ($myrow = $this->db->fetchArray($result)) {
            if ($asObject) {
                $ret[$myrow['storyid']] = new nw_NewsStory($myrow);
            } else {
                $ret[$myrow['storyid']] = $myts->htmlSpecialChars($myrow['title']);
            }
        }

        return $ret;
    }

    //ADDED by wishcraft ver 1.89

    /**
     * @param     $xoops_key
     * @param int $num
     * @param int $length
     * @param int $uu
     * @return bool|mixed|string
     */
    public function nw_stripeKey($xoops_key, $num = 7, $length = 32, $uu = 0)
    {
        $strip = floor(strlen($xoops_key) / $num);
        for ($i = 0; $i < strlen($xoops_key); $i++) {
            if ($i < $length) {
                $uu++;
                if ($uu == $strip) {
                    $ret .= substr($xoops_key, $i, 1) . '-';
                    $uu  = 0;
                } else {
                    if ('-' != substr($xoops_key, $i, 1)) {
                        $ret .= substr($xoops_key, $i, 1);
                    } else {
                        $uu--;
                    }
                }
            }
        }
        $ret = str_replace('--', '-', $ret);
        if ('-' == substr($ret, 0, 1)) {
            $ret = substr($ret, 2, strlen($ret));
        }
        if ('-' == substr($ret, strlen($ret) - 1, 1)) {
            $ret = substr($ret, 0, strlen($ret) - 1);
        }

        return $ret;
    }
}
