<?php
// $Id: news_moderate.php 8207 2011-11-07 04:18:27Z beckmi $
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

/**
 * Dispay a block where news moderators can show news that need to be moderated.
 */
function nw_b_news_topics_moderate() {
	include_once NW_MODULE_PATH . '/class/class.newsstory.php';
	include_once NW_MODULE_PATH . '/include/functions.php';
	$block = array();
	$dateformat=nw_getmoduleoption('dateformat', NW_MODULE_DIR_NAME);
	$infotips=nw_getmoduleoption('infotips', NW_MODULE_DIR_NAME);

    $storyarray = nw_NewsStory :: getAllSubmitted(0, true, nw_getmoduleoption('restrictindex', NW_MODULE_DIR_NAME));
    if ( count( $storyarray ) > 0 )
    {
		$block['lang_story_title'] = _MB_NW_TITLE;
		$block['lang_story_date'] = _MB_NW_POSTED;
		$block['lang_story_author'] =_MB_NW_POSTER;
		$block['lang_story_action'] =_MB_NW_ACTION;
		$block['lang_story_topic'] =_MB_NW_TOPIC;
		$myts =& MyTextSanitizer::getInstance();
        foreach( $storyarray as $newstory )
        {
            $title = $newstory -> title();
			$htmltitle='';
			if($infotips>0) {
				$story['infotips'] = nw_make_infotips($newstory->hometext());
				$htmltitle=' title="'.$story['infotips'].'"';
			}

            if (!isset( $title ) || ($title == '')) {
                $linktitle = "<a href='" . NW_MODULE_URL . "/index.php?op=edit&amp;storyid=" . $newstory->storyid() . "' target='_blank'".$htmltitle.">" . _AD_NOSUBJECT . "</a>";
            } else {
                $linktitle = "<a href='" . NW_MODULE_URL . "/submit.php?op=edit&amp;storyid=" . $newstory->storyid() . "' target='_blank'".$htmltitle.">" . $title . "</a>";
            }
			$story=array();
            $story['title'] = $linktitle;
            $story['date'] = formatTimestamp($newstory->created(),$dateformat);
            $story['author'] = "<a href='" . XOOPS_URL . "/userinfo.php?uid=" . $newstory -> uid() . "'>" . $newstory->uname() . "</a>";
            $story['action'] = "<a href='" . NW_MODULE_URL . "/admin/index.php?op=edit&amp;storyid=" . $newstory->storyid() . "'>" . _EDIT. "</a> - <a href='" . NW_MODULE_URL . "/admin/index.php?op=delete&amp;storyid=" . $newstory->storyid() . "'>" . _MB_NW_DELETE . "</a>";
            $story['topic_title'] = $newstory->topic_title();
            $story['topic_color']= '#'.$myts->displayTarea($newstory->topic_color);
            $block['stories'][] =& $story;
            unset($story);
        }
    }
	return $block;
}

function nw_b_news_topics_moderate_onthefly($options)
{
	$options = explode('|',$options);
	$block = & nw_b_news_topics_moderate($options);

	$tpl = new XoopsTpl();
	$tpl->assign('block', $block);
	$tpl->display('db:nw_news_block_moderate.html');
}
?>
