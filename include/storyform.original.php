<?php
xoops_loadLanguage('admin', $xnews->getModule()->dirname());

if (file_exists(XOOPS_ROOT_PATH . '/language/' . $xoopsConfig['language'] . '/calendar.php')) {
    require_once XOOPS_ROOT_PATH . '/language/' . $xoopsConfig['language'] . '/calendar.php';
} else {
    require_once XOOPS_ROOT_PATH . '/language/english/calendar.php';
}
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XNEWS_MODULE_PATH . '/include/functions.php';

$sform = new XoopsThemeForm(_MA_NW_SUBMITNEWS, 'storyform', XNEWS_MODULE_URL . '/submit.php');
$sform->setExtra('enctype="multipart/form-data"');
$sform->addElement(new XoopsFormText(_MA_NW_TITLE, 'title', 50, 255, $title), true);

// Topic's selection box
if (!isset($xt)) {
    $xt = new nw_NewsTopic();
}

if ($xt->getAllTopicsCount() == 0) {
    redirect_header('index.php', 3, _MA_NW_POST_SORRY);
}

require_once XOOPS_ROOT_PATH . '/class/tree.php';
$allTopics    = $xt->getAllTopics($xnews->getConfig('restrictindex'), 'nw_submit');
$topic_tree   = new XoopsObjectTree($allTopics, 'topic_id', 'topic_pid');
$topic_select = $topic_tree->makeSelBox('topic_id', 'topic_title', '-- ', $topicid, false);
$sform->addElement(new XoopsFormLabel(_MA_NW_TOPIC, $topic_select));

//If admin - show admin form
//TODO: Change to "If submit privilege"
if ($approveprivilege) {
    //Show topic image?
    $sform->addElement(new XoopsFormRadioYN(_AM_NW_TOPICDISPLAY, 'topicdisplay', $topicdisplay));
    //Select image position
    $posselect = new XoopsFormSelect(_AM_NW_TOPICALIGN, 'topicalign', $topicalign);
    $posselect->addOption('R', _AM_NW_RIGHT);
    $posselect->addOption('L', _AM_NW_LEFT);
    $sform->addElement($posselect);
    //Publish in home?
    //TODO: Check that pubinhome is 0 = no and 1 = yes (currently vice versa)
    $sform->addElement(new XoopsFormRadioYN(_AM_NW_PUBINHOME, 'ihome', $ihome, _NO, _YES));
}

// news author
if ($approveprivilege && is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
    if (!isset($newsauthor)) {
        $newsauthor = $xoopsUser->getVar('uid');
    }
    $memberHandler = xoops_getHandler('member');
    $usercount     = $memberHandler->getUserCount();
    if ($usercount < $cfg['config_max_users_list']) {
        $sform->addElement(new XoopsFormSelectUser(_MA_NW_AUTHOR, 'author', true, $newsauthor), false);
    } else {
        $sform->addElement(new XoopsFormText(_MA_NW_AUTHOR_ID, 'author', 10, 10, $newsauthor), false);
    }
}
$editor = nw_getWysiwygForm(_MA_NW_THESCOOP, 'hometext', $hometext, 15, 60, '100%', '350px', 'hometext_hidden');
$sform->addElement($editor, true);

