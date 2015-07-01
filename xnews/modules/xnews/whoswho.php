<?php
/*
 * Created on 28 oct. 2006
 *
 * This page will display a list of the authors of the site
 *
 * @package News
 * @author Instant Zero
 * @copyright (c) Instant Zero - http://www.instant-zero.com
 */
include_once __DIR__ . '/header.php';

include_once XNEWS_MODULE_PATH . '/class/class.newsstory.php';
include_once XNEWS_MODULE_PATH . '/class/class.newstopic.php';
include_once XNEWS_MODULE_PATH . '/class/class.sfiles.php';

if (!$xnews->getConfig('newsbythisauthor')) {
    redirect_header('index.php',3,_ERRORS);
    exit();
}

$xoopsOption['template_main'] = 'nw_news_whos_who.html';
include_once XOOPS_ROOT_PATH . '/header.php';

$option =  $xnews->getConfig('displayname');
$article = new nw_NewsStory();
$uid_ids = array();
$uid_ids = $article->getWhosWho( $xnews->getConfig('restrictindex'));
if (count($uid_ids) > 0) {
    $lst_uid = implode(',', $uid_ids);
    $member_handler =& xoops_gethandler('member');
    $critere = new Criteria('uid', '(' . $lst_uid . ')', 'IN');
    $tbl_users = $member_handler->getUsers($critere);
    foreach($tbl_users as $one_user) {
        $uname = '';
        switch($option) {
            case 1: // Username
                $uname = $one_user->getVar('uname');
                break;
            case 2: // Display full name (if it is not empty)
                if (xoops_trim($one_user->getVar('name')) != '') {
                    $uname = $one_user->getVar('name');
                } else {
                    $uname = $one_user->getVar('uname');
                }
                break;
        }
        $xoopsTpl->append('whoswho', array('uid' => $one_user->getVar('uid'), 'name' => $uname, 'user_avatarurl' => XOOPS_URL . '/uploads/' . $one_user->getVar('user_avatar')));
    }
}
//DNPROSSI - ADDED
$xoopsTpl->assign('newsmodule_url', XNEWS_MODULE_URL);

$xoopsTpl->assign('advertisement',  $xnews->getConfig('advertisement'));

/**
 * Manage all the meta datas
 */
nw_CreateMetaDatas($article);

$xoopsTpl->assign('xoops_pagetitle', _AM_NW_WHOS_WHO);
$myts =& MyTextSanitizer::getInstance();
$meta_description = _AM_NW_WHOS_WHO . ' - ' . $myts->htmlSpecialChars($xoopsModule->name());
if (isset($xoTheme) && is_object($xoTheme)) {
    $xoTheme->addMeta( 'meta', 'description', $meta_description);
} else {    // Compatibility for old Xoops versions
    $xoopsTpl->assign('xoops_meta_description', $meta_description);
}

include_once XOOPS_ROOT_PATH . '/footer.php';
