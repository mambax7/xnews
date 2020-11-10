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
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

/*
 * Created on 5 nov. 2006
 *
 * This page is used to display a maps of the topics (with articles count)
 *
 * @package News
 * @author Instant Zero
 * @copyright (c) Instant Zero - http://xoops.instant-zero.com
 */

use XoopsModules\Xnews;

require_once __DIR__ . '/header.php';

// require_once XNEWS_MODULE_PATH . '/class/NewsStory.php';
require_once XNEWS_MODULE_PATH . '/class/NewsTopic.php';

$GLOBALS['xoopsOption']['template_main'] = 'xnews_topics_directory.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

//DNPROSSI SEO
$seo_enabled = $helper->getConfig('seo_enable');

$newscountbytopic = $tbl_topics = [];
$perms            = '';
$xt               = new Xnews\NewsTopic();
$restricted       = $helper->getConfig('restrictindex');
if ($restricted) {
    global $xoopsUser;
    /** @var \XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $newsModule    = $moduleHandler->getByDirname(XNEWS_MODULE_DIRNAME);
    $groups        = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    /** @var \XoopsGroupPermHandler $grouppermHandler */
    $grouppermHandler = xoops_getHandler('groupperm');
    $topics           = $grouppermHandler->getItemIds('nw_view', $groups, $newsModule->getVar('mid'));
    if (count($topics) > 0) {
        $topics = implode(',', $topics);
        $perms  = ' AND topic_id IN (' . $topics . ') ';
    } else {
        return '';
    }
}
$topics_arr       = $xt->getChildTreeArray(0, 'topic_title', $perms);
$newscountbytopic = $xt->getnwCountByTopic();
if (is_array($topics_arr) && count($topics_arr)) {
    foreach ($topics_arr as $onetopic) {
        $count = 0;
        if (array_key_exists($onetopic['topic_id'], $newscountbytopic)) {
            $count = $newscountbytopic[$onetopic['topic_id']];
        }
        if (0 != $onetopic['topic_pid']) {
            $onetopic['prefix'] = str_replace('.', '-', $onetopic['prefix']) . '&nbsp;';
        } else {
            $onetopic['prefix'] = str_replace('.', '', $onetopic['prefix']);
        }

        //DNPROSSI SEO
        $cat_path = '';
        if (0 != $seo_enabled) {
            $cat_path = nw_remove_accents($onetopic['topic_title']);
        }
        $topic_link = "<a href='" . nw_seo_UrlGenerator(_MD_XNEWS_SEO_TOPICS, $onetopic['topic_id'], $cat_path) . "'>" . $onetopic['topic_title'] . '</a>';

        $tbl_topics[] = [
            'id'          => $onetopic['topic_id'],
            'nw_count'    => $count,
            'topic_color' => '#' . $onetopic['topic_color'],
            'prefix'      => $onetopic['prefix'],
            'title'       => $myts->displayTarea($onetopic['topic_title']),
            'topic_link'  => $topic_link,
        ];
    }
}
$xoopsTpl->assign('topics', $tbl_topics);

$xoopsTpl->assign('advertisement', $helper->getConfig('advertisement'));

/**
 * Manage all the meta datas
 */
nw_CreateMetaDatas();

$xoopsTpl->assign('xoops_pagetitle', _AM_XNEWS_TOPICS_DIRECTORY);
$meta_description = _AM_XNEWS_TOPICS_DIRECTORY . ' - ' . $myts->htmlSpecialChars($xoopsModule->name());
if (isset($xoTheme) && is_object($xoTheme)) {
    $xoTheme->addMeta('meta', 'description', $meta_description);
} else {    // Compatibility for old Xoops versions
    $xoopsTpl->assign('xoops_meta_description', $meta_description);
}

require_once XOOPS_ROOT_PATH . '/footer.php';
