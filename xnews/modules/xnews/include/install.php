<?php
// $Id: install.php 8207 2011-11-07 04:18:27Z beckmi $
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
function xoops_module_install_xnews(&$xoopsModule) 
{
	$module_id = $xoopsModule->getVar('mid');
	$module_name = $xoopsModule->getVar('name');
	$module_dirname = $xoopsModule->getVar('dirname');
	$module_version = $xoopsModule->getVar('version');
	$gpermHandler =& xoops_gethandler('groupperm');
	
	// Access rights
	$gpermHandler->addRight('nw_approve', 1, XOOPS_GROUP_ADMIN, $module_id);
	$gpermHandler->addRight('nw_submit', 1, XOOPS_GROUP_ADMIN, $module_id);
	$gpermHandler->addRight('nw_view', 1, XOOPS_GROUP_ADMIN, $module_id);
	
	$gpermHandler->addRight('nw_view', 1, XOOPS_GROUP_USERS, $module_id);
	$gpermHandler->addRight('nw_view', 1, XOOPS_GROUP_ANONYMOUS, $module_id);
	
	//recalc original subprefix
	$sub = nw_remove_numbers(NW_SUBPREFIX);
	
	$module_original = $xoopsModule->getInfo('original');
	
	global $xoopsDB;
	
	if ( $module_original == 1 )
	{
		//Create Cloner table
		if ( !nw_TableExists($xoopsDB->prefix('news_clonerdata')) )
		{
			$sql=sprintf("CREATE TABLE IF NOT EXISTS " . $xoopsDB->prefix('news_clonerdata') . "(" .
						"clone_id int(11) unsigned NOT NULL auto_increment," .
						"clone_name varchar(50) NOT NULL default ''," .
						"clone_dir varchar(255) NOT NULL default ''," .
						"clone_subprefix varchar(50) NOT NULL default ''," .
						"clone_version varchar(5) NOT NULL default ''," .
						"clone_installed tinyint(1) NOT NULL default '0'," .
						"PRIMARY KEY  (clone_id)" .
						") ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';" );	
			$result=$xoopsDB->queryF($sql);
		} 
	
		if ( nw_TableExists($xoopsDB->prefix('news_clonerdata')) )
		{	 
			//Add cloned module to cloner dtb
			$result = $xoopsDB->queryF("INSERT INTO " . $xoopsDB->prefix('news_clonerdata') . 
						" (clone_name, clone_dir, clone_version, clone_subprefix, clone_installed)" .
						" VALUES ('" . $module_name . "', '" . $module_dirname . "', '" . $module_version . "', '" . $sub . "', '1');");	
		}
	} 
	
	if ( $module_original != 1 )
	{
		//Change cloned module install attribute
		$xoopsDB->queryF("UPDATE " . $xoopsDB->prefix('news_clonerdata') . " SET clone_installed = '1' WHERE clone_dir = '" . $module_dirname . "';");
	}
	
	//Create uploads/modulename/ images-topics-attached Folders
	if(!is_dir(NW_UPLOADS_NEWS_PATH)) {
		nw_prepareFolder(NW_UPLOADS_NEWS_PATH);
	    nw_prepareFolder(NW_TOPICS_FILES_PATH);
	    nw_prepareFolder(NW_ATTACHED_FILES_PATH);
	    
	    //Move topics content to uploads/modulename/topics
		copy(NW_MODULE_PATH . '/images/topics/blank.png', NW_TOPICS_FILES_PATH . '/blank.png');
		copy(NW_MODULE_PATH . '/images/topics/xoops.gif', NW_TOPICS_FILES_PATH . '/xoops.gif');
	}
	
	
	
	return true;
}
?>
