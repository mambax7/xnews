<?php
/**
* Module: xNews Import
* Author: DNPROSSI
* Licence: GNU
*/
	
//DELETE ALL DATA FROM DESTINATION TABLES
$resultDELETE=$xoopsDB->query("DELETE FROM " . $xoopsDB->prefix($to_module_subprefix . 'topics'));
$resultDELETE=$xoopsDB->query("DELETE FROM " . $xoopsDB->prefix($to_module_subprefix . 'stories'));
$resultDELETE=$xoopsDB->query("DELETE FROM " . $xoopsDB->prefix($to_module_subprefix . 'stories_files'));
$resultDELETE=$xoopsDB->query("DELETE FROM " . $xoopsDB->prefix($to_module_subprefix . 'stories_votedata'));
//RESET ALL TABLE AUTOINCREMENT VALUES
$resultALTER=$xoopsDB->query("ALTER TABLE " . $xoopsDB->prefix($to_module_subprefix . 'topics') . " AUTO_INCREMENT = 1");
$resultALTER=$xoopsDB->query("ALTER TABLE " . $xoopsDB->prefix($to_module_subprefix . 'stories') . " AUTO_INCREMENT = 1");
$resultALTER=$xoopsDB->query("ALTER TABLE " . $xoopsDB->prefix($to_module_subprefix . 'stories_files') . " AUTO_INCREMENT = 1");
$resultALTER=$xoopsDB->query("ALTER TABLE " . $xoopsDB->prefix($to_module_subprefix . 'stories_votedata') . " AUTO_INCREMENT = 1");

$newArticleArray = array();
 
