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

function nw_b_news_topicsnav_show($options)
{
    require_once XNEWS_MODULE_PATH . '/include/functions.php';
    require_once XNEWS_MODULE_PATH . '/class/class.newstopic.php';
    $myts             = MyTextSanitizer::getInstance();
    $block            = array();
    $newscountbytopic = array();
    $perms            = '';
    $xt               = new nw_NewsTopic();
    $restricted       = $xnews->getConfig('restrictindex');
    if ($restricted) {
        global $xoopsUser;
        $moduleHandler = xoops_getHandler('module');
        $newsModule    = $moduleHandler->getByDirname(XNEWS_MODULE_DIRNAME);
        $groups        = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $gpermHandler  = xoops_getHandler('groupperm');
        $topics        = $gpermHandler->getItemIds('nw_view', $groups, $newsModule->getVar('mid'));
        if (count($topics) > 0) {
            $topics = implode(',', $topics);
            $perms  = ' AND topic_id IN (' . $topics . ') ';
        } else {
            return '';
        }
    }
    $topics_arr = $xt->getChildTreeArray(0, 'topic_title', $perms);
    if ($options[0] == 1) {
        $newscountbytopic = $xt->getnwCountByTopic();
    }
    if (is_array($topics_arr) && count($topics_arr)) {
        foreach ($topics_arr as $onetopic) {
            if ($options[0] == 1) {
                $count = 0;
                if (array_key_exists($onetopic['topic_id'], $newscountbytopic)) {
                    $count = $newscountbytopic[$onetopic['topic_id']];
                }
            } else {
                $count = '';
            }
            $block['topics'][] = array(
                'id'          => $onetopic['topic_id'],
                'nw_count'    => $count,
                'topic_color' => '#' . $onetopic['topic_color'],
                'title'       => $myts->displayTarea($onetopic['topic_title'])
            );
        }
    }
    //DNPROSSI ADDED
    $block['newsmodule_url'] = XNEWS_MODULE_URL;

    // DNPROSSI SEO
    $seo_enabled = $xnews->getConfig('seo_enable');
    if ($seo_enabled != 0) {
        $block['urlrewrite'] = 'true';
    } else {
        $block['urlrewrite'] = 'false';
    }

    return $block;
}

function nw_b_news_topicsnav_edit($options)
{
    $form = _MB_NW_SHOW_NEWS_COUNT . " <input type='radio' name='options[]' value='1'";
    if ($options[0] == 1) {
        $form .= ' checked';
    }
    $form .= '>' . _YES;
    $form .= "<input type='radio' name='options[]' value='0'";
    if ($options[0] == 0) {
        $form .= ' checked';
    }
    $form .= '>' . _NO;

    return $form;
}

function nw_b_news_topicsnav_onthefly($options)
{
    $options = explode('|', $options);
    $block   = &nw_b_news_topicsnav_show($options);

    $tpl = new XoopsTpl();
    $tpl->assign('block', $block);
    $tpl->display('db:nw_news_block_topicnav.html');
}
