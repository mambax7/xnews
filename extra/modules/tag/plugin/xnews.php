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
 * @author       XOOPS Development Team, Herve Thouzard, Instant Zero
 */

use XoopsModules\Xnews;

/**
 * @param $items
 * @return bool
 */
function xnews_tag_iteminfo(&$items)
{
    if (empty($items) || !is_array($items)) {
        return false;
    }

    $items_id = [];
    foreach (array_keys($items) as $cat_id) {
        foreach (array_keys($items[$cat_id]) as $item_id) {
            $items_id[] = (int)$item_id;
        }
    }
    // require_once XNEWS_MODULE_PATH . '/class/NewsStory.php';
    $tempnw    = new Xnews\NewsStory();
    $items_obj = $tempnw->getStoriesByIds($items_id);

    foreach (array_keys($items) as $cat_id) {
        foreach (array_keys($items[$cat_id]) as $item_id) {
            if (isset($items_obj[$item_id])) {
                $item_obj                 = &$items_obj[$item_id];
                $items[$cat_id][$item_id] = [
                    'title'   => $item_obj->title(),
                    'uid'     => $item_obj->uid(),
                    'link'    => "article.php?storyid={$item_id}",
                    'time'    => $item_obj->published(),
                    'tags'    => tag_parse_tag($item_obj->tags()), // optional
                    'content' => $item_obj->hometext(),
                ];
            }
        }
    }
    unset($items_obj);
}

/**
 * @param $mid
 */
function xnews_tag_synchronization($mid)
{
    global $xoopsDB;
    $itemHandler_keyName = 'storyid';
    $itemHandler_table   = $xoopsDB->prefix('nw_stories');
    //    $linkHandler         = \XoopsModules\Tag\Helper::getInstance()->getHandler('Link'); //@var \XoopsModules\Tag\Handler $tagHandler
    /** @var \XoopsModules\Tag\LinkHandler $itemHandler */
    $linkHandler = \XoopsModules\Tag\Helper::getInstance()->getHandler('Link');
    $where       = "({$itemHandler_table}.published > 0 AND {$itemHandler_table}.published <= " . time() . ") AND ({$itemHandler_table}.expired = 0 OR {$itemHandler_table}.expired > " . time() . ')';

    /* clear tag-item links */
    if ($linkHandler->mysql_major_version() >= 4) {
        $sql = 'DELETE';
        $sql .= " FROM {$linkHandler->table}";
        $sql .= " WHERE tag_modid = {$mid} AND (tag_itemid NOT IN (SELECT DISTINCT {$itemHandler_keyName} FROM {$itemHandler_table} WHERE {$where}) )";
    } else {
        $sql = "DELETE {$linkHandler->table}";
        $sql .= " FROM {$linkHandler->table}";
        $sql .= " LEFT JOIN {$itemHandler_table} AS aa ON {$linkHandler->table}.tag_itemid = aa.{$itemHandler_keyName} ";
        $sql .= " WHERE tag_modid = {$mid} AND ( aa.{$itemHandler_keyName} IS NULL OR {$where})";
    }
    if (!$result = $linkHandler->db->queryF($sql)) {
        //xoops_error($linkHandler->db->error());
    }
}