//Extra info
//If admin -> if submit privilege
if ($approveprivilege) {
    $editor2 = nw_getWysiwygForm(_AM_NW_EXTEXT, 'bodytext', $bodytext, 15, 60, '100%', '350px', 'bodytext_hidden');
    $sform->addElement($editor2, false);

    if ($xnews->getConfig('tags')) {
        $itemIdForTag = isset($storyid) ? $storyid : 0;
        require_once XOOPS_ROOT_PATH . '/modules/tag/include/formtag.php';
        $sform->addElement(new XoopsFormTag('item_tag', 60, 255, $itemIdForTag, 0));
    }

    if ($xnews->getConfig('metadata')) {
        if ($xnews->getConfig('extendmetadata') == 1) {
            $textmaxlength_script = "\n
            <script type='text/javascript'><!--// \n
                function EnforceMaximumLength(fld, len) { \n
                    if(fld.value.length > len) {\n
                        alert('" . _MA_NW_METASIZE . "'); \n
                        fld.value = fld.value.substr(0,len); \n
                    } \n
                } \n
            //--></script>";
            echo $textmaxlength_script;
            $desctextextra = new xoopsFormTextArea(_MA_NW_META_DESCRIPTION, 'description', $description, 4, 60);
            $desctextextra->setExtra('onkeyup="EnforceMaximumLength(this,10)"');
            $sform->addElement($desctextextra);

            $keytextextra = new xoopsFormTextArea(_MA_NW_META_KEYWORDS, 'keywords', $keywords, 4, 60);
            $keytextextra->setExtra('onkeyup="EnforceMaximumLength(this,10)"');
            $sform->addElement($keytextextra);
        } else {
            $sform->addElement(new xoopsFormText(_MA_NW_META_DESCRIPTION, 'description', 50, 255, $description), false);
            $sform->addElement(new xoopsFormText(_MA_NW_META_KEYWORDS, 'keywords', 50, 255, $keywords), false);
        }
    }
}

// Manage upload(s)
$allowupload = false;
switch ($xnews->getConfig('uploadgroups')) {
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
    if ($op == 'edit') {
        $sfiles   = new nw_sFiles();
        $filesarr = array();
        $filesarr = $sfiles->getAllbyStory($storyid);
        if (count($filesarr) > 0) {
            $upl_tray     = new XoopsFormElementTray(_AM_NW_UPLOAD_ATTACHFILE, '<br>');
            $upl_checkbox = new XoopsFormCheckBox('', 'delupload[]');

            foreach ($filesarr as $onefile) {
                $link = sprintf("<a href='%s/%s' target='_blank'>%s</a>\n", XNEWS_ATTACHED_FILES_URL, $onefile->getDownloadname('S'), $onefile->getFileRealName('S'));
                $upl_checkbox->addOption($onefile->getFileid(), $link);
            }
            $upl_tray->addElement($upl_checkbox, false);
            $dellabel = new XoopsFormLabel(_AM_NW_DELETE_SELFILES, '');
            $upl_tray->addElement($dellabel, false);
            $sform->addElement($upl_tray);
        }
    }
    $sform->addElement(new XoopsFormFile(_AM_NW_SELFILE, 'attachedfile', $xnews->getConfig('maxuploadsize')), false);
    if ($op == 'edit') {
        if (isset($picture) && xoops_trim($picture) != '') {
            $pictureTray = new XoopsFormElementTray(_MA_NW_CURENT_PICTURE, '<br>');
            $pictureTray->addElement(new XoopsFormLabel('', "<img src='" . XNEWS_TOPICS_FILES_URL . '/' . $picture . "'>"));
            $deletePicureCheckbox = new XoopsFormCheckBox('', 'deleteimage', 0);
            $deletePicureCheckbox->addOption(1, _DELETE);
            $pictureTray->addElement($deletePicureCheckbox);
            $sform->addElement($pictureTray);
        }
    }
    //DNPROSSI - 1.71
    if ($xnews->getConfig('images_display')) {
        //Select image rows
        $image_rows = new XoopsFormSelect(_AM_NW_IMAGE_ROWS, 'imagerows', $imagerows);
        $image_rows->addOption(1, '1');
        $image_rows->addOption(2, '2');
        $image_rows->addOption(3, '3');
        $image_rows->addOption(4, '4');
        $image_rows->addOption(5, '5');
        $sform->addElement($image_rows);
    }
    if ($xnews->getConfig('pdf_display')) {
        //Select pdf rows
        $pdf_rows = new XoopsFormSelect(_AM_NW_PDF_ROWS, 'pdfrows', $pdfrows);
        $pdf_rows->addOption(1, '1');
        $pdf_rows->addOption(2, '2');
        $pdf_rows->addOption(3, '3');
        $pdf_rows->addOption(4, '4');
        $pdf_rows->addOption(5, '5');
        $sform->addElement($pdf_rows);
    }
    $sform->addElement(new XoopsFormFile(_MA_NW_SELECT_IMAGE, 'attachedimage', $xnews->getConfig('maxuploadsize')), false);
}

