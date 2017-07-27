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

// comment callback functions
defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

require_once XNEWS_MODULE_PATH . '/class/class.newsstory.php';

function nw_com_update($story_id, $total_num)
{
    $story_id  = intval($story_id);
    $total_num = intval($total_num);
    $article   = new nw_NewsStory($story_id);
    if (!$article->updateComments($total_num)) {
        return false;
    }

    return true;
}

function nw_com_approve(&$comment)
{
    // notification mail here
}