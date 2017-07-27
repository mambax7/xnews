<?php
function xnews_tag_iteminfo(&$items) {
    if (empty($items) || !is_array($items)){
        return false;
    }

    $items_id = array();
    foreach(array_keys($items) as $cat_id){
        foreach(array_keys($items[$cat_id]) as $item_id){
            $items_id[] = intval($item_id);
        }
    }
    require_once XNEWS_MODULE_PATH . '/class/class.newsstory.php';
    $tempnw = new nw_NewsStory();
    $items_obj = $tempnw->getStoriesByIds($items_id);

    foreach(array_keys($items) as $cat_id){
        foreach(array_keys($items[$cat_id]) as $item_id) {
            if (isset($items_obj[$item_id])) {
                $item_obj =& $items_obj[$item_id];
                $items[$cat_id][$item_id] = array(
                    'title'     => $item_obj->title(),
                    'uid'       => $item_obj->uid(),
                    'link'      => "article.php?storyid={$item_id}",
                    'time'      => $item_obj->published(),
                    'tags'      => tag_parse_tag($item_obj->tags()), // optional
                    'content'   => $item_obj->hometext()
                );
            }
        }
    }
    unset($items_obj);
}

function xnews_tag_synchronization($mid) {
    global $xoopsDB;
    $item_handler_keyName = 'storyid';
    $item_handler_table = $xoopsDB->prefix('nw_stories');
    $link_handler =& xoops_getmodulehandler("link", "tag");
    $where = "({$item_handler_table}.published > 0 AND {$item_handler_table}.published <= " . time() . ") AND ({$item_handler_table}.expired = 0 OR {$item_handler_table}.expired > " . time() . ')';

    /* clear tag-item links */
    if ($link_handler->mysql_major_version() >= 4) {
    $sql = "DELETE";
    $sql .= " FROM {$link_handler->table}";
    $sql .= " WHERE tag_modid = {$mid} AND (tag_itemid NOT IN (SELECT DISTINCT {$item_handler_keyName} FROM {$item_handler_table} WHERE {$where}) )";
    } else {
    $sql = "DELETE {$link_handler->table}";
    $sql .= " FROM {$link_handler->table}";
    $sql .= " LEFT JOIN {$item_handler_table} AS aa ON {$link_handler->table}.tag_itemid = aa.{$item_handler_keyName} ";
    $sql .= " WHERE tag_modid = {$mid} AND ( aa.{$item_handler_keyName} IS NULL OR {$where})";
    }
    if (!$result = $link_handler->db->queryF($sql)) {
        //xoops_error($link_handler->db->error());
    }
}
