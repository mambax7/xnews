<?php
// $Id: submit.php 8207 2011-11-07 04:18:27Z beckmi $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
if (!defined('XOOPS_ROOT_PATH')) {
	include_once 'header.php';
}
include_once NW_MODULE_PATH . '/class/class.newsstory.php';
include_once NW_MODULE_PATH . '/class/class.sfiles.php';
include_once NW_MODULE_PATH . '/class/class.newstopic.php';
include_once XOOPS_ROOT_PATH.'/class/uploader.php';
include_once XOOPS_ROOT_PATH.'/header.php';
include_once NW_MODULE_PATH . '/include/functions.php';
if (file_exists(NW_MODULE_PATH . '/language/'.$xoopsConfig['language'].'/admin.php')) {
    include_once NW_MODULE_PATH . '/language/'.$xoopsConfig['language'].'/admin.php';
} else {
    include_once NW_MODULE_PATH . '/language/english/admin.php';
}
$myts =& MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');
$storyid=0;

if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
	$groups = XOOPS_GROUP_ANONYMOUS;
}

$gperm_handler =& xoops_gethandler('groupperm');

if (isset($_POST['topic_id'])) {
    $perm_itemid = intval($_POST['topic_id']);
} else {
    $perm_itemid = 0;
}
//If no access
if (!$gperm_handler->checkRight('nw_submit', $perm_itemid, $groups, $module_id)) {
    redirect_header(NW_MODULE_URL . '/index.php', 3, _NOPERM);
    exit();
}
$op = 'form';

//If approve privileges
$approveprivilege = 0;
if (is_object($xoopsUser) && $gperm_handler->checkRight('nw_approve', $perm_itemid, $groups, $module_id)) {
    $approveprivilege = 1;
}

if (isset($_POST['preview'])) {
	$op = 'preview';
} elseif (isset($_POST['post'])) {
	$op = 'post';
}
elseif ( isset($_GET['op']) && isset($_GET['storyid'])) {
	// Verify that the user can edit or delete an article
	if( $_GET['op'] == 'edit' || $_GET['op'] == 'delete' ) {
		if($xoopsModuleConfig['authoredit']==1) {
			$tmpstory = new nw_NewsStory(intval($_GET['storyid']));
			if(is_object($xoopsUser) && $xoopsUser->getVar('uid')!=$tmpstory->uid() && !nw_is_admin_group()) {
			    redirect_header(NW_MODULE_URL . '/index.php', 3, _NOPERM);
	    		exit();
			}
		} else {	// Users can't edit their articles
			if(!nw_is_admin_group()) {
		    	redirect_header(NW_MODULE_URL . '/index.php', 3, _NOPERM);
	    		exit();
	    	}
		}
	}

    if ($approveprivilege && $_GET['op'] == 'edit') {
        $op = 'edit';
        $storyid = intval($_GET['storyid']);
    }
    elseif ($approveprivilege && $_GET['op'] == 'delete') {
        $op = 'delete';
        $storyid = intval($_GET['storyid']);
    }
    else {
    	if(nw_getmoduleoption('authoredit', NW_MODULE_DIR_NAME) && is_object($xoopsUser) && isset($_GET['storyid']) && ($_GET['op']=='edit' || $_POST['op']=='preview' || $_POST['op']=='post')) {
    		$storyid=0;
    		$storyid = isset($_GET['storyid']) ? intval($_GET['storyid']) : intval($_POST['storyid']);
    		if(!empty($storyid)) {
    			$tmpstory = new nw_NewsStory($storyid);
    			if($tmpstory->uid()==$xoopsUser->getVar('uid')) {
	    			$op= isset($_GET['op']) ? $_GET['op'] : $_POST['post'];
    				unset($tmpstory);
    				$approveprivilege=1;
    			} else {
	    			unset($tmpstory);
	    			if(!nw_is_admin_group()) {
    					redirect_header(NW_MODULE_URL . '/index.php', 3, _NOPERM);
    					exit();
    				} else {
    					$approveprivilege=1;
    				}
    			}
    		}
    	} else {
    		if(!nw_is_admin_group()) {
    			unset($tmpstory);
        		redirect_header(NW_MODULE_URL . '/index.php', 3, _NOPERM);
        		exit();
        	} else {
        		$approveprivilege=1;
        	}
        }
    }
}

