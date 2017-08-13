<?php
defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

/**
 * Solves issue when upgrading xoops version
 * Paths not set and block would not work
 */
if (!defined('XNEWS_MODULE_PATH')) {
    define('XNEWS_SUBPREFIX', 'nw');
    define('XNEWS_MODULE_DIRNAME', 'xnews');
    define('XNEWS_MODULE_PATH', XOOPS_ROOT_PATH . '/modules/' . XNEWS_MODULE_DIRNAME);
    define('XNEWS_MODULE_URL', XOOPS_URL . '/modules/' . XNEWS_MODULE_DIRNAME);
    define('XNEWS_UPLOADS_NEWS_PATH', XOOPS_ROOT_PATH . '/uploads/' . XNEWS_MODULE_DIRNAME);
    define('XNEWS_TOPICS_FILES_PATH', XOOPS_ROOT_PATH . '/uploads/' . XNEWS_MODULE_DIRNAME . '/topics');
    define('XNEWS_ATTACHED_FILES_PATH', XOOPS_ROOT_PATH . '/uploads/' . XNEWS_MODULE_DIRNAME . '/attached');
    define('XNEWS_TOPICS_FILES_URL', XOOPS_URL . '/uploads/' . XNEWS_MODULE_DIRNAME . '/topics');
    define('XNEWS_ATTACHED_FILES_URL', XOOPS_URL . '/uploads/' . XNEWS_MODULE_DIRNAME . '/attached');
}

require_once XNEWS_MODULE_PATH . '/class/class.newsstory.php';
require_once XNEWS_MODULE_PATH . '/include/functions.php';

/**
 * Dispay a block where news moderators can show news that need to be moderated.
 */
function nw_b_news_topics_moderate()
{
    $xnews               = XnewsXnews::getInstance();
    $nw_NewsStoryHandler = new nw_NewsStory();
    //
    $block      = array();
    $dateformat = $xnews->getConfig('dateformat');
    $infotips   = $xnews->getConfig('infotips');

    $storyarray = $nw_NewsStoryHandler->getAllSubmitted(0, true, $xnews->getConfig('restrictindex'));
    if (count($storyarray) > 0) {
        $block['lang_story_title']  = _MB_NW_TITLE;
        $block['lang_story_date']   = _MB_NW_POSTED;
        $block['lang_story_author'] = _MB_NW_POSTER;
        $block['lang_story_action'] = _MB_NW_ACTION;
        $block['lang_story_topic']  = _MB_NW_TOPIC;
        $myts                       = MyTextSanitizer::getInstance();
        foreach ($storyarray as $newstory) {
            $title     = $newstory->title();
            $htmltitle = '';
            if ($infotips > 0) {
                $story['infotips'] = nw_make_infotips($newstory->hometext());
                $htmltitle         = ' title="' . $story['infotips'] . '"';
            }

            if (!isset($title) || ($title == '')) {
                $linktitle = "<a href='" . XNEWS_MODULE_URL . '/index.php?op=edit&amp;storyid=' . $newstory->storyid() . "' target='_blank'" . $htmltitle . '>' . _AD_NOSUBJECT . '</a>';
            } else {
                $linktitle = "<a href='" . XNEWS_MODULE_URL . '/submit.php?op=edit&amp;storyid=' . $newstory->storyid() . "' target='_blank'" . $htmltitle . '>' . $title . '</a>';
            }
            $story                = array();
            $story['title']       = $linktitle;
            $story['date']        = formatTimestamp($newstory->created(), $dateformat);
            $story['author']      = "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $newstory->uid() . "'>" . $newstory->uname() . '</a>';
            $story['action']      = "<a href='" . XNEWS_MODULE_URL . '/admin/index.php?op=edit&amp;storyid=' . $newstory->storyid() . "'>" . _EDIT . "</a> - <a href='" . XNEWS_MODULE_URL . '/admin/index.php?op=delete&amp;storyid=' . $newstory->storyid() . "'>" . _MB_NW_DELETE . '</a>';
            $story['topic_title'] = $newstory->topic_title();
            $story['topic_color'] = '#' . $myts->displayTarea($newstory->topic_color);
            $block['stories'][]   =& $story;
            unset($story);
        }
    }

    return $block;
}

function nw_b_news_topics_moderate_onthefly($options)
{
    $options = explode('|', $options);
    $block   = &nw_b_news_topics_moderate($options);

    $tpl = new XoopsTpl();
    $tpl->assign('block', $block);
    $tpl->display('db:nw_news_block_moderate.tpl');
}
