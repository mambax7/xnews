<?php
require_once __DIR__ . '/header.php';

require_once XNEWS_MODULE_PATH . '/class/class.newsstory.php';
require_once XNEWS_MODULE_PATH . '/class/class.sfiles.php';
require_once XNEWS_MODULE_PATH . '/class/class.newstopic.php';
require_once XOOPS_ROOT_PATH . '/class/uploader.php';

xoops_loadLanguage('admin', $xnews->getModule()->dirname());

require_once XOOPS_ROOT_PATH . '/header.php';

$storyid = 0;

if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}

$gpermHandler = xoops_getHandler('groupperm');

if (isset($_POST['topic_id'])) {
    $perm_itemid = intval($_POST['topic_id']);
} else {
    $perm_itemid = 0;
}
//If no access
if (!$gpermHandler->checkRight('nw_submit', $perm_itemid, $groups, $xnews->getModule()->getVar('mid'))) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _NOPERM);
}
$op = 'form';

//If approve privileges
$approveprivilege = 0;
if (is_object($xoopsUser) && $gpermHandler->checkRight('nw_approve', $perm_itemid, $groups, $xnews->getModule()->getVar('mid'))) {
    $approveprivilege = 1;
}

if (isset($_POST['preview'])) {
    $op = 'preview';
} elseif (isset($_POST['post'])) {
    $op = 'post';
} elseif (isset($_GET['op']) && isset($_GET['storyid'])) {
    // Verify that the user can edit or delete an article
    if ($_GET['op'] == 'edit' || $_GET['op'] == 'delete') {
        if ($xnews->getConfig('authoredit') == 1) {
            $tmpstory = new nw_NewsStory(intval($_GET['storyid']));
            if (is_object($xoopsUser) && $xoopsUser->getVar('uid') != $tmpstory->uid() && !nw_is_admin_group()) {
                redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _NOPERM);
            }
        } else { // Users can't edit their articles
            if (!nw_is_admin_group()) {
                redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _NOPERM);
            }
        }
    }

    if ($approveprivilege && $_GET['op'] == 'edit') {
        $op      = 'edit';
        $storyid = intval($_GET['storyid']);
    } elseif ($approveprivilege && $_GET['op'] == 'delete') {
        $op      = 'delete';
        $storyid = intval($_GET['storyid']);
    } else {
        if ($xnews->getConfig('authoredit') && is_object($xoopsUser) && isset($_GET['storyid']) && ($_GET['op'] == 'edit' || $_POST['op'] == 'preview' || $_POST['op'] == 'post')) {
            $storyid = 0;
            $storyid = isset($_GET['storyid']) ? intval($_GET['storyid']) : intval($_POST['storyid']);
            if (!empty($storyid)) {
                $tmpstory = new nw_NewsStory($storyid);
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
        $story = new nw_NewsStory($storyid);
        if (!$gpermHandler->checkRight('nw_view', $story->topicid(), $groups, $xnews->getModule()->getVar('mid'))) {
            redirect_header(XNEWS_MODULE_URL . '/index.php', 0, _NOPERM);
        }
        echo "<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
        echo '<h4>' . _AM_NW_EDITARTICLE . '</h4>';
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
        if ($story->published() != 0) {
            $published = $story->published();
        }
        if ($story->expired() != 0) {
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
        $topic_id = intval($_POST['topic_id']);
        $xt       = new nw_NewsTopic($topic_id);
        if (isset($_GET['storyid'])) {
            $storyid = intval($_GET['storyid']);
        } else {
            if (isset($_POST['storyid'])) {
                $storyid = intval($_POST['storyid']);
            } else {
                $storyid = 0;
            }
        }

        if (!empty($storyid)) {
            $story     = new nw_NewsStory($storyid);
            $published = $story->published();
            $expired   = $story->expired();
        } else {
            $story     = new nw_NewsStory();
            $published = isset($_POST['publish_date']) ? $_POST['publish_date'] : 0;
            if (!empty($published) && isset($_POST['autodate']) && intval($_POST['autodate'] == 1)) {
                $published = strtotime($published['date']) + $published['time'];
            } else {
                $published = 0;
            }
            $expired = isset($_POST['expiry_date']) ? $_POST['expiry_date'] : 0;
            if (!empty($expired) && isset($_POST['autoexpdate']) && intval($_POST['autoexpdate'] == 1)) {
                $expired = strtotime($expired['date']) + $expired['time'];
            } else {
                $expired = 0;
            }
        }
        $topicid = $topic_id;
        if (isset($_POST['topicdisplay'])) {
            $topicdisplay = intval($_POST['topicdisplay']);
        } else {
            $topicdisplay = 1;
        }

        $approve    = isset($_POST['approve']) ? intval($_POST['approve']) : 0;
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
            if ($xnews->getConfig('metadata')) {
                $story->Setkeywords($_POST['keywords']);
                $story->Setdescription($_POST['description']);
                $story->setIhome(intval($_POST['ihome']));
            }
        } else {
            $noname = isset($_POST['noname']) ? intval($_POST['noname']) : 0;
        }

        if ($approveprivilege || (is_object($xoopsUser) && $xoopsUser->isAdmin($xnews->getModule()->mid()))) {
            if (isset($_POST['author'])) {
                $story->setUid(intval($_POST['author']));
            }
        }

        $notifypub = isset($_POST['notifypub']) ? intval($_POST['notifypub']) : 0;
        $nosmiley  = isset($_POST['nosmiley']) ? intval($_POST['nosmiley']) : 0;
        if (isset($nosmiley) && ($nosmiley == 0 || $nosmiley == 1)) {
            $story->setNosmiley($nosmiley);
        } else {
            $nosmiley = 0;
        }
        if ($approveprivilege) {
            $nohtml = isset($_POST['nohtml']) ? intval($_POST['nohtml']) : 0;
            $story->setNohtml($nohtml);
            if (!isset($_POST['approve'])) {
                $approve = 0;
            }
        } else {
            $story->setNohtml = 1;
        }
        //DNPROSSI - dobr
        $dobr = isset($_POST['dobr']) ? intval($_POST['dobr']) : 0;
        if (isset($dobr) && ($dobr == 0 || $dobr == 1)) {
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
        $p_hometext  = (($xt->topic_imgurl() != '') && $topicdisplay) ? '<img src="assets/images/topics/' . $xt->topic_imgurl() . '" ' . $topicalign2 . ' alt="">' . $p_hometext : $p_hometext;
        themecenterposts($p_title, $p_hometext);

        // Display post edit form
        $returnside = intval($_POST['returnside']);
        require_once XNEWS_MODULE_PATH . '/include/storyform.inc.php';
        break;

    case 'post':
        $nohtml_db = isset($_POST['nohtml']) ? $_POST['nohtml'] : 1;
        if (is_object($xoopsUser)) {
            $uid = $xoopsUser->getVar('uid');
            if ($approveprivilege) {
                $nohtml_db = empty($_POST['nohtml']) ? 0 : 1;
            }
            if (isset($_POST['author']) && ($approveprivilege || $xoopsUser->isAdmin($xnews->getModule()->mid()))) {
                $uid = intval($_POST['author']);
            }
        } else {
            $uid = 0;
        }

        if (isset($_GET['storyid'])) {
            $storyid = intval($_GET['storyid']);
        } else {
            if (isset($_POST['storyid'])) {
                $storyid = intval($_POST['storyid']);
            } else {
                $storyid = 0;
            }
        }

        if (empty($storyid)) {
            $story    = new nw_NewsStory();
            $editmode = false;
        } else {
            $story    = new nw_NewsStory($storyid);
            $editmode = true;
        }
        $story->setUid($uid);
        $story->setTitle($_POST['title']);
        $story->setHometext($_POST['hometext']);
        $story->setTopicId(intval($_POST['topic_id']));
        $story->setHostname(xoops_getenv('REMOTE_ADDR'));
        $story->setNohtml($nohtml_db);
        $nosmiley = isset($_POST['nosmiley']) ? intval($_POST['nosmiley']) : 0;
        $story->setNosmiley($nosmiley);
        $dobr = isset($_POST['dobr']) ? intval($_POST['dobr']) : 0;
        $story->setDobr($dobr);
        //DNPROSSI 1.71
        $imagerows = isset($_POST['imagerows']) ? intval($_POST['imagerows']) : 1;
        $story->Setimagerows($imagerows);
        $pdfrows = isset($_POST['pdfrows']) ? intval($_POST['pdfrows']) : 1;
        $story->Setpdfrows($pdfrows);
        $notifypub = isset($_POST['notifypub']) ? intval($_POST['notifypub']) : 0;
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
            if ($xnews->getConfig('metadata')) {
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
            $approve = isset($_POST['approve']) ? intval($_POST['approve']) : 0;

            if (!$story->published() && $approve) {
                $story->setPublished(time());
            }
            if (!$story->expired()) {
                $story->setExpired(0);
            }

            if (!$approve) {
                $story->setPublished(0);
            }
        } elseif ($xnews->getConfig('autoapprove') == 1 && !$approveprivilege) {
            if (empty($storyid)) {
                $approve = 1;
            } else {
                $approve = isset($_POST['approve']) ? intval($_POST['approve']) : 0;
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
            $tmpuser       = new xoopsUser($uid);
            $memberHandler = xoops_getHandler('member');
            $memberHandler->updateUserByField($tmpuser, 'posts', $tmpuser->getVar('posts') + 1);
        }

        // Second case, it's not an anonymous, the story is NOT approved and it's NOT a new story (typical when someone is approving a submited story)
        if (is_object($xoopsUser) && $approve && !empty($storyid)) {
            $storytemp = new nw_NewsStory($storyid);
            if (!$storytemp->published() && $storytemp->uid() > 0) { // the article has been submited but not approved
                $tmpuser       = new xoopsUser($storytemp->uid());
                $memberHandler = xoops_getHandler('member');
                $memberHandler->updateUserByField($tmpuser, 'posts', $tmpuser->getVar('posts') + 1);
            }
            unset($storytemp);
        }

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

        if ($allowupload && isset($_POST['deleteimage']) && intval($_POST['deleteimage']) == 1) {
            $currentPicture = $story->picture();
            if (xoops_trim($currentPicture) != '') {
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
                $fldname = (get_magic_quotes_gpc()) ? stripslashes($fldname['name']) : $fldname['name'];
                if (xoops_trim($fldname != '')) {
                    $sfiles         = new nw_sFiles();
                    $destname       = $sfiles->createUploadName(XNEWS_TOPICS_FILES_PATH, $fldname);
                    $permittedTypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
                    $uploader       = new XoopsMediaUploader(XNEWS_TOPICS_FILES_PATH, $permittedTypes, $xnews->getConfig('maxuploadsize'));
                    $uploader->setTargetFileName($destname);
                    if ($uploader->fetchMedia($_POST['xoops_upload_file'][1])) {
                        if ($uploader->upload()) {
                            $fullPictureName = XNEWS_TOPICS_FILES_PATH . '/' . basename($destname);
                            $newName         = XNEWS_TOPICS_FILES_PATH . '/redim_' . basename($destname);
                            nw_resizePicture($fullPictureName, $newName, $xnews->getConfig('maxwidth'), $xnews->getConfig('maxheight'));
                            if (file_exists($newName)) {
                                @unlink($fullPictureName);
                                rename($newName, $fullPictureName);
                            }
                            $story->Setpicture(basename($destname));
                        } else {
                            echo _AM_NW_UPLOAD_ERROR . ' ' . $uploader->getErrors();
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
            if ($approveprivilege && $xnews->getConfig('tags')) {
                $tagHandler = xoops_getModuleHandler('tag', 'tag');
                $tagHandler->updateByItem($_POST['item_tag'], $story->storyid(), $xnews->getModule()->getVar('dirname'), 0);
            }
            if (!$editmode) {
                //     Notification
                // TODO: modifier afin qu'en cas de prépublication, la notification ne se fasse pas
                $notificationHandler = xoops_getHandler('notification');
                $tags                = array();
                $tags['STORY_NAME']  = $story->title();
                $tags['STORY_URL']   = XNEWS_MODULE_URL . '/article.php?storyid=' . $story->storyid();
                // If notify checkbox is set, add subscription for approve
                if ($notifypub && $approve) {
                    require_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
                    $notificationHandler->subscribe('story', $story->storyid(), 'approve', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE, $xnews->getModule()->getVar('mid'), $story->uid());
                }

                if ($approve == 1) {
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
                if (!is_writeable(XNEWS_ATTACHED_FILES_PATH)) {
                    redirect_header(XNEWS_MODULE_URL . '/admin/index.php?op=newarticle', 2, _AD_WARNINGNOTWRITEABLE);
                }
                // Manage upload(s)
                if (isset($_POST['delupload']) && count($_POST['delupload']) > 0) {
                    foreach ($_POST['delupload'] as $onefile) {
                        $sfiles = new nw_sFiles($onefile);
                        $sfiles->delete();
                    }
                }
                if (isset($_POST['xoops_upload_file'])) {
                    $fldname = $_FILES[$_POST['xoops_upload_file'][0]];
                    $fldname = (get_magic_quotes_gpc()) ? stripslashes($fldname['name']) : $fldname['name'];
                    if (xoops_trim($fldname != '')) {
                        $sfiles   = new nw_sFiles();
                        $destname = $sfiles->createUploadName(XNEWS_ATTACHED_FILES_PATH, $fldname);
                        /**
                         * You can attach files to your news
                         */
                        $permittedTypes = explode("\n", str_replace("\r", '', $xnews->getConfig('mimetypes')));
                        array_walk($permittedTypes, 'trim');
                        $uploader = new XoopsMediaUploader(XNEWS_ATTACHED_FILES_PATH, $permittedTypes, $xnews->getConfig('maxuploadsize'));
                        $uploader->setTargetFileName($destname);
                        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                            if ($uploader->upload()) {
                                $sfiles->setFileRealName($uploader->getMediaName());
                                $sfiles->setStoryid($story->storyid());
                                $sfiles->setMimetype($sfiles->giveMimetype(XNEWS_ATTACHED_FILES_PATH . '/' . $uploader->getMediaName()));
                                $sfiles->setDownloadname($destname);
                                if (!$sfiles->store()) {
                                    echo _AM_NW_UPLOAD_DBERROR_SAVE;
                                }
                                //DNPROSSI - 1.71 - creates attached image maxsize
                                if (strstr($sfiles->getMimetype(), 'image')) {
                                    $fullPictureName = XNEWS_ATTACHED_FILES_PATH . '/' . basename($destname);
                                    $newName         = XNEWS_ATTACHED_FILES_PATH . '/redim_' . basename($destname);
                                    // IN PROGRESS
                                    // IN PROGRESS
                                    // IN PROGRESS
                                    nw_resizePicture($fullPictureName, $newName, $xnews->getConfig('maxwidth'), $xnews->getConfig('maxheight'));
                                    if (file_exists($newName)) {
                                        @unlink($fullPictureName);
                                        rename($newName, $fullPictureName);
                                    }
                                }
                                //DNPROSSI - 1.71 - creates attached image thumb
                                if (strstr($sfiles->getMimetype(), 'image')) {
                                    $fullPictureName = XNEWS_ATTACHED_FILES_PATH . '/' . basename($destname);
                                    $thumbName       = XNEWS_ATTACHED_FILES_PATH . '/thumb_' . basename($destname);
                                    // IN PROGRESS
                                    // IN PROGRESS
                                    // IN PROGRESS
                                    nw_resizePicture($fullPictureName, $thumbName, $xnews->getConfig('thumb_maxwidth'), $xnews->getConfig('thumb_maxheight'), true);
                                }
                            } else {
                                echo _AM_NW_UPLOAD_ERROR . ' ' . $uploader->getErrors();
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
        $returnside = isset($_POST['returnside']) ? intval($_POST['returnside']) : 0;
        if (!$returnside) {
            redirect_header(XNEWS_MODULE_URL . '/index.php', 2, _MA_NW_THANKS);
        } else {
            redirect_header(XNEWS_MODULE_URL . '/admin/index.php?op=newarticle', 2, _MA_NW_THANKS);
        }
        break;

    case 'form':
        $xt        = new nw_NewsTopic();
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
        if ($xnews->getConfig('autoapprove') == 1) {
            $approve = 1;
        }
        require_once XNEWS_MODULE_PATH . '/include/storyform.inc.php';
        break;
}
require_once XOOPS_ROOT_PATH . '/footer.php';
