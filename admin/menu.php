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

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

// require_once  dirname(__DIR__) . '/class/Helper.php';
//require_once  dirname(__DIR__) . '/include/common.php';
$helper = Xnews\Helper::getInstance();

$pathIcon32    = \Xmf\Module\Admin::menuIconPath('');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

$adminmenu[] = [
    'title'      => _MAIN,
    'link'       => 'admin/index.php',
    'icon'       => $pathIcon32 . '/home.png',
    'icon_small' => 'assets/images/topics32.png',
];

$adminmenu[] = [
    'title'      => _MI_XNEWS_ADMENU2,
    'link'       => 'admin/index.php?op=topicsmanager',
    'icon'       => 'assets/images/topics32.png',
    'icon_small' => 'assets/images/topics32.png',
];

$adminmenu[] = [
    'title'      => _MI_XNEWS_ADMENU3,
    'link'       => 'admin/index.php?op=newarticle',
    'icon'       => 'assets/images/newarticle32.png',
    'icon_small' => 'assets/images/newarticle32.png',
];

$adminmenu[] = [
    'title'      => _MI_XNEWS_GROUPPERMS,
    'link'       => 'admin/permissions.php',
    'icon'       => $pathIcon32 . '/permissions.png',
    'icon_small' => $pathIcon32 . '/permissions.png',
];

$adminmenu[] = [
    'title'      => _MI_XNEWS_PRUNENEWS,
    'link'       => 'admin/prune.php',
    'icon'       => 'assets/images/prune32.png',
    'icon_small' => 'assets/images/prune32.png',
];

$adminmenu[] = [
    'title'      => _MI_XNEWS_EXPORT,
    'link'       => 'admin/export.php',
    'icon'       => 'assets/images/export32.png',
    'icon_small' => 'assets/images/export32.png',
];

$adminmenu[] = [
    'title'      => _MI_XNEWS_STATS,
    'link'       => 'admin/stats.php',
    'icon'       => 'assets/images/stats32.png',
    'icon_small' => 'assets/images/stats32.png',
];

$adminmenu[] = [
    'title'      => _MI_XNEWS_NEWSLETTER,
    'link'       => 'admin/newsletter.php',
    'icon'       => 'assets/images/newsletter32.png',
    'icon_small' => 'assets/images/newsletter32.png',
];

$adminmenu[] = [
    'title'      => _MI_XNEWS_METAGEN,
    'link'       => 'admin/metagen.php',
    'icon'       => 'assets/images/metagen32.png',
    'icon_small' => 'assets/images/metagen32.png',
];

$adminmenu[] = [
    'title'      => _MI_XNEWS_CLONER,
    'link'       => 'admin/clone.php',
    'icon'       => 'assets/images/cloner32.png',
    'icon_small' => 'assets/images/cloner32.png',
];

$adminmenu[] = [
    'title' => _MI_XNEWS_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png'
];
