<?php
// $Id: backendt.php 8207 2011-11-07 04:18:27Z beckmi $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
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
/**
 * RSS per topics
 *
 * This script is used to generate RSS feeds for each topic.
 * You can enable and disable this feature with the module's option named "Enable RSS feeds per topics ?"
 * The script uses the permissions to know what to display.
 *
 * @package News
 * @author Xoops Modules Dev Team
 * @copyright (c) The Xoops Project - www.xoops.org
 * @param type $nomvariable description
 */
include_once 'header.php';
include_once XOOPS_ROOT_PATH.'/class/template.php';
include_once NW_MODULE_PATH . '/class/class.newsstory.php';
include_once NW_MODULE_PATH . '/class/class.newstopic.php';
include_once NW_MODULE_PATH . '/include/functions.php';

error_reporting(E_ALL);
$GLOBALS['xoopsLogger']->activated = false;

if(!nw_getmoduleoption('topicsrss', NW_MODULE_DIR_NAME)) {
	exit();
}

$topicid = isset($_GET['topicid']) ? intval($_GET['topicid']) : 0;
if($topicid == 0) {
	exit();
}

if (function_exists('mb_http_output')) {
	mb_http_output('pass');
}

$restricted = nw_getmoduleoption('restrictindex', NW_MODULE_DIR_NAME);
$newsnumber = nw_getmoduleoption('storyhome', NW_MODULE_DIR_NAME);

$charset = 'utf-8';

header ('Content-Type:text/xml; charset='.$charset);
$story = new nw_NewsStory();
$tpl = new XoopsTpl();
$tpl->xoops_setCaching(2);
$tpl->xoops_setCacheTime(3600);								// Change this to the value you want
if (!$tpl->is_cached('db:nw_news_rss.html', $topicid)) {
	$xt = new nw_NewsTopic($topicid);
	$sarray = $story->getAllPublished($newsnumber, 0, $restricted, $topicid);
	if (is_array($sarray) && count($sarray)>0) {
		$sitename = htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES);
		$slogan = htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES);
		$tpl->assign('channel_title', xoops_utf8_encode($sitename));
		$tpl->assign('channel_link', XOOPS_URL.'/');
		$tpl->assign('channel_desc', xoops_utf8_encode($slogan));
		$tpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
		$tpl->assign('channel_webmaster', checkEmail($xoopsConfig['adminmail'],true));	// Fed up with spam
		$tpl->assign('channel_editor', checkEmail($xoopsConfig['adminmail'],true));	// Fed up with spam
		$tpl->assign('channel_category', $xt->topic_title());
		$tpl->assign('channel_generator', 'XOOPS');
		$tpl->assign('channel_language', _LANGCODE);
		$tpl->assign('image_url', XOOPS_URL.'/images/logo.gif');
		$dimention = getimagesize(XOOPS_ROOT_PATH.'/images/logo.gif');
		if (empty($dimention[0])) {
			$width = 88;
		} else {
			$width = ($dimention[0] > 144) ? 144 : $dimention[0];
		}
		if (empty($dimention[1])) {
			$height = 31;
		} else {
			$height = ($dimention[1] > 400) ? 400 : $dimention[1];
		}
		$tpl->assign('image_width', $width);
		$tpl->assign('image_height', $height);
		$count = $sarray;
		foreach ($sarray as $story) {
			$storytitle = $story->title();
			//if we are allowing html, we need to use htmlspecialchars or any bug will break the output
			$description = htmlspecialchars($story->hometext());
			
			// DNPROSSI SEO
			$seo_enabled = nw_getmoduleoption('seo_enable', NW_MODULE_DIR_NAME);
			$item_title = '';
			if ( $seo_enabled != 0 ) $item_title = nw_remove_accents($storytitle);	
            $tpl->append('items', array(
                'title' => XoopsLocal::convert_encoding(htmlspecialchars($storytitle, ENT_QUOTES)) ,
                'link' => nw_seo_UrlGenerator(_MA_NW_SEO_ARTICLES, $story->storyid(), $item_title) ,
                'guid' => $story->nw_stripeKey(md5($story->title().$story->topic_title), 7, 32),
				'category' => XoopsLocal::convert_encoding(htmlspecialchars($story->topic_title, ENT_QUOTES)) ,
                'pubdate' => formatTimestamp($story->published(), 'rss') ,
                'description' => XoopsLocal::convert_encoding(htmlspecialchars($description, ENT_QUOTES))));

		}
	}
}
$tpl->display('db:nw_news_rss.html', $topicid);
?>
