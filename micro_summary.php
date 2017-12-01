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
