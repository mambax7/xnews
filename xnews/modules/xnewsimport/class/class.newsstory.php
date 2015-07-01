<?php
// $Id: class.newsstory.php,v 1.29 2004/09/02 17:04:08 hthouzard Exp $
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
// ------------------------------------------------------------------------- //
if (!defined('XOOPS_ROOT_PATH')) {
	die('XOOPS root path not defined');
}

include_once XOOPS_ROOT_PATH.'/class/xoopsstory.php';
include_once XOOPS_ROOT_PATH.'/include/comment_constants.php';
include_once XNI_MODULE_PATH . '/include/functions.php';

class xni_NewsStory extends XoopsStory
{
	var $newstopic;   	// XoopsTopic object
	var $rating;		// news rating
  	var $votes;			// Number of votes
  	var $description;	// META, desciption
  	var $keywords;		// META, keywords
  	var $picture;
  	var $topic_imgurl;
  	var $topic_title;

	/**
 	* Constructor
 	*/
	function xni_NewsStory($storyid=-1, $subprefix='')
	{
		$this->db =& Database::getInstance();
		$this->table = $this->db->prefix($subprefix . 'stories');
		$this->topicstable = $this->db->prefix($subprefix . 'topics');
		if (is_array($storyid)) {
			$this->makeStory($storyid);
		} elseif($storyid != -1) {
			$this->getStory(intval($storyid), $subprefix);
		}
	}

	/**
	 * Load the specified story from the database
	 */
	function getStory($storyid, $subprefix)
	{
		$sql = 'SELECT s.*, t.* FROM '.$this->table.' s, '.$this->db->prefix($subprefix .'topics').' t WHERE (storyid='.intval($storyid).') AND (s.topicid=t.topic_id)';
		$array = $this->db->fetchArray($this->db->query($sql));
		$this->makeStory($array);
	}

	/**
	 * Create or update an article
	 */
	function store($approved=false)
	{
		$myts =& MyTextSanitizer::getInstance();
		$counter = isset($this->counter) ? $this->counter : 0;
		$title = $myts->censorString($this->title);
		$title = $myts->addSlashes($title);
		$hostname=$myts->addSlashes($this->hostname);
		$type=$myts->addSlashes($this->type);
		$hometext =$myts->addSlashes($myts->censorString($this->hometext));
		$bodytext =$myts->addSlashes($myts->censorString($this->bodytext));
		$description =$myts->addSlashes($myts->censorString($this->description));
		$keywords =$myts->addSlashes($myts->censorString($this->keywords));
		$picture = $myts->addSlashes($this->picture);
		$votes= intval($this->votes);
		$rating = (float)($this->rating);
		if (!isset($this->nohtml) || $this->nohtml != 1) {
			$this->nohtml = 0;
		}
		if (!isset($this->nosmiley) || $this->nosmiley != 1) {
			$this->nosmiley = 0;
		}
		if (!isset($this->notifypub) || $this->notifypub != 1) {
			$this->notifypub = 0;
		}
		if(!isset($this->topicdisplay) || $this->topicdisplay != 0) {
			$this->topicdisplay = 1;
		}
		$expired = !empty($this->expired) ? $this->expired : 0;
		if (!isset($this->storyid)) {
			//$newpost = 1;
			$newstoryid = $this->db->genId($this->table.'_storyid_seq');
			$created = time();
			$published = ( $this->approved ) ? intval($this->published) : 0;
			$sql = sprintf("INSERT INTO %s (storyid, uid, title, created, published, expired, hostname, nohtml, nosmiley, hometext, bodytext, counter, topicid, ihome, notifypub, story_type, topicdisplay, topicalign, comments, rating, votes, description, keywords, picture) VALUES (%u, %u, '%s', %u, %u, %u, '%s', %u, %u, '%s', '%s', %u, %u, %u, %u, '%s', %u, '%s', %u, %u, %u, '%s', '%s', '%s')", $this->table, $newstoryid, intval($this->uid()), $title, $created, $published, $expired, $hostname, intval($this->nohtml()), intval($this->nosmiley()), $hometext, $bodytext, $counter, intval($this->topicid()), intval($this->ihome()), intval($this->notifypub()), $type, intval($this->topicdisplay()), $this->topicalign, intval($this->comments()), $rating, $votes, $description, $keywords, $picture);
		} else {
			$sql = sprintf("UPDATE %s SET title='%s', published=%u, expired=%u, nohtml=%u, nosmiley=%u, hometext='%s', bodytext='%s', topicid=%u, ihome=%u, topicdisplay=%u, topicalign='%s', comments=%u, rating=%u, votes=%u, uid=%u, description='%s', keywords='%s', picture='%s' WHERE storyid = %u", $this->table, $title, intval($this->published()), $expired, intval($this->nohtml()), intval($this->nosmiley()), $hometext, $bodytext, intval($this->topicid()), intval($this->ihome()), intval($this->topicdisplay()), $this->topicalign, intval($this->comments()), $rating, $votes, intval($this->uid()), $description, $keywords, $picture, intval($this->storyid()));
			$newstoryid = intval($this->storyid());
		}
		if (!$this->db->queryF($sql)) {
			return false;
		}
		if (empty($newstoryid)) {
			$newstoryid = $this->db->getInsertId();
			$this->storyid = $newstoryid;
		}
		return $newstoryid;
	}

	function picture()
	{
		return $this->picture;
	}

	function rating()
	{
		return $this->rating;
	}

	function votes()
	{
		return $this->votes;
	}

	function Setpicture($data)
	{
		$this->picture = $data;
	}

	function Setdescription($data)
	{
		$this->description=$data;
	}

	function Setkeywords($data)
	{
		$this->keywords=$data;
	}

	function description($format='S')
	{
		$myts =& MyTextSanitizer::getInstance();
		switch(strtoupper($format)) {
			case 'S':
				$description= $myts->htmlSpecialChars($this->description);
				break;
			case 'P':
			case 'F':
				$description = $myts->htmlSpecialChars($myts->stripSlashesGPC($this->description));
				break;
			case 'E':
				$description = $myts->htmlSpecialChars($this->description);
				break;
		}
		return $description;
	}

	function keywords($format='S')
	{
		$myts =& MyTextSanitizer::getInstance();
		switch(strtoupper($format)) {
			case 'S':
				$keywords= $myts->htmlSpecialChars($this->keywords);
				break;
			case 'P':
			case 'F':
				$keywords = $myts->htmlSpecialChars($myts->stripSlashesGPC($this->keywords));
				break;
			case 'E':
				$keywords = $myts->htmlSpecialChars($this->keywords);
				break;
		}
		return $keywords;
	}
}
?>
