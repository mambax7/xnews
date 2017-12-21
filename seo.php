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
 * @author     XOOPS Development Team
 */

defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

$seoOp  = @$_GET['seoOp'];
$seoArg = @$_GET['seoArg'];
//trigger_error('out', E_USER_ERROR);
if (empty($seoOp) && @$_SERVER['PATH_INFO']) {
    //SEO mode is path-info
    //
    //    Sample URL for path-info
    //    http://localhost/modules/xnews/index.php/articles.1/seo-is-active.html
    //
    $data = explode('/', $_SERVER['PATH_INFO']);

    $seoParts = explode('.', $data[1]);
    if (2 == count($seoParts)) {
        $seoOp  = $seoParts[0];
        $seoArg = $seoParts[1];
    }
    if (3 == count($seoParts)) {
        $seoOp  = $seoParts[1];
        $seoArg = $seoParts[2];
    }

    // for multi-argument modules, where stroyid and topic_id both are required.
    // $seoArg = substr($data[1], strlen($seoOp) +1);
}

$seoMap = [
    _MD_XNEWS_SEO_TOPICS   => 'index.php',
    _MD_XNEWS_SEO_ARTICLES => 'article.php',
    _MD_XNEWS_SEO_PRINT    => 'print.php',
    _MD_XNEWS_SEO_PDF      => 'makepdf.php'
];

if (!empty($seoOp) && !empty($seoMap[$seoOp])) {
    //module specific dispatching logic, other module must implement as
    //per their requirements.
    $url_arr = explode('/modules/', $_SERVER['PHP_SELF']);
    $newUrl  = $url_arr[0] . '/modules/' . XNEWS_MODULE_DIRNAME . '/' . $seoMap[$seoOp];

    $_ENV['PHP_SELF']       = $newUrl;
    $_SERVER['SCRIPT_NAME'] = $newUrl;
    $_SERVER['PHP_SELF']    = $newUrl;
    switch ($seoOp) {
        case _MD_XNEWS_SEO_TOPICS:
            $_SERVER['REQUEST_URI'] = $newUrl . '?topic_id=' . $seoArg;
            $_GET['topic_id']       = $seoArg;
            break;
        case _MD_XNEWS_SEO_ARTICLES:
        case _MD_XNEWS_SEO_PRINT:
        case _MD_XNEWS_SEO_PDF:
        default:
            $_SERVER['REQUEST_URI'] = $newUrl . '?storyid=' . $seoArg;
            $_GET['storyid']        = $seoArg;
    }

    include $seoMap[$seoOp];
    exit;
    //trigger_error('out', E_USER_WARNING);
}
