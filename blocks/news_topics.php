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

/**
 * @return mixed
 */
function nw_b_news_topics_show()
{
    global $topic_id; // Don't know why this is used and where it's coming from ....
    require_once XNEWS_MODULE_PATH . '/include/functions.php';
    require_once XNEWS_MODULE_PATH . '/class/class.newstopic.php';
    require_once XNEWS_MODULE_PATH . '/class/common/tree.php';

    $jump       = XNEWS_MODULE_URL . '/index.php?topic_id=';
    $topic_id   = !empty($topic_id) ? (int)$topic_id : 0;
    $restricted = $xnews->getConfig('restrictindex');

    $xt                 = new nw_NewsTopic();
    $allTopics          = $xt->getAllTopics($restricted);
    $topic_tree         = new XnewsMyXoopsObjectTree($allTopics, 'topic_id', 'topic_pid');
    $additional         = " onchange='location=\"" . $jump . "\"+this.options[this.selectedIndex].value'";

    if (XNewsUtility::checkVerXoops($GLOBALS['xoopsModule'], '2.5.9')) {
        $topicSelect = $topic_tree->makeSelectElement('topic_id', 'topic_title', '--', '', true, 0, $additional);
        $block['selectbox'] =  $topicSelect->render();
    } else {
        $block['selectbox'] = $topic_tree->makeSelBox('topic_id', 'topic_title', '-- ', '', true, 0, $additional);
    }
    //DNPROSSI ADDED
    $block['newsmodule_url'] = XNEWS_MODULE_URL;

    return $block;
}

/**
 * @param $options
 */
function nw_b_news_topics_onthefly($options)
{
    $options = explode('|', $options);
    $block   = &nw_b_news_topics_show($options);

    $tpl = new XoopsTpl();
    $tpl->assign('block', $block);
    $tpl->display('db:nw_news_block_topics.tpl');
}
