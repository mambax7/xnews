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
 * @copyright    XOOPS Project http://sourceforge.net/projects/xoops/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package      xNews
 * @since        1.6.0
 * @author       XOOPS Development Team, DNPROSSI
 * @version      $Id $
 */

require_once __DIR__ . '/admin_header.php';
require_once __DIR__ . '/../../../include/cp_header.php';
require_once XNI_MODULE_PATH . '/include/functions.php';
require_once XNI_MODULE_PATH . '/admin/functions.php';

//require_once XNI_MODULE_PATH . '/class/NewsTopic.php';
//require_once XNI_MODULE_PATH . '/class/NewsStory.php';

$myts = \MyTextSanitizer::getInstance();

$importFromModuleName = isset($_POST['importfrom']);
$importToCloneID      = @$_POST['importto'];
trigger_error($importToModuleDirName, E_USER_WARNING);
trigger_error($importToCloneID, E_USER_WARNING);
$scriptname = 'import_news.php';

if ('go' === $op) {
    xoops_cp_header();

    adminMenu(-1, _AM_XNI_IMPORT);

    $moduleHandler  = xoops_getHandler('module');
    $moduleObj      = $moduleHandler->getByDirname('news');
    $news_module_id = $moduleObj->getVar('mid');

    $gpermHandler = xoops_getHandler('groupperm');

    $cnt_imported_cat      = 0;
    $cnt_imported_articles = 0;

    $parentId = $_POST['parent_category'];

    $resultCat = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('topics'));

    $newCatArray     = [];
    $newArticleArray = [];
    $oldToNew        = [];
    while ($arrCat = $xoopsDB->fetchArray($resultCat)) {

        /*$newCat = array();
        $newCat['oldid'] = $arrCat['topic_id'];
        $newCat['oldpid'] = $arrCat['topic_pid'];

        $topic = new Xnews\NewsTopic;

        $result = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix(XNI_SUBPREFIX . '_topics') . " WHERE topic_title = '" . $arrCat['topic_title'] . "'");
        $title_arr = $xoopsDB->fetchArray($result);
        if ($title_arr['topic_title'] == $arrCat['topic_title']) {
            $topic->topic_title = $arrCat['topic_title'] . '-new';
        } else {
            $topic->topic_title = $arrCat['topic_title'];
        }

        $topic->topic_pid = $arrCat['topic_pid'];
        //$topic->topic_title = $arrCat['topic_title'];
        $topic->topic_description = $arrCat['topic_description'];
        $topic->menu = $arrCat['menu'];
        $topic->topic_frontpage = $arrCat['topic_frontpage'];
        $topic->topic_rssurl = $arrCat['topic_rssurl'];
        $topic->topic_color = $arrCat['topic_color'];

        // Category image
        if ( ($arrCat['topic_imgurl'] != 'blank.gif') && ($arrCat['topic_imgurl'] != '') ) {
            if ( copy(XOOPS_ROOT_PATH . "/modules/news/images/topics/" . $arrCat['topic_imgurl'], XNI_TOPICS_FILES_PATH . "/" . $arrCat['topic_imgurl']) ) {
                $topic->topic_imgurl = ($arrCat['topic_imgurl']);
            }
        }

        $idresult = $xoopsDB->query("SHOW TABLE STATUS LIKE '" . $xoopsDB->prefix(XNI_SUBPREFIX . '_topics') . "'");
        $row =  $xoopsDB->fetchArray($idresult);
        $auto_increment = $row['Auto_increment'];

        //trigger_error((int)($auto_increment -1) , E_USER_WARNING);
        if (!$topic->store($topic)) {
            echo sprintf(_AM_XNI_IMPORT_CATEGORY_ERROR, $arrCat['topic_title']) . "<br>";
            continue;
        }

        $newCat['newid'] = $auto_increment;
        ++$cnt_imported_cat;

        echo sprintf("<br\>" . _AM_XNI_IMPORT_CATEGORY_SUCCESS, $topic->topic_title());

        $sql = "SELECT * FROM " . $xoopsDB->prefix('stories') .  " WHERE topicid=" . $arrCat['topic_id'];
        $resultArticles = $xoopsDB->query($sql);
        while ($arrArticle = $xoopsDB->fetchArray($resultArticles)) {
            // insert article
            $story =new NewsStory;

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

            // Picture
            if ( ($arrArticle['picture'] != '') ) {
                if ( copy(XOOPS_ROOT_PATH . "/uploads/" . $arrArticle['picture'], XNI_TOPICS_FILES_PATH . "/" . $arrArticle['picture']) ) {
                    $story->picture = ($arrArticle['picture']);
                }
            }

            $storyPublished = $arrArticle['published'] != 0 ? $story->setApproved(1) : $story->setApproved(0);
            if ( !$story->store() ) {
                echo sprintf("  " . _AM_XNI_IMPORT_ARTICLE_ERROR, $arrArticle['title']) . "<br>";
                continue;
            } else {
                $newArticleArray[$arrArticle['storyid']] = $story->storyid();
                echo "<br>" . sprintf(_AM_XNI_IMPORTED_ARTICLE, $story->title());
                ++$cnt_imported_articles;
            }

        // Saving category permissions
        //$groupsIds = $gpermHandler->getGroupIds('news_view', $arrCat['topic_id'], $news_module_id);
        //nw_saveCategory_Permissions($groupsIds, $categoryObj->categoryid(), 'category_read');
        //$groupsIds = $gpermHandler->getGroupIds('news_submit', $arrCat['topic_id'], $news_module_id);
        //nw_saveCategory_Permissions($groupsIds, $categoryObj->categoryid(), 'item_submit');

        // Saving items permissions
        //nw_overrideItemsPermissions($groupsIds, $categoryObj->categoryid());

        $newCatArray[$newCat['oldid']] = $newCat;
        unset($newCat);
    }

    // Looping through cat to change the parentid to the new parentid
    /*foreach ($newCatArray as $oldid => $newCat) {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('topic_id', $newCat['newid']));
        $oldpid = $newCat['oldpid'];
        if ($oldpid == 0) {
            $newpid = $parentId;
        } else {
            $newpid = $newCatArray[$oldpid]['newid'];
        }
        $set_clause = is_numeric($newpid) ? "topic_id = " . $newpid : "topic_id = '" . $newpid ."'";
        $sql = 'UPDATE ' . $xoopsDB->prefix(XNI_SUBPREFIX . '_topics') . ' SET ' . $set_clause;
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' '.$criteria->renderWhere();
        }
        $result = $xoopsDB->query($sql);
        unset($criteria);
    }*/

        /*
            // Looping through the comments to link them to the new articles and module
            echo _AM_XNI_IMPORT_COMMENTS . "<br>";

            $publisher_module_id = $publisher->getModule()->mid();

            $commentHandler = xoops_getHandler('comment');
            $criteria = new \CriteriaCompo();
            $criteria->add(new \Criteria('com_modid', $news_module_id));
            $comments = $commentHandler->getObjects($criteria);
            foreach ($comments as $comment) {
                $comment->setVar('com_itemid', $newArticleArray[$comment->getVar('com_itemid')]);
                $comment->setVar('com_modid', $publisher_module_id);
                $comment->setNew();
                if (!$commentHandler->insert($comment)) {
                    echo "&nbsp;&nbsp;"  . sprintf(_AM_XNI_IMPORTED_COMMENT_ERROR, $comment->getVar('com_title')) . "<br>";
                } else {
                    echo "&nbsp;&nbsp;"  . sprintf(_AM_XNI_IMPORTED_COMMENT, $comment->getVar('com_title')) . "<br>";
                }
        */
        echo $arrCat['topic_title'];
    }

    echo '<br><br>Done.<br>';
    echo sprintf(_AM_XNI_IMPORTED_CATEGORIES, $cnt_imported_cat) . '<br>';
    echo sprintf(_AM_XNI_IMPORTED_ARTICLES, $cnt_imported_articles) . '<br>';
    echo "<br><a href='index.php'>" . _AM_XNI_IMPORT_GOTOMODULE . '</a><br>';

    xoops_cp_footer();
}
