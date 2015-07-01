<?php
// $Id: news_topics.php 8207 2011-11-07 04:18:27Z beckmi $
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

function nw_b_news_topics_show() {
	global $topic_id;	// Don't know why this is used and where it's coming from ....
    include_once NW_MODULE_PATH . '/include/functions.php';
    include_once NW_MODULE_PATH . '/class/class.newstopic.php';
	include_once NW_MODULE_PATH . "/class/tree.php";

	$jump = NW_MODULE_URL . '/index.php?topic_id=';
	$topic_id = !empty($topic_id) ? intval($topic_id) : 0;
	$restricted = nw_getmoduleoption('restrictindex', NW_MODULE_DIR_NAME);

	$xt = new nw_NewsTopic();
	$allTopics = $xt->getAllTopics($restricted);
	$topic_tree = new nw_MyXoopsObjectTree($allTopics, 'topic_id', 'topic_pid');
	$additional = " onchange='location=\"".$jump."\"+this.options[this.selectedIndex].value'";
	$block['selectbox'] = $topic_tree->makeSelBox('topic_id', 'topic_title', '-- ', '', true, 0, $additional);
	
	//DNPROSSI ADDED
	$block['newsmodule_url']= NW_MODULE_URL;
	
	return $block;
}


function nw_b_news_topics_onthefly($options)
{
	$options = explode('|',$options);
	$block = & nw_b_news_topics_show($options);

	$tpl = new XoopsTpl();
	$tpl->assign('block', $block);
	$tpl->display('db:nw_news_block_topics.html');
}

?>
