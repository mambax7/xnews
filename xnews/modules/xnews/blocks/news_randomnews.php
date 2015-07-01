<?php
// $Id: news_randomnews.php 8207 2011-11-07 04:18:27Z beckmi $
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

include_once NW_MODULE_PATH . '/class/class.newsstory.php';

function nw_b_news_randomnews_show($options) {
    include_once NW_MODULE_PATH . '/include/functions.php';
    $myts =& MyTextSanitizer::getInstance();
    $block = array();
	$block['sort']=$options[0];
	
	//DNPROSSI Added - xlanguage installed and active 
	$module_handler =& xoops_gethandler('module');
	$xlanguage = $module_handler->getByDirname('xlanguage');
	if ( is_object($xlanguage) && $xlanguage->getVar('isactive') == true ) 
	{ $xlang = true; } else { $xlang = false; } 
	
	$tmpstory = new nw_NewsStory;
	$restricted = nw_getmoduleoption('restrictindex', NW_MODULE_DIR_NAME);
	$dateformat = nw_getmoduleoption('dateformat', NW_MODULE_DIR_NAME);
	$infotips = nw_getmoduleoption('infotips', NW_MODULE_DIR_NAME);
	if($dateformat == '') {
		$dateformat = 's';
	}
    if ($options[4] == 0) {
        $stories = $tmpstory->getRandomNews($options[1],0,$restricted,0,1, $options[0]);
    } else {
        $topics = array_slice($options, 4);
        $stories = $tmpstory->getRandomNews($options[1],0,$restricted,$topics, 1, $options[0]);
    }
	unset($tmpstory);
    if(count($stories)==0) {
    	return '';
    }
    foreach ( $stories as $story ) {
        $news = array();
        $title = $story->title();
		if (strlen($title) > $options[2]) {
			//DNPROSSI Added - xlanguage installed and active 
			$title = $thisstory->hometext;
		
			if ( $xlang == true )
			{ 
				include_once XOOPS_ROOT_PATH.'/modules/xlanguage/include/functions.php';
				$title = xlanguage_ml($title); 
			} 
			
			//DNPROSSI changed xoops_substr to mb_substr for utf-8 support
			$title = mb_substr($title,0,$options[2]+3, 'UTF-8');
		}
		$news['title'] = $title;
        $news['id'] = $story->storyid();
        $news['date'] = formatTimestamp($story->published(), $dateformat);
        $news['hits'] = $story->counter();
        $news['rating'] = $story->rating();
        $news['votes'] = $story->votes();
        $news['author']= sprintf("%s %s",_POSTEDBY,$story->uname());
        $news['topic_title'] = $story->topic_title();
        $news['topic_color']= '#'.$myts->displayTarea($story->topic_color);

        if ($options[3] > 0) {
        	$html = $story->nohtml() == 1 ? 0 : 1;
        	//$news['teaser'] = nw_truncate_tagsafe($myts->displayTarea($story->hometext, $html), $options[3]+3);
        	//DNPROSSI New truncate function - now works correctly with html and utf-8
			$news['teaser'] = nw_truncate($story->hometext(), $options[3]+3, '...', true, $html);
        	$news['infotips'] = '';
        }
        else {
        	$news['teaser'] = '';
			if($infotips>0) {
				$news['infotips'] = ' title="'.nw_make_infotips($story->hometext()).'"';
			} else {
				$news['infotips'] = '';
			}
        }
        $block['stories'][] = $news;
    }
    //DNPROSSI ADDED
	$block['newsmodule_url']= NW_MODULE_URL;
    
    $block['lang_read_more']=_MB_NW_READMORE;
    
    // DNPROSSI SEO
    $seo_enabled = nw_getmoduleoption('seo_enable', NW_MODULE_DIR_NAME);
	if ( $seo_enabled != 0 ) {
		$block['urlrewrite']= "true";
	} else { 
		$block['urlrewrite']= "false"; 
	}  
    
    return $block;
}

function nw_b_news_randomnews_edit($options) {
    global $xoopsDB;
    $form = _MB_NW_ORDER."&nbsp;<select name='options[]'>";
    $form .= "<option value='published'";
    if ( $options[0] == "published" ) {
        $form .= " selected='selected'";
    }
    $form .= '>'._MB_NW_DATE."</option>\n";

    $form .= "<option value='counter'";
    if($options[0] == 'counter'){
        $form .= " selected='selected'";
    }
    $form .= '>'._MB_NW_HITS.'</option>';

    $form .= "<option value='rating'";
    if ( $options[0] == 'rating' ) {
        $form .= " selected='selected'";
    }
    $form .= '>' . _MB_NW_RATE . '</option>';

    $form .= "</select>\n";
    $form .= '&nbsp;'._MB_NW_DISP."&nbsp;<input type='text' name='options[]' value='".$options[1]."'/>&nbsp;"._MB_NW_ARTCLS;
    $form .= '&nbsp;<br><br />'._MB_NW_CHARS."&nbsp;<input type='text' name='options[]' value='".$options[2]."'/>&nbsp;"._MB_NW_LENGTH.'<br /><br />';

    $form .= _MB_NW_TEASER." <input type='text' name='options[]' value='".$options[3]."' />"._MB_NW_LENGTH;
    $form .= '<br /><br />'._MB_NW_SPOTLIGHT_TOPIC."<br /><select id='options[4]' name='options[]' multiple='multiple'>";

    include_once XOOPS_ROOT_PATH.'/class/xoopsstory.php';
    $xt = new XoopsTopic($xoopsDB->prefix('nw_topics'));
    $alltopics = $xt->getTopicsList();
    $alltopics[0]['title'] = _MB_NW_SPOTLIGHT_ALL_TOPICS;
    ksort($alltopics);
    $size = count($options);
    foreach ($alltopics as $topicid => $topic) {
        $sel = '';
        for ( $i = 4; $i < $size; $i++ ) {
            if ($options[$i] == $topicid) {
                $sel = " selected='selected'";
            }
        }
        $form .= "<option value='$topicid'$sel>".$topic['title'].'</option>';
    }
    $form .= '</select><br />';
    return $form;
}

function nw_b_news_randomnews_onthefly($options)
{
	$options = explode('|',$options);
	$block = & nw_b_news_randomnews_show($options);

	$tpl = new XoopsTpl();
	$tpl->assign('block', $block);
	$tpl->display('db:nw_news_block_moderate.html');
}

?>
