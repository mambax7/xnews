<?php
if (!defined('XOOPS_ROOT_PATH')) {
	die('XOOPS root path not defined');
}

/**
 * Returns a module's option
 *
 * @param string $option	module option's name
 */
function xni_getmoduleoption($option, $repmodule)
{
	global $xoopsModuleConfig, $xoopsModule;
	static $tbloptions= Array();
	if(is_array($tbloptions) && array_key_exists($option, $tbloptions)) {
		return $tbloptions[$option];
	}

	$retval = false;
	if (isset($xoopsModuleConfig) && (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $repmodule && $xoopsModule->getVar('isactive'))) {
		if(isset($xoopsModuleConfig[$option])) {
			$retval= $xoopsModuleConfig[$option];
		}
	} else {
		$module_handler =& xoops_gethandler('module');
		$module =& $module_handler->getByDirname($repmodule);
		$config_handler =& xoops_gethandler('config');
		if ($module) {
		    $moduleConfig =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));
	    	if(isset($moduleConfig[$option])) {
	    		$retval= $moduleConfig[$option];
	    	}
		}
	}
	$tbloptions[$option]=$retval;
	return $retval;
}

function xni_fieldexists($fieldname, $table)
{
	global $xoopsDB;
	$result=$xoopsDB->queryF("SHOW COLUMNS FROM	$table LIKE '$fieldname'");
	return($xoopsDB->getRowsNum($result) > 0);
}

function xni_gettopics($subprefix)
{
	global $xoopsDB;
	$topics_arr = '';
	if ( !empty($subprefix) ) {	
		$result = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix($subprefix . "topics"));
	} else
	{
		$result = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix("topics"));
	}
	$ix = 0;
	while ( $all_topics = $xoopsDB->fetchArray($result) ) 
	{
		$topics_arr[$ix] = $all_topics;
		$ix++;
	}
	return($topics_arr);
}

function xni_getcategories($subprefix)
{
	global $xoopsDB;
	$topics_arr = '';
	$result = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix($subprefix . "categories"));
	$ix = 0;
	while ( $all_topics = $xoopsDB->fetchArray($result) ) 
	{
		$topics_arr[$ix] = $all_topics;
		$ix++;
	}
	return($topics_arr);
}

function xni_MygetItemIds($permtype='nw_view')
{
	global $xoopsUser;
	static $tblperms = array();
	if(is_array($tblperms) && array_key_exists($permtype,$tblperms)) {
		return $tblperms[$permtype];
	}

   	$module_handler =& xoops_gethandler('module');
   	$newsModule =& $module_handler->getByDirname(XNI_MODULE_DIR_NAME);
   	$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
   	$gperm_handler =& xoops_gethandler('groupperm');
   	$topics = $gperm_handler->getItemIds($permtype, $groups, $newsModule->getVar('mid'));
   	$tblperms[$permtype] = $topics;
    return $topics;
}

function xni_clonecopyfile($srcpath, $destpath, $filename)	
{
	if ( $handle = opendir($srcpath) )
	{
		if ( $filename == '' ) 
		{
			while ( $file = readdir($handle) )
			{
				if ( $file != '.' && $file != '..' )
				{   
					@copy($srcpath.$file, $destpath.$file );
				}				
			}
		} else {
			if ( file_exists($srcpath.$filename) ) 
			{
				@copy($srcpath.$filename, $destpath.$filename);
			}	
	    }		
		closedir($handle);
	}	
}

function xni_savePermissions($dirname, $groups, $itemid, $permname)
{
    global $xoopsModule;
    
    $module_handler =& xoops_gethandler('module');
   	$news_module =& $module_handler->getByDirname($dirname);
    
    $result = true;

    $module_id = $news_module->getVar('mid');
    $gperm_handler =& xoops_gethandler('groupperm');
    // First, if the permissions are already there, delete them
    $gperm_handler->deleteByModule($module_id, $permname, $itemid);
    // Save the new permissions
    if (count($groups) > 0) {
        foreach ($groups as $group_id) {
            $gperm_handler->addRight($permname, $itemid, $group_id, $module_id);
            //trigger_error($permname . ' ---- ' . $itemid . ' ---- ' . $group_id . ' ---- ' . $module_id, E_USER_WARNING);
        }
    }
    return $result;
}
?>
