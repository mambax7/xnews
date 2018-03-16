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

use XoopsModules\Xnews;

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

// ######################################################################
// #                                                                    #
// # Latest News block by Mowaffak ( www.arabxoops.com )                #
// # based on Last Articles Block by Pete Glanz (www.glanz.ru)          #
// # Thanks to:                                                         #
// # Trabis ( www.xuups.com ) and Bandit-x ( www.bandit-x.net )         #
// #                                                                    #
// ######################################################################

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

/**
 * Class Latestnewsstory
 */
class Latestnewsstory extends Xnews\NewsStory
{
    /**
     * Latestnewsstory constructor.
     * @param int $id
     */
    public function __construct($id = -1)
    {
        parent::__construct($id);
    }

    /**
     * Returns published stories according to some options
     * @param int    $limit
     * @param bool   $selected_stories
     * @param int    $start
     * @param bool   $checkRight
     * @param int    $topic
     * @param int    $ihome
     * @param bool   $asObject
     * @param string $order
     * @param bool   $topic_frontpage
     * @return array
     */
    public function getAllPublished($limit = 0, $selected_stories = true, $start = 0, $checkRight = false, $topic = 0, $ihome = 0, $asObject = true, $order = 'published', $topic_frontpage = false)
    {
        $db   = \XoopsDatabaseFactory::getDatabaseConnection();
        $myts = \MyTextSanitizer::getInstance();

        $ret = [];
        $sql = 'SELECT s.*, t.*';
        $sql .= " FROM {$db->prefix('nw_stories')} s, {$db->prefix('nw_topics')}";
        $sql .= ' t WHERE (s.published > 0 AND s.published <= ' . time() . ') AND (s.expired = 0 OR s.expired > ' . time() . ') AND (s.topicid = t.topic_id) ';
        if (0 != $topic) {
            if ($selected_stories) {
                $sql .= " AND s.storyid IN ({$selected_stories})";
            }
            if (!is_array($topic)) {
                if ($checkRight) {
                    $topics = nw_MygetItemIds('nw_view');
                    if (!in_array($topic, $topics)) {
                        return null;
                    } else {
                        $sql .= ' AND s.topicid = ' . (int)$topic . ' AND (s.ihome = 1 OR s.ihome = 0)';
                    }
                } else {
                    $sql .= ' AND s.topicid = ' . (int)$topic . ' AND (s.ihome = 1 OR s.ihome = 0)';
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
                $sql .= ' AND s.ihome = 0';
            }
        }
        if ($topic_frontpage) {
            $sql .= ' AND t.topic_frontpage=1';
        }
        $sql    .= " ORDER BY s.$order DESC";
        $result = $db->query($sql, (int)$limit, (int)$start);

        while (false !== ($myrow = $db->fetchArray($result))) {
            if ($asObject) {
                $ret[] = new Latestnewsstory($myrow);
            } else {
                $ret[$myrow['storyid']] = $myts->htmlSpecialChars($myrow['title']);
            }
        }

        return $ret;
    }
}
