<?php
// $Id: upgrade.php 8207 2011-11-07 04:18:27Z beckmi $
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
include_once "header.php";
include_once '../../../include/cp_header.php';
xoops_cp_header();
include_once NW_MODULE_PATH . '/include/functions.php';


if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
	$errors=0;
	
	//DNPROSSI - Upgrade if clone version is different from original's version
	
	//DNPROSSI - Import data from old news database files
	if( nw_TableExists($xoopsDB->prefix('stories')) )
	{
		$sql=sprintf("INSERT INTO ". $xoopsDB->prefix('nw_stories') ." SELECT * FROM ". $xoopsDB->prefix('stories') .";");
		$result=$xoopsDB->queryF($sql);
	
	    $sql=sprintf("INSERT INTO ". $xoopsDB->prefix('nw_stories_files') ." SELECT * FROM ". $xoopsDB->prefix('stories_files') .";");
		$result=$xoopsDB->queryF($sql);
		
	    $sql=sprintf("INSERT INTO ". $xoopsDB->prefix('nw_topics') ." SELECT * FROM ". $xoopsDB->prefix('topics') .";");
		$result=$xoopsDB->queryF($sql);
		
	    $sql=sprintf("INSERT INTO ". $xoopsDB->prefix('nw_stories_votedata') ." SELECT * FROM ". $xoopsDB->prefix('stories_votedata'). ";");
		$result=$xoopsDB->queryF($sql);
	}	
		
	// 1) Create, if it does not exists, the nw_stories_files table
	if(!nw_TableExists($xoopsDB->prefix('nw_stories_files')))
	{
		$sql = 'CREATE TABLE '.$xoopsDB->prefix('nw_stories_files')." (
  			fileid int(8) unsigned NOT NULL auto_increment,
  			filerealname varchar(255) NOT NULL default '',
  			storyid int(8) unsigned NOT NULL default '0',
  			date int(10) NOT NULL default '0',
  			mimetype varchar(64) NOT NULL default '',
  			downloadname varchar(255) NOT NULL default '',
  			counter int(8) unsigned NOT NULL default '0',
  			PRIMARY KEY  (fileid),
  			KEY storyid (storyid)
			) ENGINE=MyISAM;";
		if (!$xoopsDB->queryF($sql)) {
	    	echo '<br />' . _AM_NW_UPGRADEFAILED.' '._AM_NW_UPGRADEFAILED1;
	    	$errors++;
		}
	}

	// 2) Change the topic title's length, in the nw_topics table
	$sql=sprintf('ALTER TABLE ' . $xoopsDB->prefix('nw_topics') . ' CHANGE topic_title topic_title VARCHAR( 255 ) NOT NULL;');
	$result=$xoopsDB->queryF($sql);
	if (!$result) {
    	echo '<br />' .  _AM_NW_UPGRADEFAILED.' '._AM_NW_UPGRADEFAILED2;
    	$errors++;
	}

	// 2.1) Add the new fields to the nw_topic table
	if (!nw_FieldExists('menu',$xoopsDB->prefix('nw_topics'))) {
		nw_AddField("menu TINYINT( 1 ) DEFAULT '0' NOT NULL",$xoopsDB->prefix('nw_topics'));
	}
	if (!nw_FieldExists('topic_frontpage',$xoopsDB->prefix('nw_topics'))) {
		nw_AddField("topic_frontpage TINYINT( 1 ) DEFAULT '1' NOT NULL",$xoopsDB->prefix('nw_topics'));
	}
	if (!nw_FieldExists('topic_rssurl',$xoopsDB->prefix('nw_topics'))) {
		nw_AddField("topic_rssurl VARCHAR( 255 ) NOT NULL",$xoopsDB->prefix('nw_topics'));
	}
	if (!nw_FieldExists('topic_description',$xoopsDB->prefix('nw_topics'))) {
		nw_AddField("topic_description TEXT NOT NULL",$xoopsDB->prefix('nw_topics'));
	}
	if (!nw_FieldExists('topic_color',$xoopsDB->prefix('nw_topics'))) {
		nw_AddField("topic_color varchar( 6 ) NOT NULL default '000000'",$xoopsDB->prefix('nw_topics'));
	}
	if (!nw_FieldExists('topic_weight',$xoopsDB->prefix('nw_topics'))) {
		nw_AddField("topic_weight int( 11 ) NOT NULL default '0'",$xoopsDB->prefix('nw_topics'));
	}

	// 3) If it does not exists, create the table nw_stories_votedata
	if(!nw_TableExists($xoopsDB->prefix('nw_stories_votedata')))
	{
		$sql = 'CREATE TABLE '.$xoopsDB->prefix('nw_stories_votedata')." (
  			ratingid int(11) unsigned NOT NULL auto_increment,
  			storyid int(8) unsigned NOT NULL default '0',
  			ratinguser int(11) NOT NULL default '0',
  			rating tinyint(3) unsigned NOT NULL default '0',
  			ratinghostname varchar(60) NOT NULL default '',
  			ratingtimestamp int(10) NOT NULL default '0',
  			PRIMARY KEY  (ratingid),
  			KEY ratinguser (ratinguser),
  			KEY ratinghostname (ratinghostname),
  			KEY storyid (storyid)
			) ENGINE=MyISAM;";
		if (!$xoopsDB->queryF($sql)) {
	    	echo '<br />' .  _AM_NW_UPGRADEFAILED.' '._AM_NW_UPGRADEFAILED3;
	    	$errors++;
		}
	}

	// 4) Create the four new fields for the votes in the nw_stories table
	if (!nw_FieldExists('rating',$xoopsDB->prefix('nw_stories'))) {
		nw_AddField("rating DOUBLE( 6, 4 ) DEFAULT '0.0000' NOT NULL",$xoopsDB->prefix('nw_stories'));
	}
	if (!nw_FieldExists('votes',$xoopsDB->prefix('nw_stories'))) {
		nw_AddField("votes INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL",$xoopsDB->prefix('nw_stories'));
	}
	if (!nw_FieldExists('keywords',$xoopsDB->prefix('nw_stories'))) {
		nw_AddField("keywords VARCHAR( 255 ) NOT NULL",$xoopsDB->prefix('nw_stories'));
	}
	if (!nw_FieldExists('description',$xoopsDB->prefix('nw_stories'))) {
		nw_AddField("description VARCHAR( 255 ) NOT NULL",$xoopsDB->prefix('nw_stories'));
	}
	if (!nw_FieldExists('dobr',$xoopsDB->prefix('nw_stories'))) {
		nw_AddField("dobr TINYINT( 1 ) NOT NULL DEFAULT '1'",$xoopsDB->prefix('nw_stories'));
	}
	if (!nw_FieldExists('tags',$xoopsDB->prefix('nw_stories'))) {
		nw_AddField("tags VARCHAR( 255 ) DEFAULT ''",$xoopsDB->prefix('nw_stories'));
	}

	// 5) Add some indexes to the topics table
	$sql=sprintf('ALTER TABLE ' . $xoopsDB->prefix('nw_topics') . " ADD INDEX ( `topic_title` );");
	$result=$xoopsDB->queryF($sql);
	$sql=sprintf('ALTER TABLE ' . $xoopsDB->prefix('nw_topics') . " ADD INDEX ( `menu` );");
	$result=$xoopsDB->queryF($sql);

    $moduledirname = NW_MODULE_DIR_NAME;

    // At the end, if there was errors, show them or redirect user to the module's upgrade page
	if($errors) {
		echo '<H1>' . _AM_NW_UPGRADEFAILED . '</H1>';
		echo '<br />' . _AM_NW_UPGRADEFAILED0;
	} else {
		echo _AM_NW_UPGRADECOMPLETE." - <a href='".XOOPS_URL."/modules/system/admin.php?fct=modulesadmin&op=update&module=".$moduledirname."'>"._AM_NW_UPDATEMODULE."</a>";
	}
} else {
	printf("<h2>%s</h2>\n",_AM_NW_UPGR_ACCESS_ERROR);
}
xoops_cp_footer();
?>
