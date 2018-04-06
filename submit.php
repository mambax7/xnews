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
 * @author       XOOPS Development Team
 */

use XoopsModules\Xnews;

if (!defined('XOOPS_ROOT_PATH')) {
    require_once __DIR__ . '/header.php';
}
// require_once XNEWS_MODULE_PATH . '/class/NewsStory.php';
// require_once XNEWS_MODULE_PATH . '/class/Files.php';
// require_once XNEWS_MODULE_PATH . '/class/NewsTopic.php';
require_once XOOPS_ROOT_PATH . '/class/uploader.php';

xoops_loadLanguage('admin', $helper->getModule()->dirname());

require_once XOOPS_ROOT_PATH . '/header.php';

$storyid = 0;

if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}

$grouppermHandler = xoops_getHandler('groupperm');

$perm_itemid = \Xmf\Request::getInt('topic_id', 0, 'POST');
//If no access
if (!$grouppermHandler->checkRight('nw_submit', $perm_itemid, $groups, $helper->getModule()->getVar('mid'))) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _NOPERM);
}
$op = 'form';

//If approve privileges
$approveprivilege = 0;
if (is_object($xoopsUser) && $grouppermHandler->checkRight('nw_approve', $perm_itemid, $groups, $helper->getModule()->getVar('mid'))) {
    $approveprivilege = 1;
}

if (isset($_POST['preview'])) {
    $op = 'preview';
} elseif (isset($_POST['post'])) {
    $op = 'post';
} elseif (isset($_GET['op']) && isset($_GET['storyid'])) {
    // Verify that the user can edit or delete an article
    if ('edit' === $_GET['op'] || 'delete' === $_GET['op']) {
        if (1 == $helper->getConfig('authoredit')) {
            $tmpstory = new Xnews\NewsStory(\Xmf\Request::getInt('storyid', 0, 'GET'));
            if (is_object($xoopsUser) && $xoopsUser->getVar('uid') != $tmpstory->uid() && !nw_is_admin_group()) {
                redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _NOPERM);
            }
        } else { // Users can't edit their articles
            if (!nw_is_admin_group()) {
                redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _NOPERM);
            }
        }
    }

    if ($approveprivilege && 'edit' === $_GET['op']) {
        $op      = 'edit';
        $storyid = \Xmf\Request::getInt('storyid', 0, 'GET');
    } elseif ($approveprivilege && 'delete' === $_GET['op']) {
        $op      = 'delete';
        $storyid = \Xmf\Request::getInt('storyid', 0, 'GET');
    } else {
        if ($helper->getConfig('authoredit') && is_object($xoopsUser) && isset($_GET['storyid']) && ('edit' === $_GET['op'] || 'preview' === $_POST['op'] || 'post' === $_POST['op'])) {
            $storyid = 0;
//            $storyid = isset($_GET['storyid']) ? (int)$_GET['storyid'] : (int)$_POST['storyid'];
            $storyid = \Xmf\Request::getInt('storyid', 0);
            if (!empty($storyid)) {
                $tmpstory = new Xnews\NewsStory($storyid);
                if ($tmpstory->uid() == $xoopsUser->getVar('uid')) {
                    $op = isset($_GET['op']) ? $_GET['op'] : $_POST['post'];
                    unset($tmpstory);
                    $approveprivilege = 1;
                } else {
                    unset($tmpstory);
                    if (!nw_is_admin_group()) {
                        redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _NOPERM);
                    } else {
                        $approveprivilege = 1;
                    }
                }
            }
        } else {
            if (!nw_is_admin_group()) {
                unset($tmpstory);
                redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _NOPERM);
            } else {
                $approveprivilege = 1;
            }
        }
    }
}

