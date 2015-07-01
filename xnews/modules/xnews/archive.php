<?php
// $Id: archive.php 8207 2011-11-07 04:18:27Z beckmi $
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
/*
 * Display a list of all the published articles by month
 *
 * This page is called from the module's main menu.
 * It will shows a list of all the articles by month. We use the module's
 * option named "restrictindex" to show or hide stories according
 * to users permissions
 *
 * @package News
 * @author Xoops Modules Dev Team
 * @copyright	(c) The Xoops Project - www.xoops.org
 *
 * Parameters received by this page :
 * @page_param 	int		year	Optional, the starting year
 * @page_param 	int		month	Optional, the starting month
 *
 * @page_title			"news Archives" - Year - Month - Module's name
 *
 * @template_name		nw_news_archive.html
 *
 * Template's variables :
 * @template_var 	array 	years			Contains all the years we have information for
 *											Structure :
 *												number	int		Year (2004 for example)
 *												months	array	moths in the year (months when we have some articles)
 *													Structure :
 *													string	string	Month's name
 *													number	int		Month's number (between 1 and 12)
 * @template_var 	boolean	show_articles	true or false
 * @template_var	string	lang_articles	Fixed text "Articles"
 * @template_var	array	currentmonth	Label of each month (from january to december)
 * @template_var	int		currentyear		Starting year
 * @template_var	string	lang_actions 	Fixed text "Actions"
 * @template_var	string	lang_date		Fixed text "Date"
 * @template_var	string	lang_views 		Fixed text "Views"
 * @template_var	array	stories			Contains all the stories to display
 *											Structure :
 *											title		string	Contains a link to see the topic and a link (with the story's title) to read the full story
 *											counter		int		Number of views for this article
 *											date		string	Article's publish date
 *											print_link	string	A link to the story's printable version
 *											mail_link	string	A mailto link to mail the story to a friend
 * @template_var	string	lang_printer	Fixed text "Printer Friendly Page"
 * @template_var	string	lang_sendstory	Fixed text "Send this Story to a Friend"
 * @template_var	string  lang_storytotal	Text "There are xx article(s) in total"
 */
######################################################################
# Original version:
# [11-may-2001] Kenneth Lee - http://www.nexgear.com/
######################################################################

include_once 'header.php';
$xoopsOption['template_main'] = 'nw_news_archive.html';
include_once XOOPS_ROOT_PATH.'/header.php';
include_once NW_MODULE_PATH . '/class/class.newsstory.php';
include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/calendar.php';
include_once NW_MODULE_PATH . '/include/functions.php';
$lastyear = 0;
$lastmonth = 0;

$months_arr = array(1 => _CAL_JANUARY, 2 => _CAL_FEBRUARY, 3 => _CAL_MARCH, 4 => _CAL_APRIL, 5 => _CAL_MAY, 6 => _CAL_JUNE, 7 => _CAL_JULY, 8 => _CAL_AUGUST, 9 => _CAL_SEPTEMBER, 10 => _CAL_OCTOBER, 11 => _CAL_NOVEMBER, 12 => _CAL_DECEMBER);

$fromyear = (isset($_GET['year'])) ? intval ($_GET['year']): 0;
$frommonth = (isset($_GET['month'])) ? intval($_GET['month']) : 0;

$pgtitle='';
if($fromyear && $frommonth) {
	$pgtitle=sprintf(" - %d - %d",$fromyear,$frommonth);
}
$infotips=nw_getmoduleoption('infotips', NW_MODULE_DIR_NAME);
$restricted=nw_getmoduleoption('restrictindex', NW_MODULE_DIR_NAME);
$dateformat=nw_getmoduleoption('dateformat', NW_MODULE_DIR_NAME);
if($dateformat == '') {
	$dateformat='m';
}
$myts =& MyTextSanitizer::getInstance();
$xoopsTpl->assign('xoops_pagetitle', $myts->htmlSpecialChars(_MA_NW_NEWSARCHIVES) . $pgtitle . ' - ' . $myts->htmlSpecialChars($xoopsModule->name()));

//DNPROSSI - ADDED
$xoopsTpl->assign('newsmodule_url', NW_MODULE_URL);

