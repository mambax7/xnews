<?php
include_once __DIR__ . '/header.php';

include_once XNEWS_MODULE_PATH . '/class/class.newsstory.php';

// We verify that the user can post comments **********************************
if ($xnews->getConfig('com_rule') == 0) { // Comments are deactivate
    die();
}

if ($xnews->getConfig('com_anonpost') == 0 && !is_object($xoopsUser)) { // Anonymous users can't post
    die();
}
// ****************************************************************************

$com_itemid = isset($_GET['com_itemid']) ? intval($_GET['com_itemid']) : 0;
if ($com_itemid > 0) {
    $article = new nw_NewsStory($com_itemid);
    if ($article->storyid > 0) {
        $com_replytext = _POSTEDBY . '&nbsp;<b>' . $article->uname() . '</b>&nbsp;' . _DATE . '&nbsp;<b>' . formatTimestamp($article->published(), $xnews->getConfig('dateformat')) . '</b><br /><br />' . $article->hometext();
        $bodytext = $article->bodytext();
        if ($bodytext != '') {
            $com_replytext .= '<br /><br />' . $bodytext . '';
        }
        $com_replytitle = $article->title();
        include_once XOOPS_ROOT_PATH . '/include/comment_new.php';
    } else {
        exit;
    }
}