switch ($op) {
    case 'edit':
        if (!$approveprivilege) {
            redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _NOPERM);
            break;
        }
        //if($storyid==0 && isset($_POST['storyid'])) {
        //    $storyid=intval($_POST['storyid']);
        //}
        $story = new Xnews\NewsStory($storyid);
        if (!$grouppermHandler->checkRight('nw_view', $story->topicid(), $groups, $helper->getModule()->getVar('mid'))) {
            redirect_header(XNEWS_MODULE_URL . '/index.php', 0, _NOPERM);
        }
        echo "<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
        echo '<h4>' . _AM_XNEWS_EDITARTICLE . '</h4>';
        $title       = $story->title('Edit');
        $hometext    = $story->hometext('Edit');
        $bodytext    = $story->bodytext('Edit');
        $nohtml      = $story->nohtml();
        $nosmiley    = $story->nosmiley();
        $description = $story->description();
        $keywords    = $story->keywords();
        $ihome       = $story->ihome();
        $newsauthor  = $story->uid();
        $topicid     = $story->topicid();
        $notifypub   = $story->notifypub();
        $picture     = $story->picture();
        //DNPROSSI - 1.71
        $imagerows = $story->imagerows();
        $pdfrows   = $story->pdfrows();
        //DNPROSSI - dobr
        $dobr      = $story->dobr();
        $approve   = 0;
        $published = $story->published();
        if (isset($published) && $published > 0) {
            $approve = 1;
        }
        if (0 != $story->published()) {
            $published = $story->published();
        }
        if (0 != $story->expired()) {
            $expired = $story->expired();
        } else {
            $expired = 0;
        }
        $type         = $story->type();
        $topicdisplay = $story->topicdisplay();
        $topicalign   = $story->topicalign(false);
        if (!nw_is_admin_group()) {
            require_once XNEWS_MODULE_PATH . '/include/storyform.inc.php';
        } else {
            require_once XNEWS_MODULE_PATH . '/include/storyform.original.php';
        }
        echo '</td></tr></table>';
        break;

    case 'preview':
        $topic_id = \Xmf\Request::getInt('topic_id', 0, 'POST');
        $xt       = new Xnews\NewsTopic($topic_id);

        $storyid    = \Xmf\Request::getInt('storyid', 0);

        if (!empty($storyid)) {
            $story     = new Xnews\NewsStory($storyid);
            $published = $story->published();
            $expired   = $story->expired();
        } else {
            $story     = new Xnews\NewsStory();
            $published = \Xmf\Request::getInt('publish_date', 0, POST);
            if (!empty($published) && isset($_POST['autodate']) && (int)(1 == $_POST['autodate'])) {
                $published = strtotime($published['date']) + $published['time'];
            } else {
                $published = 0;
            }
            $expired = \Xmf\Request::getInt('expiry_date', 0, POST);
            if (!empty($expired) && isset($_POST['autoexpdate']) && (int)(1 == $_POST['autoexpdate'])) {
                $expired = strtotime($expired['date']) + $expired['time'];
            } else {
                $expired = 0;
            }
        }
        $topicid = $topic_id;

        $topicdisplay = \Xmf\Request::getInt('topicdisplay', 1, 'POST');


        $approve    = \Xmf\Request::getInt('approve', 0, 'POST');
        $topicalign = 'R';
        if (isset($_POST['topicalign'])) {
            $topicalign = $_POST['topicalign'];
        }
        $story->setTitle($_POST['title']);
        $story->setHometext($_POST['hometext']);
        if ($approveprivilege) {
            $story->setTopicdisplay($topicdisplay);
            $story->setTopicalign($topicalign);
            $story->setBodytext($_POST['bodytext']);
            if ($helper->getConfig('metadata')) {
                $story->Setkeywords($_POST['keywords']);
                $story->Setdescription($_POST['description']);
                $story->setIhome(\Xmf\Request::getInt('ihome', 0, 'POST'));
            }
        } else {
            $noname = \Xmf\Request::getInt('noname', 0, 'POST');
        }

        if ($approveprivilege || (is_object($xoopsUser) && $xoopsUser->isAdmin($helper->getModule()->mid()))) {
            if (isset($_POST['author'])) {
                $story->setUid(\Xmf\Request::getInt('author', 0, 'POST'));
            }
        }

        $notifypub = \Xmf\Request::getInt('notifypub', 0, 'POST');
        $nosmiley  = \Xmf\Request::getInt('nosmiley', 0, 'POST');
        if (isset($nosmiley) && (0 == $nosmiley || 1 == $nosmiley)) {
            $story->setNosmiley($nosmiley);
        } else {
            $nosmiley = 0;
        }
        if ($approveprivilege) {
            $nohtml = \Xmf\Request::getInt('nohtml', 0, 'POST');
            $story->setNohtml($nohtml);
            if (!isset($_POST['approve'])) {
                $approve = 0;
            }
        } else {
            $story->setNohtml = 1;
        }
        //DNPROSSI - dobr
        $dobr = \Xmf\Request::getInt('dobr', 0, 'POST');
        if (isset($dobr) && (0 == $dobr || 1 == $dobr)) {
            $story->setDobr($dobr);
        } else {
            $dobr = 0;
        }

        $title    = $story->title('InForm');
        $hometext = $story->hometext('InForm');
        if ($approveprivilege) {
            $bodytext    = $story->bodytext('InForm');
            $ihome       = $story->ihome();
            $description = $story->description('E');
            $keywords    = $story->keywords('E');
        }

        // Display post preview
        $newsauthor = $story->uid();
        $p_title    = $story->title('Preview');
        $p_hometext = $story->hometext('Preview');
        if ($approveprivilege) {
            $p_bodytext = $story->bodytext('Preview');
            $p_hometext .= '<br><br>' . $p_bodytext;
        }
        $topicalign2 = isset($story->topicalign) ? 'align="' . $story->topicalign() . '"' : '';
        $p_hometext  = (('' != $xt->topic_imgurl()) && $topicdisplay) ? '<img src="assets/images/topics/' . $xt->topic_imgurl() . '" ' . $topicalign2 . ' alt="">' . $p_hometext : $p_hometext;
        themecenterposts($p_title, $p_hometext);

        // Display post edit form
        $returnside = \Xmf\Request::getInt('returnside', 0, 'POST');
        require_once XNEWS_MODULE_PATH . '/include/storyform.inc.php';
        break;

    case 'post':
        $nohtml_db = \Xmf\Request::getInt('nohtml', 1, 'POST');
        if (is_object($xoopsUser)) {
            $uid = $xoopsUser->getVar('uid');
            if ($approveprivilege) {
                $nohtml_db = empty($_POST['nohtml']) ? 0 : 1;
            }
            if (isset($_POST['author']) && ($approveprivilege || $xoopsUser->isAdmin($helper->getModule()->mid()))) {
                $uid = \Xmf\Request::getInt('author', 0, 'POST');
            }
        } else {
            $uid = 0;
        }

        $storyid    = \Xmf\Request::getInt('storyid', 0);

        if (empty($storyid)) {
            $story    = new Xnews\NewsStory();
            $editmode = false;
        } else {
            $story    = new Xnews\NewsStory($storyid);
            $editmode = true;
        }
        $story->setUid($uid);
        $story->setTitle($_POST['title']);
        $story->setHometext($_POST['hometext']);
        $story->setTopicId(\Xmf\Request::getInt('topic_id', 0, 'POST'));
        $story->setHostname(xoops_getenv('REMOTE_ADDR'));
        $story->setNohtml($nohtml_db);
        $nosmiley = \Xmf\Request::getInt('nosmiley', 0, 'POST');
        $story->setNosmiley($nosmiley);
        $dobr = \Xmf\Request::getInt('dobr', 0, 'POST');
        $story->setDobr($dobr);
        //DNPROSSI 1.71
        $imagerows = \Xmf\Request::getInt('imagerows', 1, 'POST');
        $story->Setimagerows($imagerows);
        $pdfrows = \Xmf\Request::getInt('pdfrows', 1, 'POST');
        $story->Setpdfrows($pdfrows);
        $notifypub = \Xmf\Request::getInt('notifypub', 0, 'POST');
        $story->setNotifyPub($notifypub);
        $story->setType($_POST['type']);

        if (!empty($_POST['autodate']) && $approveprivilege) {
            //$publish_date = strtotime($_POST['publish_date']['date']) + $_POST['publish_date']['time'];
            $dateTimeObj = DateTime::createFromFormat(_SHORTDATESTRING, $_POST['publish_date']['date']);
            $dateTimeObj->setTime(0, 0, 0);
            $publish_date = $dateTimeObj->getTimestamp() + $_POST['publish_date']['time'];
            unset($dateTimeObj);
            //$offset = $xoopsUser -> timezone() - $xoopsConfig['server_TZ'];
            //$pubdate = $pubdate - ( $offset * 3600 );
            $story->setPublished($publish_date);
        }
        if (!empty($_POST['autoexpdate']) && $approveprivilege) {
            //$expiry_date = strtotime($_POST['expiry_date']['date']) + $_POST['expiry_date']['time'];
            $dateTimeObj = DateTime::createFromFormat(_SHORTDATESTRING, $_POST['expiry_date']['date']);
            $dateTimeObj->setTime(0, 0, 0);
            $expiry_date = $dateTimeObj->getTimestamp() + $_POST['expiry_date']['time'];
            unset($dateTimeObj);
            $offset      = $xoopsUser->timezone() - $xoopsConfig['server_TZ'];
            $expiry_date = $expiry_date - ($offset * 3600);
            $story->setExpired($expiry_date);
        } else {
            $story->setExpired(0);
        }

        if ($approveprivilege) {
            if ($helper->getConfig('metadata')) {
                $story->Setdescription($_POST['description']);
                $story->Setkeywords($_POST['keywords']);
            }
            $story->setTopicdisplay($_POST['topicdisplay']); // Display Topic Image ? (Yes or No)
            $story->setTopicalign($_POST['topicalign']); // Topic Align, 'Right' or 'Left'
            $story->setIhome($_POST['ihome']); // Publish in home ? (Yes or No)
            if (isset($_POST['bodytext'])) {
                $story->setBodytext($_POST['bodytext']);
            } else {
                $story->setBodytext(' ');
            }
            $approve = \Xmf\Request::getInt('approve', 0, 'POST');

            if (!$story->published() && $approve) {
                $story->setPublished(time());
            }
            if (!$story->expired()) {
                $story->setExpired(0);
            }

            if (!$approve) {
                $story->setPublished(0);
            }
        } elseif (1 == $helper->getConfig('autoapprove') && !$approveprivilege) {
            if (empty($storyid)) {
                $approve = 1;
            } else {
                $approve = \Xmf\Request::getInt('approve', 0, 'POST');
            }
            if ($approve) {
                $story->setPublished(time());
            } else {
                $story->setPublished(0);
            }
            $story->setExpired(0);
            $story->setTopicalign('R');
        } else {
            $approve = 0;
        }
        $story->setApproved($approve);

        if ($approve) {
            nw_updateCache();
        }

        // Increment author's posts count (only if it's a new article)
        // First case, it's not an anonyous, the story is approved and it's a new story
        if ($uid && $approve && empty($storyid)) {
            $tmpuser       = new \XoopsUser($uid);
            $memberHandler = xoops_getHandler('member');
            $memberHandler->updateUserByField($tmpuser, 'posts', $tmpuser->getVar('posts') + 1);
        }

        // Second case, it's not an anonymous, the story is NOT approved and it's NOT a new story (typical when someone is approving a submited story)
        if (is_object($xoopsUser) && $approve && !empty($storyid)) {
            $storytemp = new Xnews\NewsStory($storyid);
            if (!$storytemp->published() && $storytemp->uid() > 0) { // the article has been submited but not approved
                $tmpuser       = new \XoopsUser($storytemp->uid());
                $memberHandler = xoops_getHandler('member');
                $memberHandler->updateUserByField($tmpuser, 'posts', $tmpuser->getVar('posts') + 1);
            }
            unset($storytemp);
        }

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

        if ($allowupload && isset($_POST['deleteimage']) && 1 == \Xmf\Request::getInt('deleteimage', 0, 'POST')) {
            $currentPicture = $story->picture();
            if ('' != xoops_trim($currentPicture)) {
                $currentPicture = XNEWS_TOPICS_FILES_PATH . '/' . xoops_trim($story->picture());
                if (is_file($currentPicture) && file_exists($currentPicture)) {
                    if (!unlink($currentPicture)) {
                        trigger_error('Error, impossible to delete the picture attached to this article');
                    }
                }
            }
            $story->Setpicture('');
        }

        if ($allowupload) { // L'image
            if (isset($_POST['xoops_upload_file'])) {
                $fldname = $_FILES[$_POST['xoops_upload_file'][1]];
                $fldname = get_magic_quotes_gpc() ? stripslashes($fldname['name']) : $fldname['name'];
                if (xoops_trim('' != $fldname)) {
                    $sfiles         = new Xnews\Files();
                    $destname       = $sfiles->createUploadName(XNEWS_TOPICS_FILES_PATH, $fldname);
                    $permittedTypes = ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'];
                    $uploader       = new \XoopsMediaUploader(XNEWS_TOPICS_FILES_PATH, $permittedTypes, $helper->getConfig('maxuploadsize'));
                    $uploader->setTargetFileName($destname);
                    if ($uploader->fetchMedia($_POST['xoops_upload_file'][1])) {
                        if ($uploader->upload()) {
                            $fullPictureName = XNEWS_TOPICS_FILES_PATH . '/' . basename($destname);
                            $newName         = XNEWS_TOPICS_FILES_PATH . '/redim_' . basename($destname);
                            nw_resizePicture($fullPictureName, $newName, $helper->getConfig('maxwidth'), $helper->getConfig('maxheight'));
                            if (file_exists($newName)) {
                                @unlink($fullPictureName);
                                rename($newName, $fullPictureName);
                            }
                            $story->Setpicture(basename($destname));
                        } else {
                            echo _AM_XNEWS_UPLOAD_ERROR . ' ' . $uploader->getErrors();
                        }
                    } else {
                        echo $uploader->getErrors();
                    }
                }
            }
        }
        $destname = '';

        //WISHCRAFT
        if (isset($_POST['item_tag'])) { //Hide warning when tags not installed
            $story->Settags($_POST['item_tag']);
        }
        $result = $story->store();

        if ($result) {
            if ($approveprivilege && $helper->getConfig('tags')) {
//                $tagHandler0 = \XoopsModules\Tag\Helper::getInstance()->getHandler('Tag'); // xoops_getModuleHandler('tag', 'tag');

                /** @var \XoopsModules\Tag\TagHandler $tagHandler */
                $tagHandler = \XoopsModules\Tag\Helper::getInstance()->getHandler('Tag');

                $tagHandler->updateByItem($_POST['item_tag'], $story->storyid(), $helper->getModule()->getVar('dirname'), 0);
            }
            if (!$editmode) {
                //     Notification
                // TODO: modifier afin qu'en cas de prépublication, la notification ne se fasse pas
                $notificationHandler = xoops_getHandler('notification');
                $tags                = [];
                $tags['STORY_NAME']  = $story->title();
                $tags['STORY_URL']   = XNEWS_MODULE_URL . '/article.php?storyid=' . $story->storyid();
                // If notify checkbox is set, add subscription for approve
                if ($notifypub && $approve) {
                    require_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
                    $notificationHandler->subscribe('story', $story->storyid(), 'approve', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE, $helper->getModule()->getVar('mid'), $story->uid());
                }

                if (1 == $approve) {
                    $notificationHandler->triggerEvent('global', 0, 'new_story', $tags);
                    $notificationHandler->triggerEvent('story', $story->storyid(), 'approve', $tags);
                    // Added by Lankford on 2007/3/23
                    $notificationHandler->triggerEvent('category', $story->topicid(), 'new_story', $tags);
                } else {
                    $tags['WAITINGSTORIES_URL'] = XNEWS_MODULE_URL . '/admin/index.php?op=newarticle';
                    $notificationHandler->triggerEvent('global', 0, 'story_submit', $tags);
                }
            }

            if ($allowupload) {
                //DNPROSSI - Control if writable dir
                if (!is_writable(XNEWS_ATTACHED_FILES_PATH)) {
                    redirect_header(XNEWS_MODULE_URL . '/admin/index.php?op=newarticle', 2, _AD_WARNINGNOTWRITEABLE);
                }
                // Manage upload(s)
                if (isset($_POST['delupload']) && count($_POST['delupload']) > 0) {
                    foreach ($_POST['delupload'] as $onefile) {
                        $sfiles = new Xnews\Files($onefile);
                        $sfiles->delete();
                    }
                }
                if (isset($_POST['xoops_upload_file'])) {
                    $fldname = $_FILES[$_POST['xoops_upload_file'][0]];
                    $fldname = get_magic_quotes_gpc() ? stripslashes($fldname['name']) : $fldname['name'];
                    if (xoops_trim('' != $fldname)) {
                        $sfiles   = new Xnews\Files();
                        $destname = $sfiles->createUploadName(XNEWS_ATTACHED_FILES_PATH, $fldname);
                        /**
                         * You can attach files to your news
                         */
                        $permittedTypes = explode("\n", str_replace("\r", '', $helper->getConfig('mimetypes')));
                        array_walk($permittedTypes, 'trim');
                        $uploader = new \XoopsMediaUploader(XNEWS_ATTACHED_FILES_PATH, $permittedTypes, $helper->getConfig('maxuploadsize'));
                        $uploader->setTargetFileName($destname);
                        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                            if ($uploader->upload()) {
                                $sfiles->setFileRealName($uploader->getMediaName());
                                $sfiles->setStoryid($story->storyid());
                                $sfiles->setMimetype($sfiles->giveMimetype(XNEWS_ATTACHED_FILES_PATH . '/' . $uploader->getMediaName()));
                                $sfiles->setDownloadname($destname);
                                if (!$sfiles->store()) {
                                    echo _AM_XNEWS_UPLOAD_DBERROR_SAVE;
                                }
                                //DNPROSSI - 1.71 - creates attached image maxsize
                                if (false !== strpos($sfiles->getMimetype(), 'image')) {
                                    $fullPictureName = XNEWS_ATTACHED_FILES_PATH . '/' . basename($destname);
                                    $newName         = XNEWS_ATTACHED_FILES_PATH . '/redim_' . basename($destname);
                                    // IN PROGRESS
                                    // IN PROGRESS
                                    // IN PROGRESS
                                    nw_resizePicture($fullPictureName, $newName, $helper->getConfig('maxwidth'), $helper->getConfig('maxheight'));
                                    if (file_exists($newName)) {
                                        @unlink($fullPictureName);
                                        rename($newName, $fullPictureName);
                                    }
                                }
                                //DNPROSSI - 1.71 - creates attached image thumb
                                if (false !== strpos($sfiles->getMimetype(), 'image')) {
                                    $fullPictureName = XNEWS_ATTACHED_FILES_PATH . '/' . basename($destname);
                                    $thumbName       = XNEWS_ATTACHED_FILES_PATH . '/thumb_' . basename($destname);
                                    // IN PROGRESS
                                    // IN PROGRESS
                                    // IN PROGRESS
                                    nw_resizePicture($fullPictureName, $thumbName, $helper->getConfig('thumb_maxwidth'), $helper->getConfig('thumb_maxheight'), true);
                                }
                            } else {
                                echo _AM_XNEWS_UPLOAD_ERROR . ' ' . $uploader->getErrors();
                            }
                        } else {
                            echo $uploader->getErrors();
                        }
                    }
                }
            }
        } else {
            echo _ERRORS;
        }
        $returnside = \Xmf\Request::getInt('returnside', 0, 'POST');
        if (!$returnside) {
            redirect_header(XNEWS_MODULE_URL . '/index.php', 2, _MD_XNEWS_THANKS);
        } else {
            redirect_header(XNEWS_MODULE_URL . '/admin/index.php?op=newarticle', 2, _MD_XNEWS_THANKS);
        }
        break;

    case 'form':
        $xt        = new Xnews\NewsTopic();
        $title     = '';
        $hometext  = '';
        $noname    = 0;
        $nohtml    = 0;
        $nosmiley  = 0;
        $dobr      = 0;
        $notifypub = 1;
        $topicid   = 0;
        if ($approveprivilege) {
            $description  = '';
            $keywords     = '';
            $topicdisplay = 0;
            $topicalign   = 'R';
            $ihome        = 0;
            $bodytext     = '';
            $approve      = 0;
            $autodate     = '';
            $expired      = 0;
            $published    = 0;
        }
        if (1 == $helper->getConfig('autoapprove')) {
            $approve = 1;
        }
        require_once XNEWS_MODULE_PATH . '/include/storyform.inc.php';
        break;
}
require_once XOOPS_ROOT_PATH . '/footer.php';