switch ($op) {
    case 'edit':
        if (!$approveprivilege) {
            redirect_header(NW_MODULE_URL . '/index.php', 0, _NOPERM);
            exit();
            break;
        }
        //if($storyid==0 && isset($_POST['storyid'])) {
     	//	$storyid=intval($_POST['storyid']);
       	//}
        $story = new nw_NewsStory($storyid);
        if (!$gperm_handler->checkRight('nw_view', $story->topicid(), $groups, $module_id)) {
            redirect_header(NW_MODULE_URL . '/index.php', 0, _NOPERM);
            exit();
        }
        echo"<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
        echo '<h4>' . _AM_NW_EDITARTICLE . '</h4>';
        $title = $story->title('Edit');
        $hometext = $story->hometext('Edit');
        $bodytext = $story->bodytext('Edit');
        $nohtml = $story->nohtml();
        $nosmiley = $story->nosmiley();
        $description = $story->description();
        $keywords = $story->keywords();
        $ihome = $story->ihome();
        $newsauthor=$story->uid();
        $topicid = $story->topicid();
        $notifypub = $story->notifypub();
        $picture = $story->picture();
        //DNPROSSI - dobr
        $dobr = $story->dobr();
        $approve = 0;
        $published = $story->published();
        if (isset($published) && $published > 0) {
            $approve = 1;
        }
        if ( $story -> published() != 0) {
            $published = $story->published();
        }
		if ( $story -> expired() != 0) {
            $expired = $story->expired();
        } else {
            $expired = 0;
        }
		$type = $story -> type();
        $topicdisplay = $story -> topicdisplay();
        $topicalign = $story -> topicalign( false );
        if(!nw_is_admin_group()) {
        	include_once NW_MODULE_PATH . '/include/storyform.inc.php';
        } else {
        	include_once NW_MODULE_PATH . '/include/storyform.original.php';
        }
        echo'</td></tr></table>';
        break;

	case 'preview':
		$topic_id = intval($_POST['topic_id']);
		$xt = new nw_NewsTopic($topic_id);
		if(isset($_GET['storyid'])) {
			$storyid=intval($_GET['storyid']);
		} else {
			if(isset($_POST['storyid'])) {
				$storyid=intval($_POST['storyid']);
			} else {
				$storyid=0;
			}
		}

		if (!empty($storyid)) {
		    $story = new nw_NewsStory($storyid);
	    	$published = $story->published();
	    	$expired = $story->expired();
		} else {
		    $story = new nw_NewsStory();
	    	$published = isset($_POST['publish_date']) ? $_POST['publish_date'] : 0;
	    	if(!empty($published) && isset($_POST['autodate']) && intval($_POST['autodate'] == 1)) {
		    	$published = strtotime($published['date']) + $published['time'];
	    	} else {
				$published = 0;
	    	}
	    	$expired = isset($_POST['expiry_date']) ? $_POST['expiry_date'] : 0;
	    	if(!empty($expired) && isset($_POST['autoexpdate']) && intval($_POST['autoexpdate'] == 1)) {
		    	$expired = strtotime($expired['date']) + $expired['time'];
	    	} else {
				$expired = 0;
			}
		}
		$topicid = $topic_id;
		if(isset($_POST['topicdisplay'])) {
			$topicdisplay = intval($_POST['topicdisplay']);
		} else {
			$topicdisplay = 1;
		}

		$approve = isset($_POST['approve']) ? intval($_POST['approve']) : 0;
		$topicalign = 'R';
		if(isset($_POST['topicalign'])) {
			$topicalign = $_POST['topicalign'];
		}
		$story->setTitle($_POST['title']);
		$story->setHometext($_POST['hometext']);
		if ($approveprivilege) {
	    	$story->setTopicdisplay($topicdisplay);
	    	$story->setTopicalign($topicalign);
	    	$story->setBodytext($_POST['bodytext']);
			if(nw_getmoduleoption('metadata', NW_MODULE_DIR_NAME)) {
	        	$story->Setkeywords($_POST['keywords']);
        		$story->Setdescription($_POST['description']);
        		$story->setIhome(intval($_POST['ihome']));
        	}
		} else {
		    $noname = isset($_POST['noname']) ? intval($_POST['noname']) : 0;
		}

		if ($approveprivilege || (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid()))) {
			if(isset($_POST['author'])) {
				$story->setUid(intval($_POST['author']));
			}
		}

		$notifypub = isset($_POST['notifypub']) ? intval($_POST['notifypub']) : 0;
		$nosmiley = isset($_POST['nosmiley']) ? intval($_POST['nosmiley']) : 0;
		if (isset($nosmiley) && ($nosmiley == 0 || $nosmiley == 1)) {
		    $story -> setNosmiley($nosmiley);
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
		
		$title = $story->title('InForm');
	  	$hometext = $story->hometext('InForm');
	  	if ($approveprivilege) {
  	    	$bodytext = $story->bodytext('InForm');
  	    	$ihome = $story -> ihome();
  	    	$description = $story->description('E');
  	    	$keywords = $story->keywords('E');
  		}

		//Display post preview
		$newsauthor=$story->uid();
		$p_title = $story->title('Preview');
		$p_hometext = $story->hometext('Preview');
		if ($approveprivilege) {
		    $p_bodytext = $story->bodytext('Preview');
	    	$p_hometext .= '<br /><br />'.$p_bodytext;
		}
		$topicalign2 = isset($story->topicalign) ? 'align="'.$story->topicalign().'"' : '';
		$p_hometext = (($xt->topic_imgurl() != '') && $topicdisplay) ? '<img src="images/topics/'.$xt->topic_imgurl().'" '.$topicalign2.' alt="" />'.$p_hometext : $p_hometext;
		themecenterposts($p_title, $p_hometext);

		//Display post edit form
		$returnside=intval($_POST['returnside']);
		include_once NW_MODULE_PATH . '/include/storyform.inc.php';
		break;

	case 'post':
		$nohtml_db = isset($_POST['nohtml']) ? $_POST['nohtml'] : 1;
		if (is_object($xoopsUser) ) {
			$uid = $xoopsUser->getVar('uid');
			if ($approveprivilege) {
			    $nohtml_db = empty($_POST['nohtml']) ? 0 : 1;
			}
			if (isset($_POST['author']) && ($approveprivilege || $xoopsUser->isAdmin($xoopsModule->mid())) ) {
				$uid=intval($_POST['author']);
			}
		} else {
		    $uid = 0;
		}

		if(isset($_GET['storyid'])) {
			$storyid=intval($_GET['storyid']);
		} else {
			if(isset($_POST['storyid'])) {
				$storyid=intval($_POST['storyid']);
			} else {
				$storyid=0;
			}
		}

		if (empty($storyid)) {
		    $story = new nw_NewsStory();
		    $editmode = false;
		} else {
	    	$story = new nw_NewsStory($storyid);
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
		$notifypub = isset($_POST['notifypub']) ? intval($_POST['notifypub']) : 0;
		$story->setNotifyPub($notifypub);
		$story->setType($_POST['type']);

		if (!empty( $_POST['autodate'] ) && $approveprivilege) {
		    $publish_date=$_POST['publish_date'];
	    	$pubdate = strtotime($publish_date['date']) + $publish_date['time'];
	    	//$offset = $xoopsUser -> timezone() - $xoopsConfig['server_TZ'];
	    	//$pubdate = $pubdate - ( $offset * 3600 );
	    	$story -> setPublished( $pubdate );
		}
		if (!empty( $_POST['autoexpdate'] ) && $approveprivilege) {
			$expiry_date=$_POST['expiry_date'];
	    	$expiry_date = strtotime($expiry_date['date']) + $expiry_date['time'];
	    	$offset = $xoopsUser -> timezone() - $xoopsConfig['server_TZ'];
	    	$expiry_date = $expiry_date - ( $offset * 3600 );
	    	$story -> setExpired( $expiry_date );
		} else {
		    $story -> setExpired( 0 );
		}

		if ($approveprivilege) {
			if(nw_getmoduleoption('metadata', NW_MODULE_DIR_NAME)) {
				$story->Setdescription($_POST['description']);
        		$story->Setkeywords($_POST['keywords']);
        	}
	    	$story->setTopicdisplay($_POST['topicdisplay']);	// Display Topic Image ? (Yes or No)
	    	$story->setTopicalign($_POST['topicalign']);		// Topic Align, 'Right' or 'Left'
   			$story->setIhome($_POST['ihome']);				// Publish in home ? (Yes or No)
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

	    	if(!$approve) {
		    	$story->setPublished(0);
	    	}
		} elseif ( $xoopsModuleConfig['autoapprove'] == 1 && !$approveprivilege) {
	    	if (empty($storyid)) {
				$approve = 1;
			} else {
				$approve = isset($_POST['approve']) ? intval($_POST['approve']) : 0;
			}
			if($approve) {
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

		if($approve) {
			nw_updateCache();
		}

		// Increment author's posts count (only if it's a new article)
		// First case, it's not an anonyous, the story is approved and it's a new story
		if($uid && $approve && empty($storyid)) {
			$tmpuser = new xoopsUser($uid);
        	$member_handler =& xoops_gethandler('member');
        	$member_handler->updateUserByField($tmpuser, 'posts', $tmpuser->getVar('posts') + 1);
		}

		// Second case, it's not an anonymous, the story is NOT approved and it's NOT a new story (typical when someone is approving a submited story)
		if(is_object($xoopsUser) && $approve && !empty($storyid)) {
			$storytemp = new nw_NewsStory( $storyid );
			if(!$storytemp->published() && $storytemp->uid()>0) {	// the article has been submited but not approved
				$tmpuser=new xoopsUser($storytemp->uid());
        		$member_handler =& xoops_gethandler('member');
        		$member_handler->updateUserByField($tmpuser, 'posts', $tmpuser->getVar('posts') + 1);
        	}
        	unset($storytemp);
		}

		$allowupload = false;
		switch ($xoopsModuleConfig['uploadgroups']) {
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

		if($allowupload && isset($_POST['deleteimage']) && intval($_POST['deleteimage']) == 1) {
			$currentPicture = $story->picture();
			if(xoops_trim($currentPicture) != '') {
				$currentPicture = NW_TOPICS_FILES_PATH . '/'.xoops_trim($story->picture());
				if(is_file($currentPicture) && file_exists($currentPicture)) {
					if(!unlink($currentPicture)) {
						trigger_error("Error, impossible to delete the picture attached to this article");
					}
				}
			}
			$story->Setpicture('');
		}

		if($allowupload) {	// L'image
			if(isset($_POST['xoops_upload_file'])) {
				$fldname = $_FILES[$_POST['xoops_upload_file'][1]];
				$fldname = (get_magic_quotes_gpc()) ? stripslashes($fldname['name']) : $fldname['name'];
				if(xoops_trim($fldname != '')) {
					$sfiles = new nw_sFiles();
					$destname = $sfiles->createUploadName(NW_TOPICS_FILES_PATH, $fldname);
					$permittedtypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
					$uploader = new XoopsMediaUploader( NW_TOPICS_FILES_PATH, $permittedtypes, $xoopsModuleConfig['maxuploadsize']);
					$uploader->setTargetFileName($destname);
					if ($uploader->fetchMedia($_POST['xoops_upload_file'][1])) {
						if ($uploader->upload()) {
							$fullPictureName = NW_TOPICS_FILES_PATH . '/'.basename($destname);
							$newName = NW_TOPICS_FILES_PATH . '/redim_'.basename($destname);
							nw_resizePicture($fullPictureName, $newName, $xoopsModuleConfig['maxwidth'], $xoopsModuleConfig['maxheight']);
							if(file_exists($newName)) {
								@unlink($fullPictureName);
								rename($newName, $fullPictureName);
							}
							$story->Setpicture(basename($destname));
						} else {
							echo _AM_NW_UPLOAD_ERROR. ' ' . $uploader->getErrors();
						}
					} else {
						echo $uploader->getErrors();
					}
				}
			}
		}
		$destname = '';
		//WISHCRAFT
		$story->Settags($_POST['item_tag']);
		$result = $story->store();
		if ($result) {
			if ($approveprivilege && nw_getmoduleoption('tags', NW_MODULE_DIR_NAME)) {
				$tag_handler = xoops_getmodulehandler('tag', 'tag');
				$tag_handler->updateByItem($_POST['item_tag'], $story->storyid(), $xoopsModule->getVar('dirname'), 0);
	    	}

			if(!$editmode) {
				// 	Notification
				// TODO: modifier afin qu'en cas de prépublication, la notification ne se fasse pas
				$notification_handler =& xoops_gethandler('notification');
				$tags = array();
				$tags['STORY_NAME'] = $story->title();
				$tags['STORY_URL'] = NW_MODULE_URL . '/article.php?storyid=' . $story->storyid();
				// If notify checkbox is set, add subscription for approve
				if ($notifypub && $approve) {
					include_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
					$notification_handler->subscribe('story', $story->storyid(), 'approve', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE,$xoopsModule->getVar('mid'),$story->uid());
				}

				if ($approve == 1) {
					$notification_handler->triggerEvent('global', 0, 'new_story', $tags);
					$notification_handler->triggerEvent('story', $story->storyid(), 'approve', $tags);
					// Added by Lankford on 2007/3/23
					$notification_handler->triggerEvent('category', $story->topicid(), 'new_story', $tags);
				} else {
					$tags['WAITINGSTORIES_URL'] = NW_MODULE_URL . '/admin/index.php?op=newarticle';
					$notification_handler->triggerEvent('global', 0, 'story_submit', $tags);
				}
			}

			if($allowupload) {
				// Manage upload(s)
				if(isset($_POST['delupload']) && count($_POST['delupload']) > 0) {
					foreach ($_POST['delupload'] as $onefile) {
						$sfiles = new nw_sFiles($onefile);
						$sfiles->delete();
					}
				}

				if(isset($_POST['xoops_upload_file'])) {
					$fldname = $_FILES[$_POST['xoops_upload_file'][0]];
					$fldname = (get_magic_quotes_gpc()) ? stripslashes($fldname['name']) : $fldname['name'];
					if(xoops_trim($fldname!='')) {
						$sfiles = new nw_sFiles();
						$destname=$sfiles->createUploadName(NW_ATTACHED_FILES_PATH,$fldname);
						/**
						 * You can attach files to your news
						 */
						$permittedtypes = explode("\n",str_replace("\r",'',nw_getmoduleoption('mimetypes', NW_MODULE_DIR_NAME)));
						array_walk($permittedtypes, 'trim');
						$uploader = new XoopsMediaUploader( NW_ATTACHED_FILES_PATH, $permittedtypes, $xoopsModuleConfig['maxuploadsize']);
						$uploader->setTargetFileName($destname);
						if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
							if ($uploader->upload()) {
								$sfiles->setFileRealName($uploader->getMediaName());
								$sfiles->setStoryid($story->storyid());
								$sfiles->setMimetype($sfiles->giveMimetype(NW_ATTACHED_FILES_PATH.'/'.$uploader->getMediaName()));
								$sfiles->setDownloadname($destname);
								if(!$sfiles->store()) {
									echo _AM_NW_UPLOAD_DBERROR_SAVE;
								}
							} else {
								echo _AM_NW_UPLOAD_ERROR. ' ' . $uploader->getErrors();
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
		if(!$returnside) {
			redirect_header(NW_MODULE_URL . '/index.php',2,_MA_NW_THANKS);
			exit();
		} else {
			redirect_header(NW_MODULE_URL . '/admin/index.php?op=newarticle',2,_MA_NW_THANKS);
			exit();
		}
		break;

	case 'form':
		$xt = new nw_NewsTopic();
		$title = '';
		$hometext = '';
		$noname = 0;
		$nohtml = 0;
		$nosmiley = 0;
		$dobr = 0;
		$notifypub = 1;
		$topicid = 0;
		if ($approveprivilege) {
			$description='';
			$keywords='';
	    	$topicdisplay = 0;
	    	$topicalign = 'R';
	    	$ihome = 0;
	    	$bodytext = '';
	    	$approve = 0;
	    	$autodate = '';
	    	$expired = 0;
	    	$published = 0;
		}
		if($xoopsModuleConfig['autoapprove'] == 1) {
			$approve=1;
		}
		include_once NW_MODULE_PATH . '/include/storyform.inc.php';
		break;
}
include_once XOOPS_ROOT_PATH.'/footer.php';
?>
