<?php
// $Id: update.php 8207 2011-11-07 04:18:27Z beckmi $
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
//  DNPROSSI - 2010
//  ------------------------------------------------------------------------ //

//Default Permission Settings
function xoops_module_update_xnews(&$xoopsModule) 
{
	include_once NW_MODULE_PATH . '/include/functions.php';
	
	$module_id = $xoopsModule->getVar('mid');
	$module_name = $xoopsModule->getVar('name');
	$module_dirname = $xoopsModule->getVar('dirname');
	$module_version = $xoopsModule->getVar('version');
	
	global $xoopsDB;
		
	//EDIT Cloner table 
	$result = $xoopsDB->query("SELECT clone_id FROM " . $xoopsDB->prefix('news_clonerdata') . " WHERE clone_dir = '" . $module_dirname . "' ;");
	$tmpcloneid = $xoopsDB->fetchRow($result);
	$cloneid = $tmpcloneid[0];	
	$xoopsDB->query("UPDATE " . $xoopsDB->prefix('news_clonerdata') . " SET clone_version = " . $module_version . " WHERE clone_id = " . $cloneid);
	if (!nw_FieldExists('dobr',$xoopsDB->prefix('nw_stories'))) {
		nw_AddField("dobr TINYINT( 1 ) NOT NULL DEFAULT '1'",$xoopsDB->prefix('nw_stories'));
	}
	if (!nw_FieldExists('tags',$xoopsDB->prefix('nw_stories'))) {
		nw_AddField("tags VARCHAR( 255 ) DEFAULT ''",$xoopsDB->prefix('nw_stories'));
	}
	if (!nw_FieldExists('topic_weight',$xoopsDB->prefix('nw_topics'))) {
		nw_AddField("topic_weight int(11) NOT NULL default '0'",$xoopsDB->prefix('nw_topics'));
	}
	return true;
}
?>
