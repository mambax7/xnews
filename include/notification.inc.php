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
 * @copyright    {@link https://xoops.org/ XOOPS Project}
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author       XOOPS Development Team
 */

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * @param $category
 * @param $item_id
 * @return null
 */
function nw_notify_iteminfo($category, $item_id)
{
    if ('global' === $category) {
        $item['name'] = '';
        $item['url']  = '';

        return $item;
    }

    global $xoopsDB;

    if ('story' === $category) {
        // Assume we have a valid story id
        $sql    = 'SELECT title FROM ' . $xoopsDB->prefix('nw_stories') . ' WHERE storyid = ' . (int)$item_id;
        $result = $xoopsDB->query($sql);
        if ($result) {
            $result_array = $xoopsDB->fetchArray($result);
            $item['name'] = $result_array['title'];
            $item['url']  = XNEWS_MODULE_URL . '/article.php?storyid=' . (int)$item_id;

            return $item;
        } else {
            return null;
        }
    }

    // Added by Lankford on 2007/3/23
    if ('category' === $category) {
        $sql    = 'SELECT title FROM ' . $xoopsDB->prefix('nw_topics') . ' WHERE topic_id = ' . (int)$item_id;
        $result = $xoopsDB->query($sql);
        if ($result) {
            $result_array = $xoopsDB->fetchArray($result);
            $item['name'] = $result_array['topic_id'];
            $item['url']  = XNEWS_MODULE_URL . '/index.php?topic_id=' . (int)$item_id;

            return $item;
        } else {
            return null;
        }
    }
}
