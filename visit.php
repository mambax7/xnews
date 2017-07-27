<?php
/include_once __DIR__ . '/header.php';

include_once XNEWS_MODULE_PATH . '/class/class.sfiles.php';
include_once XNEWS_MODULE_PATH . '/class/class.newsstory.php';

$fileid = (isset($_GET['fileid'])) ? intval($_GET['fileid']) : 0;
if (empty($fileid)) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _ERRORS);
    exit();
}
$myts = MyTextSanitizer::getInstance(); // MyTextSanitizer object
$sfiles = new nw_sFiles($fileid);

// Do we have the right to see the file ?
$article = new nw_NewsStory($sfiles->getStoryid());
// and the news, can we see it ?
if ($article->published() == 0 || $article->published() > time()) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _MA_NW_NOSTORY);
    exit();
}
// Expired
if ($article->expired() != 0 && $article->expired() < time()) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _MA_NW_NOSTORY);
    exit();
}


$gperm_handler =& xoops_gethandler('groupperm');
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
if (!$gperm_handler->checkRight('nw_view', $article->topicid(), $groups, $xoopsModule->getVar('mid'))) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _NOPERM);
    exit();
}

$sfiles->updateCounter();
$url = XNEWS_ATTACHED_FILES_URL . '/' . $sfiles->getDownloadname();
if (!preg_match("/^ed2k*:\/\//i", $url)) {
    Header("Location: $url");
}
echo "<html><head><meta http-equiv=\"Refresh\" content=\"0; URL=" . $myts->htmlSpecialChars($url) . "\"></meta></head><body></body></html>";
exit();
