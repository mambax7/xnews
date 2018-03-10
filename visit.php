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
// require_once XNEWS_MODULE_PATH . '/class/Files.php';
// require_once XNEWS_MODULE_PATH . '/class/NewsStory.php';

$fileid = isset($_GET['fileid']) ? (int)$_GET['fileid'] : 0;
if (empty($fileid)) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _ERRORS);
}
$myts   = \MyTextSanitizer::getInstance(); // MyTextSanitizer object
$sfiles = new Xnews\Files($fileid);

// Do we have the right to see the file ?
$article = new Xnews\NewsStory($sfiles->getStoryid());
// and the news, can we see it ?
if (0 == $article->published() || $article->published() > time()) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _MD_XNEWS_NOSTORY);
}
// Expired
if (0 != $article->expired() && $article->expired() < time()) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _MD_XNEWS_NOSTORY);
}

$gpermHandler = xoops_getHandler('groupperm');
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
if (!$gpermHandler->checkRight('nw_view', $article->topicid(), $groups, $xoopsModule->getVar('mid'))) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _NOPERM);
}

$sfiles->updateCounter();
$url = XNEWS_ATTACHED_FILES_URL . '/' . $sfiles->getDownloadname();
if (!preg_match("/^ed2k*:\/\//i", $url)) {
    header("Location: $url");
}
echo '<html><head><meta http-equiv="Refresh" content="0; URL=' . $myts->htmlSpecialChars($url) . '"></meta></head><body></body></html>';
exit();
