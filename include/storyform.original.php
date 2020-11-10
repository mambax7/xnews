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
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

use XoopsModules\Xnews;

defined('XOOPS_ROOT_PATH') || exit('Restricted access');

xoops_loadLanguage('admin', XNEWS_MODULE_DIRNAME);
xoops_loadLanguage('calendar');

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XNEWS_MODULE_PATH . '/include/functions.php';
// require_once XNEWS_MODULE_PATH . '/class/Utility.php';

$sform = new \XoopsThemeForm(_MD_XNEWS_SUBMITNEWS, 'storyform', XNEWS_MODULE_URL . '/submit.php');
$sform->setExtra('enctype="multipart/form-data"');
$sform->addElement(new \XoopsFormText(_MD_XNEWS_TITLE, 'title', 50, 255, $title), true);

// Topic's selection box
if (!isset($xt)) {
    $xt = new Xnews\NewsTopic();
}

if (0 == $xt->getAllTopicsCount()) {
    redirect_header('index.php', 3, _MD_XNEWS_POST_SORRY);
}

require_once XOOPS_ROOT_PATH . '/class/tree.php';
$allTopics  = $xt->getAllTopics($helper->getConfig('restrictindex'), 'nw_submit');
$topic_tree = new \XoopsObjectTree($allTopics, 'topic_id', 'topic_pid');
//$topic_select = $topic_tree->makeSelBox('topic_id', 'topic_title', '-- ', $topicid, false);

$moduleDirName = basename(dirname(__DIR__));
/** @var \XoopsModules\Xnews\Helper $helper */
$helper = \XoopsModules\Xnews\Helper::getInstance();
$module = $helper->getModule();

if (Xnews\Utility::checkVerXoops($module, '2.5.9')) {
    //         $topic_select = $topic_tree->makeSelBox('storytopic', 'topic_title', '-- ', $xoopsOption['storytopic'], true);
    $topic_select = $topic_tree->makeSelectElement('topic_id', 'topic_title', '--', $topicid, false, 0, '', '');
    $sform->addElement($topic_select);
} else {
    $topic_select = $topic_tree->makeSelBox('topic_id', 'topic_title', '-- ', $topicid, false);
    $sform->addElement(new \XoopsFormLabel(_MD_XNEWS_TOPIC, $topic_select));
}

//If admin - show admin form
//TODO: Change to "If submit privilege"
if ($approveprivilege) {
    //Show topic image?
    $sform->addElement(new \XoopsFormRadioYN(_AM_XNEWS_TOPICDISPLAY, 'topicdisplay', $topicdisplay));
    //Select image position
    $posselect = new \XoopsFormSelect(_AM_XNEWS_TOPICALIGN, 'topicalign', $topicalign);
    $posselect->addOption('R', _AM_XNEWS_RIGHT);
    $posselect->addOption('L', _AM_XNEWS_LEFT);
    $sform->addElement($posselect);
    //Publish in home?
    //TODO: Check that pubinhome is 0 = no and 1 = yes (currently vice versa)
    $sform->addElement(new \XoopsFormRadioYN(_AM_XNEWS_PUBINHOME, 'ihome', $ihome, _NO, _YES));
}

// news author
if ($approveprivilege && is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
    global $cfg;
    if (!isset($newsauthor)) {
        $newsauthor = $xoopsUser->getVar('uid');
    }
    /** @var \XoopsMemberHandler $memberHandler */
    $memberHandler = xoops_getHandler('member');
    $usercount     = $memberHandler->getUserCount();
    if (isset( $cfg['config_max_users_list']) && $usercount < $cfg['config_max_users_list']) {
        $sform->addElement(new \XoopsFormSelectUser(_MD_XNEWS_AUTHOR, 'author', true, $newsauthor), false);
    } else {
        $sform->addElement(new \XoopsFormText(_MD_XNEWS_AUTHOR_ID, 'author', 10, 10, $newsauthor), false);
    }
}
$editor = nw_getWysiwygForm(_MD_XNEWS_THESCOOP, 'hometext', $hometext, 15, 60, '100%', '350px', 'hometext_hidden');
$sform->addElement($editor, true);