while ($arrCat = $xoopsDB->fetchArray($resultCat)) 
{
    $topic = new xni_NewsTopic(0, $to_module_subprefix);
	
	$topic->topic_pid = $arrCat['topic_pid'];
	$topic->topic_title = $arrCat['topic_title'];
	$topic->topic_description = $arrCat['topic_description'];
	$topic->menu = $arrCat['menu'];
	$topic->topic_frontpage = $arrCat['topic_frontpage'];
	$topic->topic_rssurl = $arrCat['topic_rssurl'];
	$topic->topic_color = $arrCat['topic_color'];
	//DNPROSSI - Added for version 1.69
	if ( isset($arrCat['topic_weight'] ) ) { $topic->topic_weight = $arrCat['topic_weigth']; } 
	
	if ($from_module_dirname == 'news') 
	{
		$sourcepath = XOOPS_ROOT_PATH . '/modules/'.$from_module_dirname.'/images/topics/';
		$destinationpath = XOOPS_ROOT_PATH . '/uploads/'.$to_module_dirname.'/topics/';
	}
	else
	{
		$sourcepath = XOOPS_ROOT_PATH . '/uploads/'.$from_module_dirname.'/topics/';
		$destinationpath = XOOPS_ROOT_PATH . '/uploads/'.$to_module_dirname.'/topics/';
	}
		
    // Category image
    if ( ($arrCat['topic_imgurl'] != 'blank.gif') && ($arrCat['topic_imgurl'] != '') ) 
    {
		if ($arrCat['topic_imgurl'] == 'xoops.gif') 
        {
			$topic->topic_imgurl = $arrCat['topic_imgurl'];
		}
		else
		{
			if ( copy($sourcepath . $arrCat['topic_imgurl'], $destinationpath . $arrCat['topic_imgurl']) ) 
			{
				$topic->topic_imgurl = $arrCat['topic_imgurl'];
				echo sprintf(_AM_XNI_IMPORTED_FILE, $topic->topic_imgurl()) . "<br />";
				$cnt_imported_files++;
			}
		}
	}
        
	$idresult = $xoopsDB->query("SHOW TABLE STATUS LIKE '" . $xoopsDB->prefix($to_module_subprefix . 'topics') . "'");
	$row =  $xoopsDB->fetchArray($idresult);
	$auto_increment = $row['Auto_increment'];
               
	//trigger_error(intval($auto_increment -1) , E_USER_WARNING);
	if (!$topic->store($topic)) 
    {
        echo sprintf(_AM_XNI_IMPORT_CATEGORY_ERROR, $arrCat['topic_title']) . "<br/>";
        continue;
    }
	
	// Saving topic permissions
	if ($from_module_dirname == 'news') 
	{
		$groupsIds = $gperm_handler->getGroupIds('news_approve', $arrCat['topic_id'], $news_module_id);
		xni_savePermissions($to_module_dirname, $groupsIds, $topic->topic_id(), $to_module_subprefix . 'approve');
		$groupsIds = $gperm_handler->getGroupIds('news_view', $arrCat['topic_id'], $news_module_id);
		xni_savePermissions($to_module_dirname, $groupsIds, $topic->topic_id(), $to_module_subprefix . 'view');
		$groupsIds = $gperm_handler->getGroupIds('news_submit', $arrCat['topic_id'], $news_module_id);
		xni_savePermissions($to_module_dirname, $groupsIds, $topic->topic_id(), $to_module_subprefix . 'submit');
	    // echo intval($topic->topic_id()) . '<br/>';
	}
	else
	{
		$groupsIds = $gperm_handler->getGroupIds($from_module_subprefix . 'approve', $arrCat['topic_id'], $news_module_id);
		xni_savePermissions($to_module_dirname, $groupsIds, $topic->topic_id(), $to_module_subprefix . 'approve');
		$groupsIds = $gperm_handler->getGroupIds($from_module_subprefix . 'view', $arrCat['topic_id'], $news_module_id);
		xni_savePermissions($to_module_dirname, $groupsIds, $topic->topic_id(), $to_module_subprefix . 'view');
		$groupsIds = $gperm_handler->getGroupIds($from_module_subprefix . 'submit', $arrCat['topic_id'], $news_module_id);
		xni_savePermissions($to_module_dirname, $groupsIds, $topic->topic_id(), $to_module_subprefix . 'submit');
	}		
		
    $cnt_imported_cat++;

    echo sprintf(_AM_XNI_IMPORT_CATEGORY_SUCCESS, $topic->topic_title());

    $sql = "SELECT * FROM " . $xoopsDB->prefix($from_module_subprefix . 'stories') .  " WHERE topicid=" . $arrCat['topic_id'];
    $resultArticles = $xoopsDB->query($sql);
    while ($arrArticle = $xoopsDB->fetchArray($resultArticles)) 
    {            
		// Insert article
		$story =new xni_NewsStory(-1, $to_module_subprefix);
           
		$story->uid = $arrArticle['uid'];
		$story->title = $arrArticle['title'];
		$story->created = $arrArticle['created'];
		$story->published = $arrArticle['published'];
		$story->expired = $arrArticle['expired'];
		$story->hostname = $arrArticle['hostname'];
		$story->nohtml = $arrArticle['nohtml'];
		$story->nosmiley = $arrArticle['nosmiley'];
		$story->hometext = $arrArticle['hometext'];
		$story->bodytext = $arrArticle['bodytext'];
		$story->keywords = $arrArticle['keywords']; //META
		$story->description = $arrArticle['description']; //META
		$story->counter = $arrArticle['counter'];
		$story->topicid = $auto_increment; //$arrArticle['topicid'];
		$story->ihome = $arrArticle['ihome'];
		$story->notifypub = $arrArticle['notifypub'];
		$story->story_type = $arrArticle['story_type'];
		$story->topicdisplay = $arrArticle['topicdisplay'];
		$story->topicalign = $arrArticle['topicalign'];
		$story->comments = $arrArticle['comments'];
		$story->rating = $arrArticle['rating'];
		$story->votes = $arrArticle['votes'];
		$story->picture = $arrArticle['picture'];
		if ( isset($arrArticle['dobr']) ) { $story->dobr = $arrArticle['dobr']; } 
		if ( isset($arrArticle['tags']) ) { $story->tags = $arrArticle['tags']; } 
		
		// Picture
		if ( ($arrArticle['picture'] != '') ) 
		{
			if ( copy($sourcepath . $arrArticle['picture'], $destinationpath . $arrArticle['picture']) ) 
			{
				$story->picture = $arrArticle['picture'];
				echo sprintf(_AM_XNI_IMPORTED_FILE, $story->picture()) . "<br />";
				$cnt_imported_files++;
			}
		}
			
		// Attached files
		if ($from_module_dirname == 'news') 
		{
			$attached_sourcepath = XOOPS_ROOT_PATH . '/uploads/';
			$attached_destinationpath = XOOPS_ROOT_PATH . '/uploads/'.$to_module_dirname.'/attached/';
		}
		else
		{
			$attached_sourcepath = XOOPS_ROOT_PATH . '/uploads/'.$from_module_dirname.'/attached/';
			$attached_destinationpath = XOOPS_ROOT_PATH . '/uploads/'.$to_module_dirname.'/attached/';
		}
			
		$sql = "SELECT * FROM " . $xoopsDB->prefix($from_module_subprefix . 'stories_files') .  " WHERE storyid=" . $arrArticle['storyid'];
		$resultfiles = $xoopsDB->query($sql);
		while ($arrFiles = $xoopsDB->fetchArray($resultfiles)) 
		{            
			$result = $xoopsDB->query("INSERT INTO " . $xoopsDB->prefix($to_module_subprefix . 'stories_files') . 
				" (filerealname, storyid, date, mimetype, downloadname, counter)" .
				" VALUES ('" . $arrFiles['filerealname'] . "', '" . 
								$arrFiles['storyid'] . "', '" . 
								$arrFiles['date'] . "', '" .
								$arrFiles['mimetype'] . "', '" .
								$arrFiles['downloadname'] . "', '" .
								$arrFiles['counter'] . "'); ");	
			if ( copy($attached_sourcepath . $arrFiles['downloadname'], $attached_destinationpath . $arrFiles['downloadname']) ) 
			{
				echo sprintf(_AM_XNI_IMPORTED_FILE, $arrFiles['downloadname']) . "<br />";
				$cnt_imported_files++;
			}
        }	
        	
        // Vote Data
        $sql = "SELECT * FROM " . $xoopsDB->prefix($from_module_subprefix . 'stories_votedata') .  " WHERE storyid=" . $arrArticle['storyid'];
		$resultvotes = $xoopsDB->query($sql);
		while ($arrVotes = $xoopsDB->fetchArray($resultvotes)) 
		{            
			$result = $xoopsDB->query("INSERT INTO " . $xoopsDB->prefix($to_module_subprefix . 'stories_votedata') . 
				" (storyid, ratinguser, rating, ratinghostname, ratingtimestamp)" .
				" VALUES ('" . $arrVotes['storyid'] . "', '" . 
								$arrVotes['ratinguser'] . "', '" . 
								$arrVotes['rating'] . "', '" .
								$arrVotes['ratinghostname'] . "', '" .
								$arrVotes['ratingtimestamp'] . "'); ");	
        }	
        	
        // Save story            
		$storyPublished = $arrArticle['published'] != 0 ? $story->setApproved(1) : $story->setApproved(0);
		if ( !$story->store() ) 
		{
			echo sprintf("  " . _AM_XNI_IMPORT_ARTICLE_ERROR, $arrArticle['title']) . "<br/>";
			continue;
		} 
		else 
		{
			$newArticleArray[$arrArticle['storyid']] = $story->storyid();
			echo sprintf(_AM_XNI_IMPORTED_ARTICLE, $story->title()) . "<br />";
			$cnt_imported_articles++;
		}
	}
}    

