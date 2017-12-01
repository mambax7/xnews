<?php
defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

use Xoopsmodules\xnews;

require_once __DIR__ . '/../class/Helper.php';
//require_once __DIR__ . '/../include/common.php';
$helper = xnews\Helper::getInstance();

$pathIcon32    = \Xmf\Module\Admin::menuIconPath('');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

$adminmenu[] = [
    'title'      => _MAIN,
    'link'       => 'admin/index.php',
    'icon'       => $pathIcon32 . '/home.png',
    'icon_small' => 'assets/images/topics32.png',
];

$adminmenu[] = [
    'title'      => _MI_NW_ADMENU2,
    'link'       => 'admin/index.php?op=topicsmanager',
    'icon'       => 'assets/images/topics32.png',
    'icon_small' => 'assets/images/topics32.png',
];

$adminmenu[] = [
    'title'      => _MI_NW_ADMENU3,
    'link'       => 'admin/index.php?op=newarticle',
    'icon'       => 'assets/images/newarticle32.png',
    'icon_small' => 'assets/images/newarticle32.png',
];

$adminmenu[] = [
    'title'      => _MI_NW_GROUPPERMS,
    'link'       => 'admin/permissions.php',
    'icon'       => $pathIcon32 . '/permissions.png',
    'icon_small' => $pathIcon32 . '/permissions.png',
];

$adminmenu[] = [
    'title'      => _MI_NW_PRUNENEWS,
    'link'       => 'admin/prune.php',
    'icon'       => 'assets/images/prune32.png',
    'icon_small' => 'assets/images/prune32.png',
];

$adminmenu[] = [
    'title'      => _MI_NW_EXPORT,
    'link'       => 'admin/export.php',
    'icon'       => 'assets/images/export32.png',
    'icon_small' => 'assets/images/export32.png',
];

$adminmenu[] = [
    'title'      => _MI_NW_STATS,
    'link'       => 'admin/stats.php',
    'icon'       => 'assets/images/stats32.png',
    'icon_small' => 'assets/images/stats32.png',
];

$adminmenu[] = [
    'title'      => _MI_NW_NEWSLETTER,
    'link'       => 'admin/newsletter.php',
    'icon'       => 'assets/images/newsletter32.png',
    'icon_small' => 'assets/images/newsletter32.png',
];

$adminmenu[] = [
    'title'      => _MI_NW_METAGEN,
    'link'       => 'admin/metagen.php',
    'icon'       => 'assets/images/metagen32.png',
    'icon_small' => 'assets/images/metagen32.png',
];

$adminmenu[] = [
    'title'      => _MI_NW_CLONER,
    'link'       => 'admin/clone.php',
    'icon'       => 'assets/images/cloner32.png',
    'icon_small' => 'assets/images/cloner32.png',
];

$adminmenu[] = [
    'title' => _MI_NW_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png'
];