//Extra info
//If admin -> if submit privilege
if ($approveprivilege) {
    $editor2 = nw_getWysiwygForm(_AM_XNEWS_EXTEXT, 'bodytext', $bodytext, 15, 60, '100%', '350px', 'bodytext_hidden');
    $sform->addElement($editor2, false);

    if ($helper->getConfig('tags')) {
        $itemIdForTag = isset($storyid) ? $storyid : 0;
        require_once XOOPS_ROOT_PATH . '/modules/tag/include/formtag.php';
        $sform->addElement(new \XoopsModules\Tag\FormTag('item_tag', 60, 255, $itemIdForTag, 0));
    }

    if ($helper->getConfig('metadata')) {
        if (1 == $helper->getConfig('extendmetadata')) {
            $textmaxlength_script = "\n
            <script type='text/javascript'><!--// \n
                function EnforceMaximumLength(fld, len) { \n
                    if(fld.value.length > len) {\n
                        alert('" . _MD_XNEWS_METASIZE . "'); \n
                        fld.value = fld.value.substr(0,len); \n
                    } \n
                } \n
            //--></script>";
            echo $textmaxlength_script;
            $desctextextra = new \XoopsFormTextArea(_MD_XNEWS_META_DESCRIPTION, 'description', $description, 4, 60);
            $desctextextra->setExtra('onkeyup="EnforceMaximumLength(this,10)"');
            $sform->addElement($desctextextra);

            $keytextextra = new \XoopsFormTextArea(_MD_XNEWS_META_KEYWORDS, 'keywords', $keywords, 4, 60);
            $keytextextra->setExtra('onkeyup="EnforceMaximumLength(this,10)"');
            $sform->addElement($keytextextra);
        } else {
            $sform->addElement(new \XoopsFormText(_MD_XNEWS_META_DESCRIPTION, 'description', 50, 255, $description), false);
            $sform->addElement(new \XoopsFormText(_MD_XNEWS_META_KEYWORDS, 'keywords', 50, 255, $keywords), false);
        }
    }
}

// Manage upload(s)
$allowupload = false;
switch ($helper->getConfig('uploadgroups')) {
    case 1: //Submitters and Approvers
        $allowupload = true;
        break;
    case 2: //Approvers only
        $allowupload = $approveprivilege ? true : false;
        break;
    case 3: //Upload Disabled
        $allowupload = false;
        break;
}

if ($allowupload) {
    if ('edit' === $op) {
        $sfiles   = new Xnews\Files();
        $filesarr = [];
        $filesarr = $sfiles->getAllbyStory($storyid);
        if (count($filesarr) > 0) {
            $upl_tray     = new \XoopsFormElementTray(_AM_XNEWS_UPLOAD_ATTACHFILE, '<br>');
            $upl_checkbox = new \XoopsFormCheckBox('', 'delupload[]');

            foreach ($filesarr as $onefile) {
                $link = sprintf("<a href='%s/%s' target='_blank'>%s</a>\n", XNEWS_ATTACHED_FILES_URL, $onefile->getDownloadname('S'), $onefile->getFileRealName('S'));
                $upl_checkbox->addOption($onefile->getFileid(), $link);
            }
            $upl_tray->addElement($upl_checkbox, false);
            $dellabel = new \XoopsFormLabel(_AM_XNEWS_DELETE_SELFILES, '');
            $upl_tray->addElement($dellabel, false);
            $sform->addElement($upl_tray);
        }
    }
    $sform->addElement(new \XoopsFormFile(_AM_XNEWS_SELFILE, 'attachedfile', $helper->getConfig('maxuploadsize')), false);
    if ('edit' === $op) {
        if (isset($picture) && '' != xoops_trim($picture)) {
            $pictureTray = new \XoopsFormElementTray(_MD_XNEWS_CURENT_PICTURE, '<br>');
            $pictureTray->addElement(new \XoopsFormLabel('', "<img src='" . XNEWS_TOPICS_FILES_URL . '/' . $picture . "'>"));
            $deletePicureCheckbox = new \XoopsFormCheckBox('', 'deleteimage', 0);
            $deletePicureCheckbox->addOption(1, _DELETE);
            $pictureTray->addElement($deletePicureCheckbox);
            $sform->addElement($pictureTray);
        }
    }
    //DNPROSSI - 1.71
    if ($helper->getConfig('images_display')) {
        //Select image rows
        $image_rows = new \XoopsFormSelect(_AM_XNEWS_IMAGE_ROWS, 'imagerows', $imagerows);
        $image_rows->addOption(1, '1');
        $image_rows->addOption(2, '2');
        $image_rows->addOption(3, '3');
        $image_rows->addOption(4, '4');
        $image_rows->addOption(5, '5');
        $sform->addElement($image_rows);
    }
    if ($helper->getConfig('pdf_display')) {
        //Select pdf rows
        $pdf_rows = new \XoopsFormSelect(_AM_XNEWS_PDF_ROWS, 'pdfrows', $pdfrows);
        $pdf_rows->addOption(1, '1');
        $pdf_rows->addOption(2, '2');
        $pdf_rows->addOption(3, '3');
        $pdf_rows->addOption(4, '4');
        $pdf_rows->addOption(5, '5');
        $sform->addElement($pdf_rows);
    }
    $sform->addElement(new \XoopsFormFile(_MD_XNEWS_SELECT_IMAGE, 'attachedimage', $helper->getConfig('maxuploadsize')), false);
}

