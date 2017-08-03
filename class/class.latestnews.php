<?php
defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

class nw_Latestnewsstory extends nw_NewsStory
{
    public function __construct($id = -1)
    {
        parent::nw_NewsStory($id);
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
        $db   = XoopsDatabaseFactory::getDatabaseConnection();
        $myts = MyTextSanitizer::getInstance();

        $ret = array();
        $sql = 'SELECT s.*, t.*';
        $sql .= " FROM {$db->prefix('nw_stories')} s, {$db->prefix('nw_topics')}";
        $sql .= ' t WHERE (s.published > 0 AND s.published <= ' . time() . ') AND (s.expired = 0 OR s.expired > ' . time() . ') AND (s.topicid = t.topic_id) ';
        if ($topic != 0) {
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
            if ((int)$ihome == 0) {
                $sql .= ' AND s.ihome = 0';
            }
        }
        if ($topic_frontpage) {
            $sql .= ' AND t.topic_frontpage=1';
        }
        $sql    .= " ORDER BY s.$order DESC";
        $result = $db->query($sql, (int)$limit, (int)$start);

        while ($myrow = $db->fetchArray($result)) {
            if ($asObject) {
                $ret[] = new nw_Latestnewsstory($myrow);
            } else {
                $ret[$myrow['storyid']] = $myts->htmlSpecialChars($myrow['title']);
            }
        }

        return $ret;
    }
}
