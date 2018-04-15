<?php

use XoopsModules\Xnews;

require_once  dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
//require_once $GLOBALS['xoops']->path('www/class/xoopsformloader.php');
// require_once  dirname(__DIR__) . '/class/Utility.php';
require_once  dirname(__DIR__) . '/include/common.php';

$moduleDirName = basename(dirname(__DIR__));
/** @var \XoopsModules\Xnews\Helper $helper */
$helper      = Xnews\Helper::getInstance();
$adminObject = \Xmf\Module\Admin::getInstance();

$pathIcon16    = \Xmf\Module\Admin::iconUrl('', 16);
$pathIcon32    = \Xmf\Module\Admin::iconUrl('', 32);
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('main');

$myts = \MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new \XoopsTpl();
}
