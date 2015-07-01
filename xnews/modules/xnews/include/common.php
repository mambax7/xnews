<?php
defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

// common Xoops stuff
xoops_load('XoopsFormLoader');
xoops_load('XoopsPageNav');
xoops_load('XoopsUserUtility');
xoops_load('XoopsLocal');
xoops_load('XoopsRequest');

// MyTextSanitizer object
$myts = MyTextSanitizer::getInstance();

// load Xoops handlers
$module_handler = xoops_gethandler('module');
$member_handler = xoops_gethandler('member');
$notification_handler = &xoops_gethandler('notification');
$groupperm_handler = xoops_gethandler('groupperm');

// common module_skeleton stuff
define('XNEWS_SUBPREFIX', 'nw');
define('XNEWS_MODULE_DIRNAME', 'xnews');
define('XNEWS_MODULE_PATH', XOOPS_ROOT_PATH . '/modules/' . XNEWS_MODULE_DIRNAME);
define('XNEWS_MODULE_URL', XOOPS_URL . '/modules/' . XNEWS_MODULE_DIRNAME);
define('XNEWS_UPLOADS_NEWS_PATH', XOOPS_ROOT_PATH . '/uploads/' . XNEWS_MODULE_DIRNAME);
define('XNEWS_TOPICS_FILES_PATH', XOOPS_ROOT_PATH . '/uploads/' . XNEWS_MODULE_DIRNAME . '/topics');
define('XNEWS_ATTACHED_FILES_PATH', XOOPS_ROOT_PATH . '/uploads/' . XNEWS_MODULE_DIRNAME . '/attached');
define('XNEWS_TOPICS_FILES_URL', XOOPS_URL . '/uploads/' . XNEWS_MODULE_DIRNAME . '/topics');
define('XNEWS_ATTACHED_FILES_URL', XOOPS_URL . '/uploads/' . XNEWS_MODULE_DIRNAME . '/attached');
xoops_loadLanguage('common', XNEWS_MODULE_DIRNAME);

include_once XNEWS_MODULE_PATH . '/include/functions.php';
include_once XNEWS_MODULE_PATH . '/include/constants.php';

include_once XNEWS_MODULE_PATH . '/class/session.php'; // XnewsSession class
include_once XNEWS_MODULE_PATH . '/class/xnews.php'; // XnewsXnews class
//include_once XNEWS_MODULE_PATH . '/class/common/breadcrumb.php'; // XnewsBreadcrumb class
//include_once XNEWS_MODULE_PATH . '/class/common/choicebyletter.php'; // XnewsChoiceByLetter class
include_once XNEWS_MODULE_PATH . '/class/common/tree.php'; // xnews_MyXoopsObjectTree class
//include_once XNEWS_MODULE_PATH . '/class/common/uploader.php'; // XnewsMediaUploader class

$debug = false;
$xnews = XnewsXnews::getInstance($debug);

// this is needed or it will not work in blocks
global $xnews_isAdmin;

// load only if module is installed
if (is_object($xnews->getModule())) {
    // find if the user is admin of the module
    $xnews_isAdmin = xnews_userIsAdmin();
}

include_once XNEWS_MODULE_PATH . '/include/config.php';