// Looping through the comments to link them to the new articles and module
//echo _AM_XNI_IMPORT_COMMENTS . "<br />";
$module_handler =& xoops_gethandler('module');
$moduleObj = $module_handler->getByDirname($to_module_dirname);
$module_id = $moduleObj->getVar('mid');

$comment_handler = xoops_gethandler('comment');
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('com_modid', $news_module_id));
$comments = $comment_handler->getObjects($criteria);
foreach ($comments as $comment) 
{
	$comment->setVar('com_itemid', $newArticleArray[$comment->getVar('com_itemid')]);
	$comment->setVar('com_modid', $module_id);
	$comment->setNew();
	if (!$comment_handler->insert($comment)) 
	{
		echo sprintf(_AM_XNI_IMPORTED_COMMENT_ERROR, $comment->getVar('com_title')) . "<br />";
	} 
	else 
	{
		echo sprintf(_AM_XNI_IMPORTED_COMMENT, $comment->getVar('com_title')) . "<br />";
		$cnt_imported_comments++;
	}
}

echo "<br/><br/>Done.<br/>";
echo sprintf(_AM_XNI_IMPORTED_CATEGORIES, $cnt_imported_cat) . "<br/>";
echo sprintf(_AM_XNI_IMPORTED_ARTICLES, $cnt_imported_articles) . "<br/>";
echo sprintf(_AM_XNI_IMPORTED_FILES, $cnt_imported_files) . "<br/>";
echo sprintf(_AM_XNI_IMPORTED_COMMENTS, $cnt_imported_comments) . "<br/>";
echo "<br/><a href='". XOOPS_URL . "/modules/" . $to_module_dirname ."/index.php'>" . _AM_XNI_IMPORT_GOTOMODULE . "</a><br/>";

?>
