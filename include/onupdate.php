<?php

use XoopsModules\Xnews;

require_once __DIR__ . '/functions.php';

//Default Permission Settings
/**
 * @param $xoopsModule
 * @return bool
 */
function xoops_module_update_xnews($xoopsModule)
{
    global $xoopsDB;
    require_once __DIR__ . '/common.php';
    // require_once XNEWS_MODULE_PATH . '/class/Files.php';
    //
    $module_id      = $xoopsModule->getVar('mid');
    $module_name    = $xoopsModule->getVar('name');
    $module_dirname = $xoopsModule->getVar('dirname');
    $module_version = $xoopsModule->getVar('version');
    //
    // EDIT Cloner table
    $result     = $xoopsDB->query('SELECT clone_id FROM ' . $xoopsDB->prefix('news_clonerdata') . " WHERE clone_dir = '" . $module_dirname . "' ;");
    $tmpcloneid = $xoopsDB->fetchRow($result);
    $cloneid    = $tmpcloneid[0];
    $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('news_clonerdata') . ' SET clone_version = ' . $module_version . ' WHERE clone_id = ' . $cloneid);
    if (!nw_FieldExists('dobr', $xoopsDB->prefix('nw_stories'))) {
        nw_AddField("dobr TINYINT( 1 ) NOT NULL DEFAULT '1'", $xoopsDB->prefix('nw_stories'));
    }
    if (!nw_FieldExists('tags', $xoopsDB->prefix('nw_stories'))) {
        nw_AddField("tags VARCHAR( 255 ) DEFAULT ''", $xoopsDB->prefix('nw_stories'));
    }
    if (!nw_FieldExists('imagerows', $xoopsDB->prefix('nw_stories'))) {
        nw_AddField("imagerows SMALLINT(4) unsigned NOT NULL default '1'", $xoopsDB->prefix('nw_stories'));
    }
    if (!nw_FieldExists('pdfrows', $xoopsDB->prefix('nw_stories'))) {
        nw_AddField("pdfrows SMALLINT(4) unsigned NOT NULL default '1'", $xoopsDB->prefix('nw_stories'));
    }
    if (!nw_FieldExists('topic_weight', $xoopsDB->prefix('nw_topics'))) {
        nw_AddField("topic_weight int(11) NOT NULL default '0'", $xoopsDB->prefix('nw_topics'));
    }
    // Create thumbs from attached images if not exist
    $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('nw_stories_files'));
    //$stuff = $xoopsDB->fetchArray($result);
    //echo count($stuff);
    while (false !== ($singlefile = $xoopsDB->fetchArray($result))) {
        //foreach ( $xoopsDB->fetchArray($result) as $singlefile ) {
        $sfiles   = new Xnews\Files($singlefile['fileid']);
        $destname = $sfiles->getDownloadname();
        if (false !== mb_strpos($sfiles->getMimetype(), 'image')) {
            $fullPictureName = XNEWS_ATTACHED_FILES_PATH . '/' . basename($destname);
            // IN PROGRESS
            // IN PROGRESS
            // IN PROGRESS
            $thumbName = XNEWS_ATTACHED_FILES_PATH . '/thumb_' . basename($destname);
            if (!file_exists($thumbName)) {
                nw_resizePicture($fullPictureName, $thumbName, $helper->getConfig('thumb_maxwidth'), $helper->getConfig('thumb_maxheight'), true);
            }
        }
    }

    return true;
}