$option_tray = new XoopsFormElementTray(_OPTIONS, '<br>');
//Set date of publish/expiration
if ($approveprivilege) {
    if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
        $approve = 1;
    }
    $approve_checkbox = new XoopsFormCheckBox('', 'approve', $approve);
    $approve_checkbox->addOption(1, _AM_NW_APPROVE);
    $option_tray->addElement($approve_checkbox);

    $check              = $published > 0 ? 1 : 0;
    $published_checkbox = new XoopsFormCheckBox('', 'autodate', $check);
    $published_checkbox->addOption(1, _AM_NW_SETDATETIME);
    $option_tray->addElement($published_checkbox);

    $option_tray->addElement(new XoopsFormDateTime(_AM_NW_SETDATETIME, 'publish_date', 15, $published));

    $check            = $expired > 0 ? 1 : 0;
    $expired_checkbox = new XoopsFormCheckBox('', 'autoexpdate', $check);
    $expired_checkbox->addOption(1, _AM_NW_SETEXPDATETIME);
    $option_tray->addElement($expired_checkbox);

    $option_tray->addElement(new XoopsFormDateTime(_AM_NW_SETEXPDATETIME, 'expiry_date', 15, $expired));
}

if (is_object($xoopsUser)) {
    $notify_checkbox = new XoopsFormCheckBox('', 'notifypub', $notifypub);
    $notify_checkbox->addOption(1, _MA_NW_NOTIFYPUBLISH);
    $option_tray->addElement($notify_checkbox);
    if ($xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
        $nohtml_checkbox = new XoopsFormCheckBox('', 'nohtml', $nohtml);
        $nohtml_checkbox->addOption(1, _DISABLEHTML);
        $option_tray->addElement($nohtml_checkbox);
    }
}
$smiley_checkbox = new XoopsFormCheckBox('', 'nosmiley', $nosmiley);
$smiley_checkbox->addOption(1, _DISABLESMILEY);
$option_tray->addElement($smiley_checkbox);

//DNPROSSI - dobr
$linebreak_checkbox = new XoopsFormCheckBox('', 'dobr', $dobr);
$linebreak_checkbox->addOption(1, _AM_NW_DOLINEBREAK);
$option_tray->addElement($linebreak_checkbox);

$sform->addElement($option_tray);

//TODO: Approve checkbox + "Move to top" if editing + Edit indicator

//Submit buttons
$button_tray = new XoopsFormElementTray('', '');
$preview_btn = new XoopsFormButton('', 'preview', _PREVIEW, 'submit');
$preview_btn->setExtra('accesskey="p"');
$button_tray->addElement($preview_btn);
$submit_btn = new XoopsFormButton('', 'post', _MA_NW_POST, 'submit');
$submit_btn->setExtra('accesskey="s"');
$button_tray->addElement($submit_btn);
$sform->addElement($button_tray);

//Hidden variables
if (isset($storyid)) {
    $sform->addElement(new XoopsFormHidden('storyid', $storyid));
}

if (!isset($returnside)) {
    $returnside = isset($_POST['returnside']) ? intval($_POST['returnside']) : 0;
    if (empty($returnside)) {
        $returnside = isset($_GET['returnside']) ? intval($_GET['returnside']) : 0;
    }
}

if (!isset($returnside)) {
    $returnside = 0;
}
$sform->addElement(new XoopsFormHidden('returnside', $returnside), false);

if (!isset($type)) {
    if ($approveprivilege) {
        $type = 'admin';
    } else {
        $type = 'user';
    }
}
$type_hidden = new XoopsFormHidden('type', $type);
$sform->addElement($type_hidden);
$sform->display();
