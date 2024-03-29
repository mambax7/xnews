<?php

use XoopsModules\Xnews;

defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

require_once dirname(__DIR__) . '/preloads/autoloader.php';

// common Xoops stuff
xoops_load('XoopsFormLoader');
xoops_load('XoopsPageNav');
xoops_load('XoopsUserUtility');
xoops_load('XoopsLocal');

$moduleDirName = basename(dirname(__DIR__));

// require_once  dirname(__DIR__) . '/class/Helper.php';
// require_once  dirname(__DIR__) . '/class/Utility.php';

$db = \XoopsDatabaseFactory::getDatabaseConnection();
/** @var Xnews\Helper $helper */
$helper = Xnews\Helper::getInstance();
/** @var Xnews\Utility $utility */
$utility = new Xnews\Utility();

// MyTextSanitizer object
$myts = \MyTextSanitizer::getInstance();

// load Xoops handlers
/** @var \XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
/** @var \XoopsMemberHandler $memberHandler */
$memberHandler = xoops_getHandler('member');
/** @var \XoopsNotificationHandler $notificationHandler */
$notificationHandler = xoops_getHandler('notification');
/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler = xoops_getHandler('groupperm');

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

require_once XNEWS_MODULE_PATH . '/include/functions.php';
require_once XNEWS_MODULE_PATH . '/include/constants.php';

// require_once XNEWS_MODULE_PATH . '/class/session.php'; // Session class
// require_once XNEWS_MODULE_PATH . '/class/xnews.php'; // Xnews\Helper class
// require_once XNEWS_MODULE_PATH . '/class/Helper.php';
//require_once XNEWS_MODULE_PATH . '/class/common/breadcrumb.php'; // XnewsBreadcrumb class
//require_once XNEWS_MODULE_PATH . '/class/common/choicebyletter.php'; // XnewsChoiceByLetter class
// require_once XNEWS_MODULE_PATH . '/class/common/tree.php'; // xnews_MyXoopsObjectTree class
//require_once XNEWS_MODULE_PATH . '/class/common/uploader.php'; // XnewsMediaUploader class

$debug  = false;
$helper = Xnews\Helper::getInstance($debug);

// this is needed or it will not work in blocks
global $xnews_isAdmin;

// load only if module is installed
if (is_object($helper->getModule())) {
    // find if the user is admin of the module
    $xnews_isAdmin = xnews_userIsAdmin();
}
if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof \XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $GLOBALS['xoopsTpl'] = new \XoopsTpl();
}

require_once XNEWS_MODULE_PATH . '/config/config.php';
