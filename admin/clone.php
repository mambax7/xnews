<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author     XOOPS Development Team
 */


use Xmf\Request;

$currentFile = basename(__FILE__);
require_once __DIR__ . '/admin_header.php';

require_once XNEWS_MODULE_PATH . '/class/deprecate/xnewstopic.php';
require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';

require_once XNEWS_MODULE_PATH . '/class/class.newsstory.php';
require_once XNEWS_MODULE_PATH . '/class/class.newstopic.php';
require_once XNEWS_MODULE_PATH . '/class/class.sfiles.php';
require_once XNEWS_MODULE_PATH . '/class/blacklist.php';
require_once XNEWS_MODULE_PATH . '/class/registryfile.php';

require_once XOOPS_ROOT_PATH . '/class/uploader.php';
xoops_load('xoopspagenav');
require_once XOOPS_ROOT_PATH . '/class/tree.php';

$myts        = MyTextSanitizer::getInstance();
$topicscount = 0;

$storiesTableName = $GLOBALS['xoopsDB']->prefix('nw_stories');
if (!nw_FieldExists('picture', $storiesTableName)) {
    nw_AddField('`picture` VARCHAR( 50 ) NOT NULL', $storiesTableName);
}

/**
 * Cloner - DNPROSSI
 */
function NewsCloner()
{
    global $myts;
    $xnews               = XnewsXnews::getInstance();
    $nw_NewsStoryHandler = new nw_NewsStory();

    //  admin navigation
    xoops_cp_header();
    $adminObject = \Xmf\Module\Admin::getInstance();
    $adminObject->displayNavigation($currentFile);
    //
    xoops_load('XoopsFormLoader');

    $clone_modulename = '';

    $start = isset($_GET['start']) ? (int)$_GET['start'] : 0;

    $result = $GLOBALS['xoopsDB']->query('SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('news_clonerdata'));
    $ix     = 0;
    $iy     = 0;
    while ($clone = $GLOBALS['xoopsDB']->fetchArray($result)) {
        //DNPROSSI - Control if clone dir exists
        if (is_dir(XOOPS_ROOT_PATH . '/modules/' . $clone['clone_dir'])) {
            $clone_arr[$ix] = $clone;
            $ix++;
        } else {
            $nonclone_arr[$iy] = $clone;
            $iy++;
        }
    }
    // If cloned dir does not exists because deleted remove from dtb
    if (isset($nonclone_arr)) {
        for ($iy = 0; $iy < count($nonclone_arr); $iy++) {
            $result = $GLOBALS['xoopsDB']->queryF('DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('news_clonerdata') . " WHERE clone_dir = '" . $nonclone_arr[$iy]['clone_dir'] . "' ;");
        }
    }

    $totalclones = count($clone_arr);
    $class       = '';

    xnews_collapsableBar('NewsCloner', 'topNewsCloner');
    echo "<img onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='topNewsCloner' name='topNewsCloner' src='" . XNEWS_MODULE_URL . "/assets/images/close12.gif' alt=''></a>&nbsp;" . _AM_NW_CLONER_CLONES . ' (' . $totalclones . ')' . '</h4>';
    echo "<div id='NewsCloner'>";
    echo '<br>';
    echo "<div style='text-align: center;'>";
    echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr class='bg3'><td align='center'>"
         . _AM_NW_CLONER_NAME
         . "</td><td align='center'>"
         . _AM_NW_CLONER_DIRFOL
         . "</td><td align='center'>"
         . _AM_XNEWS_SUBPREFIX
         . "</td><td align='center'>"
         . _AM_NW_CLONER_VERSION
         . "</td><td align='center'>"
         . _AM_NW_ACTION
         . "</td><td align='center'>"
         . _AM_NW_CLONER_ACTION_INSTALL
         . '</td></tr>';
    if (is_array($clone_arr) && $totalclones) {
        $cpt    = 1;
        $tmpcpt = $start;
        $ok     = true;
        $output = '';
        while ($ok) {
            if ($tmpcpt < $totalclones) {
                //DNPROSSI - Upgrade if clone version is different from original news version
                //DNPROSSI - Install if cloned
                if ($clone_arr[$tmpcpt]['clone_dir'] != $clone_arr[0]['clone_dir']) {
                    if ($clone_arr[$tmpcpt]['clone_version'] != $clone_arr[0]['clone_version']) {
                        $linkupgrade = XNEWS_MODULE_URL . '/admin/clone.php?op=cloneupgrade&amp;clone_id=' . $clone_arr[$tmpcpt]['clone_id'];
                        $action      = sprintf("<a href='%s'>%s</a>", $linkupgrade, _AM_NW_UPGRADE);
                        if (1 == $clone_arr[$tmpcpt]['clone_installed']) {
                            $linkupgrade   = XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&op=uninstall&module=' . $clone_arr[$tmpcpt]['clone_dir'];
                            $installaction = sprintf("<a href='%s'>%s</a>", $linkupgrade, _AM_NW_CLONER_UNINSTALL);
                        } else {
                            $linkupgrade   = XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&op=install&module=' . $clone_arr[$tmpcpt]['clone_dir'];
                            $linkdelete    = XNEWS_MODULE_URL . '/admin/clone.php?op=clonedelete&amp;clone_id=' . $clone_arr[$tmpcpt]['clone_id'];
                            $installaction = sprintf("<a href='%s'>%s</a> - <a href='%s'>%s</a>", $linkupgrade, _AM_NW_CLONER_INSTALL, $linkdelete, _AM_NW_DELETE);
                        }
                    } else {
                        $linkforce = XNEWS_MODULE_URL . '/admin/clone.php?op=cloneupgrade&amp;clone_id=' . $clone_arr[$tmpcpt]['clone_id'];
                        $action    = sprintf(_AM_NW_CLONER_CLONEUPGRADED . " - <a href='%s'>%s</a>", $linkforce, _AM_NW_CLONER_UPGRADEFORCE);
                        if (1 == $clone_arr[$tmpcpt]['clone_installed']) {
                            $linkupgrade   = XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&op=uninstall&module=' . $clone_arr[$tmpcpt]['clone_dir'];
                            $installaction = sprintf("<a href='%s'>%s</a>", $linkupgrade, _AM_NW_CLONER_UNINSTALL);
                        } else {
                            $linkupgrade   = XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&op=install&module=' . $clone_arr[$tmpcpt]['clone_dir'];
                            $linkdelete    = XNEWS_MODULE_URL . '/admin/clone.php?op=clonedelete&amp;clone_id=' . $clone_arr[$tmpcpt]['clone_id'];
                            $installaction = sprintf("<a href='%s'>%s</a> - <a href='%s'>%s</a>", $linkupgrade, _AM_NW_CLONER_INSTALL, $linkdelete, _AM_NW_DELETE);
                        }
                    }
                } else {
                    $linkupgrade   = XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&op=update&module=' . $clone_arr[$tmpcpt]['clone_dir'];
                    $action        = sprintf("<a href='%s'>%s</a>", $linkupgrade, _AM_NW_CLONER_UPDATE);
                    $installaction = '';
                }
                $class  = ('even' === $class) ? 'odd' : 'even';
                $output = $output
                          . "<tr class='"
                          . $class
                          . "'><td align='center'>"
                          . $clone_arr[$tmpcpt]['clone_name']
                          . "</td><td align='center'>"
                          . $clone_arr[$tmpcpt]['clone_dir']
                          . "</td><td align='center'>"
                          . $clone_arr[$tmpcpt]['clone_subprefix']
                          . "</td><td align='center'>"
                          . round($clone_arr[$tmpcpt]['clone_version'] / 100, 2)
                          . '</td><td>'
                          . $action
                          . '</td><td>'
                          . $installaction
                          . '</td></tr>';
            } else {
                $ok = false;
            }
            if ($cpt >= $xnews->getConfig('storycountadmin')) {
                $ok = false;
            }
            $tmpcpt++;
            $cpt++;
        }
        echo $output;
    }
    $pagenav = new XoopsPageNav($totalclones, $xnews->getConfig('storycountadmin'), $start, 'start', 'op=clonemanager');
    echo "</table><div align='right'>" . $pagenav->renderNav() . '</div><br>';
    echo "</div></div><br>\n";

    $clone_id = isset($_GET['clone_id']) ? (int)$_GET['clone_id'] : 0;
    if ($clone_id > 0) {
        $xtmod           = new nw_NewsTopic($clone_id);
        $clone_name      = $xtmod->clone_name('E');
        $clone_dir       = $xtmod->clone_dir('E');
        $clone_version   = $xtmod->clone_version('E');
        $op              = 'modClone';
        $btnlabel        = _AM_NW_MODIFY;
        $parent          = $xtmod->topic_pid();
        $formlabel       = _AM_NW_MODIFYTOPIC;
        $oldnewsimport   = $xtmod->menu();
        $topic_frontpage = $xtmod->topic_frontpage();
        $topic_color     = $xtmod->topic_color();
        unset($xtmod);
    } else {
        $clone_name      = '';
        $clone_frontpage = 1;
        $clone_dir       = '';
        $op              = 'addTopic';
        $btnlabel        = _AM_NW_ADD;
        $parent          = -1;
        $oldnewsimport   = 0;
        $clone_version   = '';
        $formlabel       = _AM_NW_ADD_TOPIC;
    }

    //Draw Form
    $sform = new XoopsThemeForm(_AM_NW_CLONER_ADD, 'clonerform', XNEWS_MODULE_URL . '/admin/clone.php', 'post', true);

    $filedir_tray = new XoopsFormElementTray(_AM_NW_CLONER_NEWNAME, '');
    $label        = sprintf(_AM_NW_CLONER_NEWNAMEDESC, $xnews->getModule()->name());
    $filedir_tray->addElement(new XoopsFormLabel($label), false);
    $filedir_tray->addElement(new XoopsFormText(_AM_NW_CLONER_NEWNAMELABEL, 'clone_modulename', 50, 255, $clone_modulename), true);
    $sform->addElement($filedir_tray);

    $sform->addElement(new XoopsFormHidden('op', 'clonerapply'), false);

    $button_tray = new XoopsFormElementTray('', '');
    $submit_btn  = new XoopsFormButton('', 'post', _SUBMIT, 'submit');
    $button_tray->addElement($submit_btn);
    $sform->addElement($button_tray);
    $sform->display();

    //recalc original subprefix
    $sub     = nw_remove_numbers(XNEWS_SUBPREFIX);
    $result2 = $GLOBALS['xoopsDB']->query('SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix($sub . '_stories'));
    $count   = $GLOBALS['xoopsDB']->getRowsNum($result2);

    $tmpmoduleHandler = xoops_getHandler('module');
}

/**
 * Cloner Apply - DNPROSSI
 */
function NewsClonerApply()
{
    $xnews               = XnewsXnews::getInstance();
    $nw_NewsStoryHandler = new nw_NewsStory();

    require_once __DIR__ . '/cloner.php';
    if (!empty($_POST['clone_modulename'])) {
        $module_version = $xnews->getModule()->version();
        $old_dirname    = $xnews->getModule()->dirname();
        $old_modulename = $xnews->getModule()->name();
        $old_subprefix  = XNEWS_SUBPREFIX;

        $new_modulename = $_POST['clone_modulename'];

        $new_dirname    = strtolower(str_replace(' ', '', $new_modulename));
        $new_modulename = ucwords(strtolower($new_modulename));

        //Select last id for new sub-prefix.
        $result         = $GLOBALS['xoopsDB']->query("SHOW TABLE STATUS LIKE '" . $GLOBALS['xoopsDB']->prefix('news_clonerdata') . "'");
        $row            = $GLOBALS['xoopsDB']->fetchArray($result);
        $Auto_increment = $row['Auto_increment'];

        $new_subprefix = 'nw' . (string)$Auto_increment;
        //trigger_error($result. ' ---- ' .$count. ' ---- ' .$new_subprefix , E_USER_WARNING);

        $patterns = [
            $old_dirname                                   => $new_dirname,
            '$modversion["original"] = 1;'                 => '$modversion["original"] = 0;',
            '$modversion["name"] = "' . 'x' . 'News' . '"' => '$modversion["name"] = "' . $new_modulename . '"',
            $old_subprefix                                 => strtolower($new_subprefix),
            strtoupper($old_subprefix)                     => strtoupper($new_subprefix)
        ];

        $patKeys   = array_keys($patterns);
        $patValues = array_values($patterns);

        $newPath = str_replace($patKeys[0], $patValues[0], XNEWS_MODULE_PATH);
        $oldlogo = $newPath . '/' . $old_dirname . '_logo.png';
        $newlogo = $newPath . '/' . $new_dirname . '_logo.png';

        if (!is_dir($newPath)) { //&& !$old_subprefix == $new_subprefix ) {
            nw_cloneFileFolder(XNEWS_MODULE_PATH, $patterns);
            //rename logo.png
            @rename($oldlogo, $newlogo);
            //trigger_error($oldlogo. ' ---- ' .$newlogo , E_USER_WARNING);

            $path_array[0] = $newPath . '/templates/';
            $path_array[1] = $newPath . '/templates/blocks/';

            // check all files in dir, and process them
            nw_clonefilename($path_array, $old_subprefix, $new_subprefix);

            //Add cloned module to cloner dtb
            $sql    = 'INSERT';
            $sql    .= ' INTO ' . $GLOBALS['xoopsDB']->prefix('news_clonerdata') . ' (clone_name, clone_dir, clone_version, clone_subprefix, clone_installed)';
            $sql    .= " VALUES ('" . $new_modulename . "', '" . $new_dirname . "', '" . $module_version . "', '" . $new_subprefix . "', 0);";
            $result = $GLOBALS['xoopsDB']->query($sql);

            $label = sprintf(_AM_NW_CLONER_CREATED, $new_modulename);
            redirect_header('clone.php?op=cloner', 3, $label);
        } else {
            $label = sprintf(_AM_NW_CLONER_DIREXISTS, $new_dirname);
            redirect_header('clone.php?op=cloner', 3, $label);
        }
    }
}

/**
 * Clone Upgrade - DNPROSSI
 */
function CloneUpgrade()
{
    $xnews               = XnewsXnews::getInstance();
    $nw_NewsStoryHandler = new nw_NewsStory();
    require_once __DIR__ . '/cloner.php';
    //
    if (!isset($_GET['clone_id'])) {
        //trigger_error("Not set", E_USER_WARNING);
        redirect_header('clone.php?op=cloner', 3, _AM_NW_CLONER_NOMODULEID);
    } else {
        $cloneid = $_GET['clone_id'];
        $result  = $GLOBALS['xoopsDB']->query('SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('news_clonerdata') . ' WHERE clone_id = ' . $cloneid);
        $ix      = 0;
        while ($clone = $GLOBALS['xoopsDB']->fetchArray($result)) {
            $clone_arr[$ix] = $clone;
            $ix++;
        }
        $org_modulename = $xnews->getModule()->name();
        $org_dirname    = $xnews->getModule()->dirname();
        $org_version    = $xnews->getModule()->version();
        $org_subprefix  = XNEWS_SUBPREFIX;
        //
        $upg_modulename = $clone_arr[0]['clone_name'];
        $upg_dirname    = $clone_arr[0]['clone_dir'];
        $upg_version    = $clone_arr[0]['clone_version'];
        $upg_subprefix  = $clone_arr[0]['clone_subprefix'];
        //
        $patterns = [
            $org_dirname                                   => $upg_dirname,
            '$modversion["original"] = 1;'                 => '$modversion["original"] = 0;',
            '$modversion["name"] = "' . 'x' . 'News' . '"' => '$modversion["name"] = "' . $upg_modulename . '"',
            $org_subprefix                                 => strtolower($upg_subprefix),
            strtoupper($org_subprefix)                     => strtoupper($upg_subprefix),
        ];
        //
        $patKeys   = array_keys($patterns);
        $patValues = array_values($patterns);
        //
        $newPath = str_replace($patKeys[0], $patValues[0], XNEWS_MODULE_PATH);
        // IN PROGRESS
        // IN PROGRESS
        // IN PROGRESS
        $oldlogo = $newPath . '/' . $org_dirname . '_logo.png';
        $newlogo = $newPath . '/' . $upg_dirname . '_logo.png';
        //
        nw_cloneFileFolder(XNEWS_MODULE_PATH, $patterns);
        //rename logo.png
        @rename($oldlogo, $newlogo);
        $path_array[0] = $newPath . '/templates/';
        $path_array[1] = $newPath . '/templates/blocks/';
        nw_deleteclonefile($path_array, $upg_subprefix);
        // check all files in dir, and process them
        nw_clonefilename($path_array, $org_subprefix, $upg_subprefix);
        //Update module dtb
        $GLOBALS['xoopsDB']->queryF('UPDATE ' . $GLOBALS['xoopsDB']->prefix('news_clonerdata') . ' SET clone_version  = ' . $org_version . ' WHERE clone_id = ' . $cloneid);
        $label = sprintf(_AM_NW_CLONER_UPRADED, $upg_modulename);
        redirect_header('clone.php?op=cloner', 3, $label);
    }
}

/**
 * Delete Clone - DNPROSSI - 1.68 RC1
 */
function CloneDelete()
{
    $xnews               = XnewsXnews::getInstance();
    $nw_NewsStoryHandler = new nw_NewsStory();
    //  admin navigation
    xoops_cp_header();
    $adminObject = \Xmf\Module\Admin::getInstance();
    $adminObject->displayNavigation('clone.php?op=cloner');
    //
    xoops_load('XoopsFormLoader');
    //
    if (!isset($_GET['clone_id'])) {
        redirect_header('clone.php?op=cloner', 3, _AM_NW_CLONER_CLONEID);
    } else {
        $cloneid = $_GET['clone_id'];
        $result  = $GLOBALS['xoopsDB']->query('SELECT * FROM ' . $GLOBALS['xoopsDB']->prefix('news_clonerdata') . ' WHERE clone_id = ' . $cloneid);
        $ix      = 0;
        while ($clone = $GLOBALS['xoopsDB']->fetchArray($result)) {
            $clone_arr[$ix] = $clone;
            $ix++;
        }
        $module_dirname = $clone_arr[0]['clone_dir'];
        echo "<div id='NewsCloner' style='text-align: center;'>";
        echo '<h2>' . _AM_NW_CLONER_CLONEDELETION . '</h2>';
        echo '</div>';
        //echo "<div style='text-align: center;'>";
        $message = sprintf(_AM_NW_CLONER_SUREDELETE, $module_dirname);
        xoops_confirm(['op' => 'clonedeleteapply', 'clone_id' => $cloneid, 'ok' => 1, 'module_name' => $module_dirname], 'clone.php', $message);
    }
}

/**
 * Apply Delete Clone - DNPROSSI - 1.68 RC1
 */
function CloneDeleteApply()
{
    $xnews               = XnewsXnews::getInstance();
    $nw_NewsStoryHandler = new nw_NewsStory();
    require_once __DIR__ . '/cloner.php';
    //
    //trigger_error("Not set", E_USER_WARNING);
    if (!isset($_POST['clone_id'])) {
        redirect_header('clone.php?op=cloner', 3, _AM_NW_CLONER_CLONEID);
    } else {
        $del_dirname = $_POST['module_name'];
        $delPath1    = XOOPS_ROOT_PATH . '/modules/' . $del_dirname;
        $delPath2    = XOOPS_ROOT_PATH . '/uploads/' . $del_dirname;
        if (file_exists($delPath2) && is_dir($delPath2)) {
            if (true === nw_removewholeclone($delPath1) && true === nw_removewholeclone($delPath2)) {
                $label = sprintf(_AM_NW_CLONER_CLONEDELETED, $del_dirname);
                redirect_header('clone.php?op=cloner', 3, $label);
            } else {
                $label = sprintf(_AM_NW_CLONER_CLONEDELETEERR, $del_dirname);
                redirect_header('clone.php?op=cloner', 3, $label);
            }
        } elseif (true === nw_removewholeclone($delPath1)) {
            $label = sprintf(_AM_NW_CLONER_CLONEDELETED, $del_dirname);
            redirect_header('clone.php?op=cloner', 3, $label);
        } else {
            $label = sprintf(_AM_NW_CLONER_CLONEDELETEERR, $del_dirname);
            redirect_header('clone.php?op=cloner', 3, $label);
        }
    }
}

// **********************************************************************************************************************************************
// **** Main
// **********************************************************************************************************************************************

$op = Request::getString('op', 'cloner');

switch ($op) {
    case 'cloner':
        NewsCloner();
        xoops_cp_footer();
        break;

    case 'clonerapply':
        NewsClonerApply();
        xoops_cp_footer();
        break;

    case 'cloneupgrade':
        CloneUpgrade();
        xoops_cp_footer();
        break;

    case 'clonedelete':
        CloneDelete();
        xoops_cp_footer();
        break;

    case 'clonedeleteapply':
        CloneDeleteApply();
        xoops_cp_footer();
        break;
}
