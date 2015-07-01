<?php
// $Id: news_bigstory.php 8207 2011-11-07 04:18:27Z beckmi $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
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
if (!defined('XOOPS_ROOT_PATH')) {
	die('XOOPS root path not defined');
}

/**
 * Solves issue when upgrading xoops version
 * Paths not set and block would not work
*/
if (!defined('NW_MODULE_PATH')) {
	define("NW_SUBPREFIX", "nw");
	define("NW_MODULE_DIR_NAME", "xnews");
	define("NW_MODULE_PATH", XOOPS_ROOT_PATH . "/modules/" . NW_MODULE_DIR_NAME);
	define("NW_MODULE_URL", XOOPS_URL . "/modules/" . NW_MODULE_DIR_NAME);
	define("NW_UPLOADS_NEWS_PATH", XOOPS_ROOT_PATH . "/uploads/" . NW_MODULE_DIR_NAME);
	define("NW_TOPICS_FILES_PATH", XOOPS_ROOT_PATH . "/uploads/" . NW_MODULE_DIR_NAME . "/topics");
	define("NW_ATTACHED_FILES_PATH", XOOPS_ROOT_PATH . "/uploads/" . NW_MODULE_DIR_NAME . "/attached");
	define("NW_TOPICS_FILES_URL", XOOPS_URL . "/uploads/" . NW_MODULE_DIR_NAME . "/topics");
	define("NW_ATTACHED_FILES_URL", XOOPS_URL . "/uploads/" . NW_MODULE_DIR_NAME . "/attached");
}

function nw_b_news_bigstory_show() {
	include_once NW_MODULE_PATH . '/include/functions.php';
    include_once NW_MODULE_PATH . '/class/class.newsstory.php';
    $myts =& MyTextSanitizer::getInstance();
	$restricted=nw_getmoduleoption('restrictindex', NW_MODULE_DIR_NAME);
	$dateformat=nw_getmoduleoption('dateformat', NW_MODULE_DIR_NAME);
	$infotips=nw_getmoduleoption('infotips', NW_MODULE_DIR_NAME);

	$block = array();
    $onestory = new nw_NewsStory();
	$stories = $onestory->getBigStory(1,0,$restricted,0,1, true, 'counter');
	if(count($stories)==0) {
		$block['message'] = _MB_NW_NOTYET;
	} else {
		foreach ( $stories as $key => $story ) {
			$htmltitle='';
			if($infotips>0) {
				$block['infotips'] = nw_make_infotips($story->hometext());
				$htmltitle=' title="'.$block['infotips'].'"';
			}
			//DNPROSSI ADDED
	        $block['newsmodule_url']= NW_MODULE_URL;
	        
			$block['htmltitle']=$htmltitle;
			$block['message'] = _MB_NW_TMRSI;
			$block['story_title'] = $story->title('Show');
			$block['story_id'] = $story->storyid();
			$block['story_date'] = formatTimestamp($story->published(), $dateformat);
			$block['story_hits'] = $story->counter();
            $block['story_rating'] = $story->rating();
            $block['story_votes'] = $story->votes();
            $block['story_author']= $story->uname();
            $block['story_text']= $story->hometext();
            $block['story_topic_title']= $story->topic_title();
            $block['story_topic_color']= '#'.$myts->displayTarea($story->topic_color);
		}
	}
	
	// DNPROSSI SEO
    $seo_enabled = nw_getmoduleoption('seo_enable', NW_MODULE_DIR_NAME);
	if ( $seo_enabled != 0 ) {
		$block['urlrewrite']= "true";
	} else { 
		$block['urlrewrite']= "false"; 
	}  
	
	return $block;
}

function nw_b_news_bigstory_onthefly($options)
{
	$options = explode('|',$options);
	$block = & nw_b_news_bigstory_show($options);

	$tpl = new XoopsTpl();
	$tpl->assign('block', $block);
	$tpl->display('db:nw_news_block_bigstory.html');
}

?>