$option_tray = new \XoopsFormElementTray(_OPTIONS, '<br>');
//Set date of publish/expiration
if ($approveprivilege) {
    if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
        $approve = 1;
    }
    $approve_checkbox = new \XoopsFormCheckBox('', 'approve', $approve);
    $approve_checkbox->addOption(1, _AM_XNEWS_APPROVE);
    $option_tray->addElement($approve_checkbox);

    $check              = $published > 0 ? 1 : 0;
    $published_checkbox = new \XoopsFormCheckBox('', 'autodate', $check);
    $published_checkbox->addOption(1, _AM_XNEWS_SETDATETIME);
    $option_tray->addElement($published_checkbox);

    $option_tray->addElement(new \XoopsFormDateTime(_AM_XNEWS_SETDATETIME, 'publish_date', 15, $published));

    $check            = $expired > 0 ? 1 : 0;
    $expired_checkbox = new \XoopsFormCheckBox('', 'autoexpdate', $check);
    $expired_checkbox->addOption(1, _AM_XNEWS_SETEXPDATETIME);
    $option_tray->addElement($expired_checkbox);

    $option_tray->addElement(new \XoopsFormDateTime(_AM_XNEWS_SETEXPDATETIME, 'expiry_date', 15, $expired));
}

if (is_object($xoopsUser)) {
    $notify_checkbox = new \XoopsFormCheckBox('', 'notifypub', $notifypub);
    $notify_checkbox->addOption(1, _MD_XNEWS_NOTIFYPUBLISH);
    $option_tray->addElement($notify_checkbox);
    if ($xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
        $nohtml_checkbox = new \XoopsFormCheckBox('', 'nohtml', $nohtml);
        $nohtml_checkbox->addOption(1, _DISABLEHTML);
        $option_tray->addElement($nohtml_checkbox);
    }
}
$smiley_checkbox = new \XoopsFormCheckBox('', 'nosmiley', $nosmiley);
$smiley_checkbox->addOption(1, _DISABLESMILEY);
$option_tray->addElement($smiley_checkbox);

//DNPROSSI - dobr
$linebreak_checkbox = new \XoopsFormCheckBox('', 'dobr', $dobr);
$linebreak_checkbox->addOption(1, _AM_XNEWS_DOLINEBREAK);
$option_tray->addElement($linebreak_checkbox);

$sform->addElement($option_tray);

//TODO: Approve checkbox + "Move to top" if editing + Edit indicator

//Submit buttons
$buttonTray  = new \XoopsFormElementTray('', '');
$preview_btn = new \XoopsFormButton('', 'preview', _PREVIEW, 'submit');
$preview_btn->setExtra('accesskey="p"');
$buttonTray->addElement($preview_btn);
$submit_btn = new \XoopsFormButton('', 'post', _MD_XNEWS_POST, 'submit');
$submit_btn->setExtra('accesskey="s"');
$buttonTray->addElement($submit_btn);
$sform->addElement($buttonTray);

//Hidden variables
if (isset($storyid)) {
    $sform->addElement(new \XoopsFormHidden('storyid', $storyid));
}

if (!isset($returnside)) {
    $returnside = \Xmf\Request::getInt('returnside', 0, 'POST');
    if (empty($returnside)) {
        $returnside = \Xmf\Request::getInt('returnside', 0, 'GET');
    }
}

if (!isset($returnside)) {
    $returnside = 0;
}
$sform->addElement(new \XoopsFormHidden('returnside', $returnside), false);

if (!isset($type)) {
    if ($approveprivilege) {
        $type = 'admin';
    } else {
        $type = 'user';
    }
}
$type_hidden = new \XoopsFormHidden('type', $type);
$sform->addElement($type_hidden);
$sform->display();
