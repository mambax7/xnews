<?php
include_once __DIR__ . '/functions.php';

function xoops_module_uninstall_xnews(&$xoopsModule) 
{
	global $xoopsDB;
    //
	$module_id = $xoopsModule->getVar('mid');
	$module_name = $xoopsModule->getVar('name');
	$module_dirname = $xoopsModule->getVar('dirname');
	$module_version = $xoopsModule->getVar('version');
	$module_original = $xoopsModule->getInfo('original');
	// DROP cloner control table DNPROSSI
	if (nw_TableExists($xoopsDB->prefix('news_clonerdata'))) {
		// update database on clone uninstall
        $sql = "SELECT * FROM {$xoopsDB->prefix('news_clonerdata')}";
		$result = $xoopsDB->query();
		list($count) = $xoopsDB->fetchRow($result);
        $sql = "SELECT clone_id FROM {$xoopsDB->prefix('news_clonerdata')} WHERE clone_dir = '{$module_dirname}' ;";
		$result = $xoopsDB->query();
		$tmpcloneid = $xoopsDB->fetchRow($result);
		$cloneid = $tmpcloneid[0];
        $sql = "UPDATE {$xoopsDB->prefix('news_clonerdata')} SET clone_installed = " . 0 . " WHERE clone_id = {$cloneid}";
		$xoopsDB->query($sql);
		// if table is empty drop
		if ($count == 1 && $module_original == 1) {
            $sql = "DROP TABLE {$xoopsDB->prefix('news_clonerdata')}";
			$result = $xoopsDB->query($sql);
	    }  
	}
	return true;
}
