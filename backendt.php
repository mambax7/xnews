<?php
/**
 * RSS per topics
 *
 * This script is used to generate RSS feeds for each topic.
 * You can enable and disable this feature with the module's option named "Enable RSS feeds per topics ?"
 * The script uses the permissions to know what to display.
 *
 * @package       News
 * @author        Xoops Modules Dev Team
 * @copyright (c) The Xoops Project - www.xoops.org
 * @param type $nomvariable description
 */
require_once __DIR__ . '/header.php';

require_once XOOPS_ROOT_PATH . '/class/template.php';
require_once XNEWS_MODULE_PATH . '/class/class.newsstory.php';
require_once XNEWS_MODULE_PATH . '/class/class.newstopic.php';

error_reporting(E_ALL);
$GLOBALS['xoopsLogger']->activated = false;

if (!$xnews->getConfig('topicsrss')) {
    exit();
}

$topicid = isset($_GET['topicid']) ? (int)($_GET['topicid']) : 0;
if ($topicid == 0) {
    exit();
}

if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}

$restricted = $xnews->getConfig('restrictindex');
$newsnumber = $xnews->getConfig('storyhome');

$charset = 'utf-8';

header('Content-Type:text/xml; charset=' . $charset);
$story = new nw_NewsStory();
$tpl   = new XoopsTpl();
$tpl->xoops_setCaching(2);
$tpl->xoops_setCacheTime(3600); // Change this to the value you want

if (!$tpl->is_cached('db:nw_news_rss.tpl', $topicid)) {
    $xt     = new nw_NewsTopic($topicid);
    $sarray = $story->getAllPublished($newsnumber, 0, $restricted, $topicid);
    if (is_array($sarray) && count($sarray > 0)) {
        $sitename = htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES);
        $slogan   = htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES);
    }
    $tpl->assign('channel_title', xoops_utf8_encode($sitename));
    $tpl->assign('channel_link', XOOPS_URL . '/');
    $tpl->assign('channel_desc', xoops_utf8_encode($slogan));
    $tpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
    $tpl->assign('channel_webmaster', checkEmail($xoopsConfig['adminmail'], true)); // Fed up with spam
    $tpl->assign('channel_editor', checkEmail($xoopsConfig['adminmail'], true)); // Fed up with spam
    $tpl->assign('channel_category', $xt->topic_title());
    $tpl->assign('channel_generator', 'XOOPS');
    $tpl->assign('channel_language', _LANGCODE);
    $tpl->assign('image_url', XOOPS_URL . '/images/logo.gif');
    $dimention = getimagesize(XOOPS_ROOT_PATH . '/images/logo.gif');
    if (empty($dimention[0])) {
        $width = 88;
    } else {
        $width = ($dimention[0] > 144) ? 144 : $dimention[0];
    }
    if (empty($dimention[1])) {
        $height = 31;
    } else {
        $height = ($dimention[1] > 400) ? 400 : $dimention[1];
    }
    $tpl->assign('image_width', $width);
    $tpl->assign('image_height', $height);
    $count = $sarray;
    foreach ($sarray as $story) {
        $storytitle = $story->title();
        //if we are allowing html, we need to use htmlspecialchars or any bug will break the output
        $description = htmlspecialchars($story->hometext(), ENT_QUOTES);

        // DNPROSSI SEO
        $seo_enabled = $xnews->getConfig('seo_enable');
        $item_title  = '';
        if ($seo_enabled != 0) {
            $item_title = nw_remove_accents($storytitle);
        }
        $tpl->append('items', [
            'title'       => XoopsLocal::convert_encoding(htmlspecialchars($storytitle, ENT_QUOTES)),
            'link'        => nw_seo_UrlGenerator(_MA_NW_SEO_ARTICLES, $story->storyid(), $item_title),
            'guid'        => $story->nw_stripeKey(md5($story->title() . $story->topic_title), 7, 32),
            'category'    => XoopsLocal::convert_encoding(htmlspecialchars($story->topic_title, ENT_QUOTES)),
            'pubdate'     => formatTimestamp($story->published(), 'rss'),
            'description' => $description
        ]);
    }
}

$tpl->display('db:nw_news_rss.tpl', $topicid);
