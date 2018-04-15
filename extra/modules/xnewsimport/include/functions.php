<?php
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Returns a module's option
 *
 * @param string $option module option's name
 * @param        $repmodule
 * @return bool
 */
function xni_getmoduleoption($option, $repmodule)
{
    global $xoopsModuleConfig, $xoopsModule;
    static $tbloptions = [];
    if (is_array($tbloptions) && array_key_exists($option, $tbloptions)) {
        return $tbloptions[$option];
    }

    $retval = false;
    if (isset($xoopsModuleConfig) && (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $repmodule && $xoopsModule->getVar('isactive'))) {
        if (isset($xoopsModuleConfig[$option])) {
            $retval = $xoopsModuleConfig[$option];
        }
    } else {
        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname($repmodule);
        $configHandler = xoops_getHandler('config');
        if ($module) {
            $moduleConfig = $configHandler->getConfigsByCat(0, $module->getVar('mid'));
            if (isset($moduleConfig[$option])) {
                $retval = $moduleConfig[$option];
            }
        }
    }
    $tbloptions[$option] = $retval;

    return $retval;
}

/**
 * @param $fieldname
 * @param $table
 * @return bool
 */
function xni_fieldexists($fieldname, $table)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF("SHOW COLUMNS FROM	$table LIKE '$fieldname'");

    return ($xoopsDB->getRowsNum($result) > 0);
}

/**
 * @param $subprefix
 * @return string
 */
function xni_gettopics($subprefix)
{
    global $xoopsDB;
    $topics_arr = '';
    if (!empty($subprefix)) {
        $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix($subprefix . 'topics'));
    } else {
        $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('topics'));
    }
    $ix = 0;
    while (false !== ($all_topics = $xoopsDB->fetchArray($result))) {
        $topics_arr[$ix] = $all_topics;
        ++$ix;
    }

    return $topics_arr;
}

/**
 * @param $subprefix
 * @return string
 */
function xni_getcategories($subprefix)
{
    global $xoopsDB;
    $topics_arr = '';
    $result     = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix($subprefix . 'categories'));
    $ix         = 0;
    while (false !== ($all_topics = $xoopsDB->fetchArray($result))) {
        $topics_arr[$ix] = $all_topics;
        ++$ix;
    }

    return $topics_arr;
}

/**
 * @param string $permtype
 * @return mixed
 */
function xni_MygetItemIds($permtype = 'nw_view')
{
    global $xoopsUser;
    static $tblperms = [];
    if (is_array($tblperms) && array_key_exists($permtype, $tblperms)) {
        return $tblperms[$permtype];
    }

    $moduleHandler       = xoops_getHandler('module');
    $newsModule          = $moduleHandler->getByDirname(XNI_MODULE_DIR_NAME);
    $groups              = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $grouppermHandler        = xoops_getHandler('groupperm');
    $topics              = $grouppermHandler->getItemIds($permtype, $groups, $newsModule->getVar('mid'));
    $tblperms[$permtype] = $topics;

    return $topics;
}

/**
 * @param $srcpath
 * @param $destpath
 * @param $filename
 */
function xni_clonecopyfile($srcpath, $destpath, $filename)
{
    if ($handle = opendir($srcpath)) {
        if ('' == $filename) {
            while ($file = readdir($handle)) {
                if ('.' !== $file && '..' !== $file) {
                    @copy($srcpath . $file, $destpath . $file);
                }
            }
        } else {
            if (file_exists($srcpath . $filename)) {
                @copy($srcpath . $filename, $destpath . $filename);
            }
        }
        closedir($handle);
    }
}

/**
 * @param $dirname
 * @param $groups
 * @param $itemid
 * @param $permname
 * @return bool
 */
function xni_savePermissions($dirname, $groups, $itemid, $permname)
{
    global $xoopsModule;
    /** @var \XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $news_module   = $moduleHandler->getByDirname($dirname);

    $result = true;

    $module_id    = $news_module->getVar('mid');
    $grouppermHandler = xoops_getHandler('groupperm');
    // First, if the permissions are already there, delete them
    $grouppermHandler->deleteByModule($module_id, $permname, $itemid);
    // Save the new permissions
    if (count($groups) > 0) {
        foreach ($groups as $group_id) {
            $grouppermHandler->addRight($permname, $itemid, $group_id, $module_id);
            //trigger_error($permname . ' ---- ' . $itemid . ' ---- ' . $group_id . ' ---- ' . $module_id, E_USER_WARNING);
        }
    }

    return $result;
}