$useroffset = '';
if(is_object($xoopsUser)) {
	$timezone = $xoopsUser->timezone();
	if(isset($timezone)){
		$useroffset = $xoopsUser->timezone();
	} else {
		$useroffset = $xoopsConfig['default_TZ'];
	}
}
$result = $xoopsDB->query('SELECT published FROM '.$xoopsDB->prefix('nw_stories').' WHERE (published>0 AND published<='.time().') AND (expired = 0 OR expired <= '.time().') ORDER BY published DESC');
if (!$result) {
	echo _ERRORS;
	exit();
} else {
	$years = array();
	$months = array();
	$i = 0;
	while (list($time) = $xoopsDB->fetchRow($result)) {
		$time = formatTimestamp($time, 'mysql', $useroffset);
			if (preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/", $time, $datetime)) {
				$this_year  = intval($datetime[1]);
				$this_month = intval($datetime[2]);
			if (empty($lastyear)) {
				$lastyear = $this_year;
			}
			if ($lastmonth == 0) {
				$lastmonth = $this_month;
				$months[$lastmonth]['string'] = $months_arr[$lastmonth];
				$months[$lastmonth]['number'] = $lastmonth;
			}
			if ($lastyear != $this_year) {
				$years[$i]['number'] = $lastyear;
				$years[$i]['months'] = $months;
				$months = array();
				$lastmonth = 0;
				$lastyear = $this_year;
				$i++;
			}
			if ($lastmonth != $this_month) {
				$lastmonth = $this_month;
				$months[$lastmonth]['string'] = $months_arr[$lastmonth];
				$months[$lastmonth]['number'] = $lastmonth;
			}
		}
	}
	
	$years[$i]['number'] = $this_year;
	$years[$i]['months'] = $months;
	$xoopsTpl->assign('years', $years);
}

if ($fromyear != 0 && $frommonth != 0) {
	$xoopsTpl->assign('show_articles', true);
	$xoopsTpl->assign('lang_articles', _MA_NW_ARTICLES);
	$xoopsTpl->assign('currentmonth', $months_arr[$frommonth]);
	$xoopsTpl->assign('currentyear', $fromyear);
	$xoopsTpl->assign('lang_actions', _MA_NW_ACTIONS);
	$xoopsTpl->assign('lang_date', _MA_NW_DATE);
	$xoopsTpl->assign('lang_views', _MA_NW_VIEWS);

	// must adjust the selected time to server timestamp
	$timeoffset = $useroffset - $xoopsConfig['server_TZ'];
	$monthstart = mktime(0 - $timeoffset, 0, 0, $frommonth, 1, $fromyear);
	$monthend = mktime(23 - $timeoffset, 59, 59, $frommonth + 1, 0, $fromyear);
	$monthend = ($monthend > time()) ? time() : $monthend;

	$count=0;
	$news = new nw_NewsStory();
	$storyarray = $news->getArchive($monthstart, $monthend, $restricted);
	$count=count($storyarray);
	if(is_array($storyarray) && $count>0) {
		foreach ($storyarray as $article) {
	    	$story = array();
			$htmltitle='';
			if($infotips>0) {
				$story['infotips'] = nw_make_infotips($article->hometext());
				$htmltitle=' title="'.$story['infotips'].'"';
			}
	    	$seo_enabled = nw_getmoduleoption('seo_enable', NW_MODULE_DIR_NAME);
			//DNPROSSI SEO
			$cat_path = '';
			$item_path = '';
			$print_item = '';
			if ( $seo_enabled != 0 ) {
				$cat_path = nw_remove_accents($article->topic_title());
				$item_path = nw_remove_accents($article->title());
				$print_item = nw_remove_accents(_MA_NW_PRINTERFRIENDLY);		
			}
			$story['title'] = "<a href='" . nw_seo_UrlGenerator(_MA_NW_SEO_TOPICS, $article->topicid(), $cat_path) . "'>" . $article->topic_title() . "</a>";
			$story['title'] .= ": <a href='" . nw_seo_UrlGenerator(_MA_NW_SEO_ARTICLES, $article->storyid(), $item_path) . "'" . $htmltitle . ">" . $article->title() . "</a>"; 
			$story['print_link'] = nw_seo_UrlGenerator(_MA_NW_SEO_PRINT, $article->storyid(), $print_item);			
			
	    	$story['counter'] = $article->counter();
	    	$story['date'] = formatTimestamp($article->published(),$dateformat,$useroffset);
	    	$story['mail_link'] = 'mailto:?subject='.sprintf(_MA_NW_INTARTICLE, $xoopsConfig['sitename']).'&amp;body='.sprintf(_MA_NW_INTARTFOUND, $xoopsConfig['sitename']).':  ' . NW_MODULE_URL . '/article.php?storyid='.$article->storyid();
	    	$xoopsTpl->append('stories', $story);
		}
	}
	$xoopsTpl->assign('lang_printer', _MA_NW_PRINTERFRIENDLY);
	$xoopsTpl->assign('lang_sendstory', _MA_NW_SENDSTORY);
	$xoopsTpl->assign('lang_storytotal', sprintf(_MA_NW_THEREAREINTOTAL, $count));
} else {
    $xoopsTpl->assign('show_articles', false);
}

$xoopsTpl->assign('lang_newsarchives', _MA_NW_NEWSARCHIVES);

/**
* Create the meta datas
*/
nw_CreateMetaDatas();

include_once XOOPS_ROOT_PATH.'/footer.php';
?>
