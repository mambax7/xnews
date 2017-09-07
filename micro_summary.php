<?php
/*
 * Created on 28 oct. 2006
 *
 * This file is responsible for creating micro summaries for Firefox 2 web navigator
 * For more information, see this page : http://wiki.mozilla.org/Microsummaries
 *
 * @package News
 * @author Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 *
 * NOTE : If you use this code, please make credit.
 *
 */
require_once __DIR__ . '/header.php';

require_once XNEWS_MODULE_PATH . '/class/class.newsstory.php';

if (!$xnews->getConfig('firefox_microsummaries')) {
    exit();
}
$story      = new nw_NewsStory();
$restricted = $xnews->getConfig('restrictindex');
$sarray     = [];
// Get the last news from all topics according to the module's restrictions
$sarray = $story->getAllPublished(1, 0, $restricted, 0);
if (count($sarray) > 0) {
    $laststory = null;
    $laststory = $sarray[0];
    if (is_object($laststory)) {
        header('Content-Type:text;');
        echo $laststory->title() . ' - ' . $xoopsConfig['sitename'];
    }
}
