<?php

/**
 * @param $xoopsModule
 * @return bool
 */
function xoops_module_install_xnews($xoopsModule)
{
    require_once __DIR__ . '/common.php';
    global $xoopsDB;
    //
    // default Permission Settings
    $module_id      = $xoopsModule->getVar('mid');
    $module_name    = $xoopsModule->getVar('name');
    $module_dirname = $xoopsModule->getVar('dirname');
    $module_version = $xoopsModule->getVar('version');
    /** @var \XoopsGroupPermHandler $grouppermHandler */
    $grouppermHandler = xoops_getHandler('groupperm');
    // access rights
    $grouppermHandler->addRight('nw_approve', 1, XOOPS_GROUP_ADMIN, $module_id);
    $grouppermHandler->addRight('nw_submit', 1, XOOPS_GROUP_ADMIN, $module_id);
    $grouppermHandler->addRight('nw_view', 1, XOOPS_GROUP_ADMIN, $module_id);
    $grouppermHandler->addRight('nw_view', 1, XOOPS_GROUP_USERS, $module_id);
    $grouppermHandler->addRight('nw_view', 1, XOOPS_GROUP_ANONYMOUS, $module_id);
    // recalc original subprefix
    $sub             = nw_remove_numbers(XNEWS_SUBPREFIX);
    $module_original = $xoopsModule->getInfo('original');
    if (1 == $module_original) {
        //Create Cloner table
        if (!nw_TableExists($xoopsDB->prefix('news_clonerdata'))) {
            $sql    = "CREATE TABLE IF NOT EXISTS {$xoopsDB->prefix('news_clonerdata')}";
            $sql    .= '(';
            $sql    .= 'clone_id int(11) unsigned NOT NULL auto_increment, ';
            $sql    .= "clone_name varchar(50) NOT NULL default '', ";
            $sql    .= "clone_dir varchar(255) NOT NULL default '', ";
            $sql    .= "clone_subprefix varchar(50) NOT NULL default '' ,";
            $sql    .= "clone_version varchar(5) NOT NULL default '', ";
            $sql    .= "clone_installed tinyint(1) NOT NULL default '0', ";
            $sql    .= 'PRIMARY KEY (clone_id)';
            $sql    .= ") ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'";
            $result = $xoopsDB->queryF($sql);
        }
        if (nw_TableExists($xoopsDB->prefix('news_clonerdata'))) {
            //Add cloned module to cloner dtb
            $sql    = "INSERT INTO {$xoopsDB->prefix('news_clonerdata')}";
            $sql    .= ' (clone_name, clone_dir, clone_version, clone_subprefix, clone_installed)';
            $sql    .= " VALUES ('{$module_name}', '{$module_dirname}', '{$module_version}', '{$sub}', '1')";
            $result = $xoopsDB->queryF($sql);
        }
    }
    if (1 != $module_original) {
        //Change cloned module install attribute
        $sql = "UPDATE {$xoopsDB->prefix('news_clonerdata')} SET clone_installed = '1'";
        $sql .= " WHERE clone_dir = '{$module_dirname}'";
        $xoopsDB->queryF($sql);
    }
    // create uploads/modulename/ images-topics-attached Folders
    if (!is_dir(XNEWS_UPLOADS_NEWS_PATH)) {
        nw_prepareFolder(XNEWS_UPLOADS_NEWS_PATH);
        nw_prepareFolder(XNEWS_TOPICS_FILES_PATH);
        nw_prepareFolder(XNEWS_ATTACHED_FILES_PATH);
        // move topics content to uploads/modulename/topics
        copy(XNEWS_MODULE_PATH . '/assets/images/topics/blank.png', XNEWS_TOPICS_FILES_PATH . '/blank.png');
        copy(XNEWS_MODULE_PATH . '/assets/images/topics/xoops.gif', XNEWS_TOPICS_FILES_PATH . '/xoops.gif');
    }

    return true;
}
