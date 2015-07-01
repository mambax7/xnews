<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                  Copyright (c) 2005-2006 Instant Zero                     //
//                     <http://xoops.instant-zero.com/>                      //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

/*
 * Created on 5 nov. 2006
 *
 * This page is used to display a maps of the topics (with articles count)
 *
 * @package News
 * @author Instant Zero
 * @copyright (c) Instant Zero - http://xoops.instant-zero.com
 */
include_once 'header.php';
include_once NW_MODULE_PATH . '/class/class.newsstory.php';
include_once NW_MODULE_PATH . '/class/class.newstopic.php';
include_once NW_MODULE_PATH . '/include/functions.php';

$xoopsOption['template_main'] = 'nw_news_topics_directory.html';
include_once XOOPS_ROOT_PATH.'/header.php';

//DNPROSSI SEO
$seo_enabled = nw_getmoduleoption('seo_enable', NW_MODULE_DIR_NAME);

$myts =& MyTextSanitizer::getInstance();

$newscountbytopic = $tbl_topics = array();
$perms = '';
$xt = new nw_NewsTopic();
$restricted = nw_getmoduleoption('restrictindex', NW_MODULE_DIR_NAME);
if ($restricted) {
	global $xoopsUser;
    $module_handler =& xoops_gethandler('module');
    $newsModule =& $module_handler->getByDirname(NW_MODULE_DIR_NAME);
    $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gperm_handler =& xoops_gethandler('groupperm');
    $topics = $gperm_handler->getItemIds('nw_view', $groups, $newsModule->getVar('mid'));
    if(count($topics) >0 ) {
       	$topics = implode(',', $topics);
       	$perms = ' AND topic_id IN ('.$topics.') ';
    } else {
    	return '';
    }
}
$topics_arr = $xt->getChildTreeArray(0, 'topic_title', $perms);
$newscountbytopic = $xt->getnwCountByTopic();
if(is_array($topics_arr) && count($topics_arr))	{
	foreach ($topics_arr as $onetopic) {
		$count = 0;
		if(array_key_exists($onetopic['topic_id'],$newscountbytopic)) {
			$count = $newscountbytopic[$onetopic['topic_id']];
		}
		if($onetopic['topic_pid'] != 0) {
			$onetopic['prefix'] = str_replace('.','-',$onetopic['prefix']) . '&nbsp;';
		} else {
			$onetopic['prefix'] = str_replace('.','',$onetopic['prefix']);
		}
		
		//DNPROSSI SEO
		$cat_path = '';
		if ( $seo_enabled != 0 ) { $cat_path = nw_remove_accents($onetopic['topic_title']); }
		$topic_link = "<a href='" . nw_seo_UrlGenerator(_MA_NW_SEO_TOPICS, $onetopic['topic_id'], $cat_path) . "'>" . $onetopic['topic_title'] . "</a>";
			
	    $tbl_topics[] = array('id'=>$onetopic['topic_id'], 'nw_count'=>$count, 'topic_color'=>'#'.$onetopic['topic_color'], 'prefix'=>$onetopic['prefix'], 'title'=>$myts->displayTarea($onetopic['topic_title']), 'topic_link'=>$topic_link);
	}
}
$xoopsTpl->assign('topics', $tbl_topics);

$xoopsTpl->assign('advertisement', nw_getmoduleoption('advertisement', NW_MODULE_DIR_NAME));

/**
 * Manage all the meta datas
 */
nw_CreateMetaDatas();

$xoopsTpl->assign('xoops_pagetitle', _AM_NW_TOPICS_DIRECTORY);
$meta_description = _AM_NW_TOPICS_DIRECTORY . ' - '.$myts->htmlSpecialChars($xoopsModule->name());
if(isset($xoTheme) && is_object($xoTheme)) {
	$xoTheme->addMeta( 'meta', 'description', $meta_description);
} else {	// Compatibility for old Xoops versions
	$xoopsTpl->assign('xoops_meta_description', $meta_description);
}

include_once XOOPS_ROOT_PATH.'/footer.php';
?>
