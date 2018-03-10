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
 * @author       XOOPS Development Team
 */

use XoopsModules\Xnews;

require_once __DIR__ . '/header.php';

// require_once XNEWS_MODULE_PATH . '/class/NewsStory.php';

// We verify that the user can post comments **********************************
if (0 == $helper->getConfig('com_rule')) { // Comments are deactivate
    die();
}

if (0 == $helper->getConfig('com_anonpost') && !is_object($xoopsUser)) { // Anonymous users can't post
    die();
}
// ****************************************************************************

$com_itemid = isset($_GET['com_itemid']) ? (int)$_GET['com_itemid'] : 0;
if ($com_itemid > 0) {
    $article = new Xnews\NewsStory($com_itemid);
    if ($article->storyid > 0) {
        $com_replytext = _POSTEDBY . '&nbsp;<b>' . $article->uname() . '</b>&nbsp;' . _DATE . '&nbsp;<b>' . formatTimestamp($article->published(), $helper->getConfig('dateformat')) . '</b><br><br>' . $article->hometext();
        $bodytext      = $article->bodytext();
        if ('' != $bodytext) {
            $com_replytext .= '<br><br>' . $bodytext . '';
        }
        $com_replytitle = $article->title();
        require_once XOOPS_ROOT_PATH . '/include/comment_new.php';
    } else {
        exit;
    }
}
