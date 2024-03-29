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

use Xmf\Request;
use XoopsModules\Xnews;
use XoopsModules\Xnews\Constants;

$currentFile = basename(__FILE__);
require_once __DIR__ . '/admin_header.php';

//require_once XNEWS_MODULE_PATH . '/class/deprecate/xnewstopic.php';
require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';

//require_once XNEWS_MODULE_PATH . '/class/NewsStory.php';
//require_once XNEWS_MODULE_PATH . '/class/NewsTopic.php';
//require_once XNEWS_MODULE_PATH . '/class/Files.php';
//require_once XNEWS_MODULE_PATH . '/class/blacklist.php';
//require_once XNEWS_MODULE_PATH . '/class/registryfile.php';

require_once XOOPS_ROOT_PATH . '/class/uploader.php';
xoops_load('xoopspagenav');
require_once XOOPS_ROOT_PATH . '/class/tree.php';

$myts        = \MyTextSanitizer::getInstance();
$topicscount = 0;

$storiesTableName = $GLOBALS[Constants::XOOPSDB]->prefix('nw_stories');
if (!nw_FieldExists('picture', $storiesTableName)) {
    nw_AddField('`picture` VARCHAR( 50 ) NOT NULL', $storiesTableName);
}

/**
 * Show new submissions
 *
 * This list can be view in the module's admin when you click on the tab named "Post/Edit News"
 * Submissions are news that was submit by users but who are not approved, so you need to edit
 * them to approve them.
 * Actually you can see the the story's title, the topic, the posted date, the author and a
 * link to delete the story. If you click on the story's title, you will be able to edit the news.
 * The table contains the last x new submissions.
 * The system's block called "Waiting Contents" is listing the number of those news.
 */
function newSubmissions()
{
    /** @var Xnews\Helper $helper */
    $helper           = Xnews\Helper::getInstance();
    $newsStoryHandler = new Xnews\NewsStory();

    $start       = \Xmf\Request::getInt('startnew', 0, 'GET');
    $newsubcount = $newsStoryHandler->getAllStoriesCount(3, false);
    $storyarray  = $newsStoryHandler->getAllSubmitted($helper->getConfig('storycountadmin'), true, $helper->getConfig('restrictindex'), $start);
    if (count($storyarray) > 0) {
        $pagenav = new \XoopsPageNav($newsubcount, $helper->getConfig('storycountadmin'), $start, 'startnew', 'op=newarticle');
        xnews_collapsableBar('newsub', 'topnewsubicon');
        echo "<img onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='topnewsubicon' name='topnewsubicon' src='" . XNEWS_MODULE_URL . "/assets/images/close12.gif' alt=''></a>&nbsp;" . _AM_XNEWS_NEWSUB . '</h4>';
        echo "<div id='newsub'>";
        echo '<br>';
        echo "<div style='text-align: center;'><table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr class='bg3'><td align='center'>"
             . _AM_XNEWS_TITLE
             . "</td><td align='center'>"
             . _AM_XNEWS_TOPIC
             . "</td><td align='center'>"
             . _AM_XNEWS_POSTED
             . "</td><td align='center'>"
             . _AM_XNEWS_POSTER
             . "</td><td align='center'>"
             . _AM_XNEWS_ACTION
             . "</td></tr>\n";
        $class = '';
        foreach ($storyarray as $newstory) {
            $class = ('even' === $class) ? 'odd' : 'even';
            echo "<tr class='" . $class . "'><td align='left'>\n";
            $title = $newstory->title();
            if (!isset($title) || ('' == $title)) {
                echo "<a href='" . XNEWS_MODULE_URL . '/admin/index.php?op=edit&amp;returnside=1&amp;storyid=' . $newstory->storyid() . "'>" . _AD_NOSUBJECT . "</a>\n";
            } else {
                echo "&nbsp;<a href='" . XNEWS_MODULE_URL . '/submit.php?returnside=1&amp;op=edit&amp;storyid=' . $newstory->storyid() . "'>" . $title . "</a>\n";
            }
            echo '</td><td>'
                 . $newstory->topic_title()
                 . "</td><td align='center' class='news'>"
                 . formatTimestamp($newstory->created(), $helper->getConfig('dateformat'))
                 . "</td><td align='center'><a href='"
                 . XOOPS_URL
                 . '/userinfo.php?uid='
                 . $newstory->uid()
                 . "'>"
                 . $newstory->uname()
                 . "</a></td><td align='right'><a href='"
                 . XNEWS_MODULE_URL
                 . '/admin/index.php?op=delete&amp;storyid='
                 . $newstory->storyid()
                 . "'>"
                 . _AM_XNEWS_DELETE
                 . "</a></td></tr>\n";
        }
        echo '</table></div>';
        echo "<div align='right'>" . $pagenav->renderNav() . '</div><br>';
        echo '<br></div><br>';
    }
}

/**
 * Shows all automated stories
 *
 * Automated stories are stories that have a publication's date greater than "now"
 * This list can be view in the module's admin when you click on the tab named "Post/Edit News"
 * Actually you can see the story's ID, its title, the topic, the author, the
 * programmed date and time, the expiration's date  and two links. The first link is
 * used to edit the story while the second is used to remove the story.
 * The list only contains the last (x) automated news
 */
function autoStories()
{
    /** @var Xnews\Helper $helper */
    $helper           = Xnews\Helper::getInstance();
    $newsStoryHandler = new Xnews\NewsStory();

    $start        = \Xmf\Request::getInt('startauto', 0, 'GET');
    $storiescount = $newsStoryHandler->getAllStoriesCount(2, false);
    $storyarray   = $newsStoryHandler->getAllAutoStory($helper->getConfig('storycountadmin'), true, $start);
    $class        = '';
    if (count($storyarray) > 0) {
        $pagenav = new \XoopsPageNav($storiescount, $helper->getConfig('storycountadmin'), $start, 'startauto', 'op=newarticle');
        xnews_collapsableBar('autostories', 'topautostories');
        echo "<img onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='topautostories' name='topautostories' src='" . XNEWS_MODULE_URL . "/assets/images/close12.gif' alt=''></a>&nbsp;" . _AM_XNEWS_AUTOARTICLES . '</h4>';
        echo "<div id='autostories'>";
        echo '<br>';
        echo "<div style='text-align: center;'>\n";
        echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr class='bg3'><td align='center'>"
             . _AM_XNEWS_STORYID
             . "</td><td align='center'>"
             . _AM_XNEWS_TITLE
             . "</td><td align='center'>"
             . _AM_XNEWS_TOPIC
             . "</td><td align='center'>"
             . _AM_XNEWS_POSTER
             . "</td><td align='center' class='news'>"
             . _AM_XNEWS_PROGRAMMED
             . "</td><td align='center' class='news'>"
             . _AM_XNEWS_EXPIRED
             . "</td><td align='center'>"
             . _AM_XNEWS_ACTION
             . '</td></tr>';
        foreach ($storyarray as $autostory) {
            $topic  = $autostory->topic();
            $expire = ($autostory->expired() > 0) ? formatTimestamp($autostory->expired(), $helper->getConfig('dateformat')) : '';
            $class  = ('even' === $class) ? 'odd' : 'even';
            echo "<tr class='" . $class . "'>";
            echo "<td align='center'><b>"
                 . $autostory->storyid()
                 . "</b>
                </td><td align='left'><a href='"
                 . XNEWS_MODULE_URL
                 . '/article.php?storyid='
                 . $autostory->storyid()
                 . "'>"
                 . $autostory->title()
                 . "</a>
                </td><td align='center'>"
                 . $topic->topic_title()
                 . "
                </td><td align='center'><a href='"
                 . XOOPS_URL
                 . '/userinfo.php?uid='
                 . $autostory->uid()
                 . "'>"
                 . $autostory->uname()
                 . "</a></td><td align='center' class='news'>"
                 . formatTimestamp($autostory->published(), $helper->getConfig('dateformat'))
                 . "</td><td align='center'>"
                 . $expire
                 . "</td><td align='center'><a href='"
                 . XNEWS_MODULE_URL
                 . '/submit.php?returnside=1&amp;op=edit&amp;storyid='
                 . $autostory->storyid()
                 . "'>"
                 . _AM_XNEWS_EDIT
                 . "</a>-<a href='"
                 . XNEWS_MODULE_URL
                 . '/admin/index.php?op=delete&amp;storyid='
                 . $autostory->storyid()
                 . "'>"
                 . _AM_XNEWS_DELETE
                 . '</a>';
            echo "</td></tr>\n";
        }
        echo '</table></div>';
        echo "<div align='right'>" . $pagenav->renderNav() . '</div><br>';
        echo '</div><br>';
    }
}

/**
 * Shows last x published stories
 *
 * This list can be view in the module's admin when you click on the tab named "Post/Edit News"
 * Actually you can see the the story's ID, its title, the topic, the author, the number of hits
 * and two links. The first link is used to edit the story while the second is used to remove the story.
 * The table only contains the last X published stories.
 * You can modify the number of visible stories with the module's option named
 * "Number of new articles to display in admin area".
 * As the number of displayed stories is limited, below this list you can find a text box
 * that you can use to enter a story's Id, then with the scrolling list you can select
 * if you want to edit or delete the story.
 */
function lastStories()
{
    /** @var Xnews\Helper $helper */
    $helper           = Xnews\Helper::getInstance();
    $newsStoryHandler = new Xnews\NewsStory();

    xnews_collapsableBar('laststories', 'toplaststories');
    echo "<img onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='toplaststories' name='toplaststories' src='" . XNEWS_MODULE_URL . "/assets/images/close12.gif' alt=''></a>&nbsp;" . sprintf(_AM_XNEWS_LAST10ARTS, $helper->getConfig('storycountadmin')) . '</h4>';
    echo "<div id='laststories'>";
    echo '<br>';
    echo "<div style='text-align: center;'>";
    $start        = \Xmf\Request::getInt('start', 0, 'GET');
    $storyarray   = $newsStoryHandler->getAllPublished($helper->getConfig('storycountadmin'), $start, false, 0, 1);
    $storiescount = $newsStoryHandler->getAllStoriesCount(4, false);
    $pagenav      = new \XoopsPageNav($storiescount, $helper->getConfig('storycountadmin'), $start, 'start', 'op=newarticle');
    $class        = '';
    echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr class='bg3'><td align='center'>"
         . _AM_XNEWS_STORYID
         . "</td><td align='center'>"
         . _AM_XNEWS_TITLE
         . "</td><td align='center'>"
         . _AM_XNEWS_TOPIC
         . "</td><td align='center'>"
         . _AM_XNEWS_POSTER
         . "</td><td align='center' class='news'>"
         . _AM_XNEWS_PUBLISHED
         . "</td><td align='center' class='news'>"
         . _AM_XNEWS_HITS
         . "</td><td align='center'>"
         . _AM_XNEWS_ACTION
         . '</td></tr>';
    foreach ($storyarray as $eachstory) {
        $published = formatTimestamp($eachstory->published(), $helper->getConfig('dateformat'));
        // $expired = ( $eachstory -> expired() > 0) ? formatTimestamp($eachstory->expired(), $helper->getConfig('dateformat')) : '---';
        $topic = $eachstory->topic();
        $class = ('even' === $class) ? 'odd' : 'even';
        echo "<tr class='" . $class . "'>";
        echo "<td align='center'><b>"
             . $eachstory->storyid()
             . "</b>
            </td><td align='left'><a href='"
             . XNEWS_MODULE_URL
             . '/article.php?storyid='
             . $eachstory->storyid()
             . "'>"
             . $eachstory->title()
             . "</a>
            </td><td align='center'>"
             . $topic->topic_title()
             . "
            </td><td align='center'><a href='"
             . XOOPS_URL
             . '/userinfo.php?uid='
             . $eachstory->uid()
             . "'>"
             . $eachstory->uname()
             . "</a></td><td align='center' class='news'>"
             . $published
             . "</td><td align='center'>"
             . $eachstory->counter()
             . "</td><td align='center'><a href='"
             . XNEWS_MODULE_URL
             . '/submit.php?returnside=1&amp;op=edit&amp;storyid='
             . $eachstory->storyid()
             . "'>"
             . _AM_XNEWS_EDIT
             . "</a>-<a href='"
             . XNEWS_MODULE_URL
             . '/admin/index.php?op=delete&amp;storyid='
             . $eachstory->storyid()
             . "'>"
             . _AM_XNEWS_DELETE
             . '</a>';
        echo "</td></tr>\n";
    }
    echo '</table><br>';
    echo "<div align='right'>" . $pagenav->renderNav() . '</div><br>';

    echo "<form action='index.php' method='get'>" . _AM_XNEWS_STORYID . " <input type='text' name='storyid' size='10' >
        <select name='op'>
            <option value='edit' selected='selected'>" . _AM_XNEWS_EDIT . "</option>
            <option value='delete'>" . _AM_XNEWS_DELETE . "</option>
        </select>
        <input type='hidden' name='returnside' value='1'>
        <input type='submit' value='" . _AM_XNEWS_GO . "' >
        </form>
    </div>";
    echo '</div><br>';
}

/**
 * Display a list of the expired stories
 *
 * This list can be view in the module's admin when you click on the tab named "Post/Edit News"
 * Actually you can see the story's ID, the title, the topic, the author,
 * the creation and expiration's date and you have two links, one to delete
 * the story and the other to edit the story.
 * The table only contains the last X expired stories.
 * You can modify the number of visible stories with the module's option named
 * "Number of new articles to display in admin area".
 * As the number of displayed stories is limited, below this list you can find a text box
 * that you can use to enter a story's Id, then with the scrolling list you can select
 * if you want to edit or delete the story.
 */
function expStories()
{
    /** @var Xnews\Helper $helper */
    $helper           = Xnews\Helper::getInstance();
    $newsStoryHandler = new Xnews\NewsStory();

    $start        = \Xmf\Request::getInt('startexp', 0, 'GET');
    $expiredcount = $newsStoryHandler->getAllStoriesCount(1, false);
    $storyarray   = $newsStoryHandler->getAllExpired($helper->getConfig('storycountadmin'), $start, 0, 1);
    $pagenav      = new \XoopsPageNav($expiredcount, $helper->getConfig('storycountadmin'), $start, 'startexp', 'op=newarticle');

    if (count($storyarray) > 0) {
        $class = '';
        xnews_collapsableBar('expstories', 'topexpstories');
        echo "<img onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='topexpstories' name='topexpstories' src='" . XNEWS_MODULE_URL . "/assets/images/close12.gif' alt=''></a>&nbsp;" . _AM_XNEWS_EXPARTS . '</h4>';
        echo "<div id='expstories'>";
        echo '<br>';
        echo "<div style='text-align: center;'>";
        echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr class='bg3'><td align='center'>"
             . _AM_XNEWS_STORYID
             . "</td><td align='center'>"
             . _AM_XNEWS_TITLE
             . "</td><td align='center'>"
             . _AM_XNEWS_TOPIC
             . "</td><td align='center'>"
             . _AM_XNEWS_POSTER
             . "</td><td align='center' class='news'>"
             . _AM_XNEWS_CREATED
             . "</td><td align='center' class='news'>"
             . _AM_XNEWS_EXPIRED
             . "</td><td align='center'>"
             . _AM_XNEWS_ACTION
             . '</td></tr>';
        foreach ($storyarray as $eachstory) {
            $created = formatTimestamp($eachstory->created(), $helper->getConfig('dateformat'));
            $expired = formatTimestamp($eachstory->expired(), $helper->getConfig('dateformat'));
            $topic   = $eachstory->topic();
            // added exired value field to table
            $class = ('even' === $class) ? 'odd' : 'even';
            echo "<tr class='" . $class . "'>";
            echo "<td align='center'><b>"
                 . $eachstory->storyid()
                 . "</b>
                </td><td align='left'><a href='"
                 . XNEWS_MODULE_URL
                 . '/article.php?returnside=1&amp;storyid='
                 . $eachstory->storyid()
                 . "'>"
                 . $eachstory->title()
                 . "</a>
                </td><td align='center'>"
                 . $topic->topic_title()
                 . "
                </td><td align='center'><a href='"
                 . XOOPS_URL
                 . '/userinfo.php?uid='
                 . $eachstory->uid()
                 . "'>"
                 . $eachstory->uname()
                 . "</a></td><td align='center' class='news'>"
                 . $created
                 . "</td><td align='center' class='news'>"
                 . $expired
                 . "</td><td align='center'><a href='"
                 . XNEWS_MODULE_URL
                 . '/submit.php?returnside=1&amp;op=edit&amp;storyid='
                 . $eachstory->storyid()
                 . "'>"
                 . _AM_XNEWS_EDIT
                 . "</a>-<a href='"
                 . XNEWS_MODULE_URL
                 . '/admin/index.php?op=delete&amp;storyid='
                 . $eachstory->storyid()
                 . "'>"
                 . _AM_XNEWS_DELETE
                 . '</a>';
            echo "</td></tr>\n";
        }
        echo '</table><br>';
        echo "<div align='right'>" . $pagenav->renderNav() . '</div><br>';
        echo "<form action='index.php' method='get'>
            " . _AM_XNEWS_STORYID . " <input type='text' name='storyid' size='10' >
            <select name='op'>
                <option value='edit' selected='selected'>" . _AM_XNEWS_EDIT . "</option>
                <option value='delete'>" . _AM_XNEWS_DELETE . "</option>
            </select>
            <input type='hidden' name='returnside' value='1'>
            <input type='submit' value='" . _AM_XNEWS_GO . "' >
            </form>
        </div>";
        echo '</div><br>';
    }
}

// Save a topic after it has been modified
function modTopicS()
{
    /** @var Xnews\Helper $helper */
    $helper           = Xnews\Helper::getInstance();
    $newsStoryHandler = new Xnews\NewsStory();

    $xt = new Xnews\NewsTopic(\Xmf\Request::getInt('topic_id', 0, 'POST'));
    if (\Xmf\Request::getInt('topic_pid', 0, 'POST') == \Xmf\Request::getInt('topic_id', 0, 'POST')) {
        redirect_header('index.php?op=topicsmanager', 3, _AM_XNEWS_ADD_TOPIC_ERROR1);
    }
    $xt->setTopicPid(\Xmf\Request::getInt('topic_pid', 0, 'POST'));
    if (empty($_POST['topic_title'])) {
        redirect_header('index.php?op=topicsmanager', 3, _AM_XNEWS_ERRORTOPICNAME);
    }
    if (\Xmf\Request::hasVar('items_count', 'SESSION')) {
        $_SESSION['items_count'] = -1;
    }
    $xt->setTopicTitle($_POST['topic_title']);
    if (\Xmf\Request::hasVar('topic_imgurl', 'POST') && '' != $_POST['topic_imgurl']) {
        $xt->setTopicImgurl($_POST['topic_imgurl']);
    }
    $xt->setMenu(\Xmf\Request::getInt('submenu', 0, 'POST'));
    $xt->setTopicFrontpage(\Xmf\Request::getInt('topic_frontpage', 0, 'POST'));
    if (\Xmf\Request::hasVar('topic_description', 'POST')) {
        $xt->setTopicDescription($_POST['topic_description']);
    } else {
        $xt->setTopicDescription('');
    }
    //$xt->Settopic_rssurl($_POST['topic_rssfeed']);
    $xt->setTopic_color($_POST['topic_color']);

    if (\Xmf\Request::hasVar('xoops_upload_file', 'POST')) {
        $fldname = $_FILES[$_POST['xoops_upload_file'][0]];
        $fldname = @get_magic_quotes_gpc() ? stripslashes($fldname['name']) : $fldname['name'];
        if (xoops_trim('' != $fldname)) {
            $sfiles         = new Xnews\Files();
            $dstpath        = XNEWS_TOPICS_FILES_PATH;
            $destname       = $sfiles->createUploadName($dstpath, $fldname, true);
            $permittedtypes = ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'];
            $uploader       = new \XoopsMediaUploader($dstpath, $permittedtypes, $helper->getConfig('maxuploadsize'));
            $uploader->setTargetFileName($destname);
            if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                if ($uploader->upload()) {
                    $xt->setTopicImgurl(basename($destname));
                } else {
                    echo _AM_XNEWS_UPLOAD_ERROR . ' ' . $uploader->getErrors();
                }
            } else {
                echo $uploader->getErrors();
            }
        }
    }
    $xt->store();

    // Permissions
    /** @var \XoopsGroupPermHandler $grouppermHandler */
    $grouppermHandler = xoops_getHandler('groupperm');
    $criteria         = new \CriteriaCompo();
    $criteria->add(new \Criteria('gperm_itemid', $xt->topic_id(), '='));
    $criteria->add(new \Criteria('gperm_modid', $helper->getModule()->getVar('mid'), '='));
    $criteria->add(new \Criteria('gperm_name', 'nw_approve', '='));
    $grouppermHandler->deleteAll($criteria);

    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('gperm_itemid', $xt->topic_id(), '='));
    $criteria->add(new \Criteria('gperm_modid', $helper->getModule()->getVar('mid'), '='));
    $criteria->add(new \Criteria('gperm_name', 'nw_submit', '='));
    $grouppermHandler->deleteAll($criteria);

    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('gperm_itemid', $xt->topic_id(), '='));
    $criteria->add(new \Criteria('gperm_modid', $helper->getModule()->getVar('mid'), '='));
    $criteria->add(new \Criteria('gperm_name', 'nw_view', '='));
    $grouppermHandler->deleteAll($criteria);

    if (\Xmf\Request::hasVar('groups_news_can_approve', 'POST')) {
        foreach ($_POST['groups_news_can_approve'] as $onegroup_id) {
            $grouppermHandler->addRight('nw_approve', $xt->topic_id(), $onegroup_id, $helper->getModule()->getVar('mid'));
        }
    }

    if (\Xmf\Request::hasVar('groups_news_can_submit', 'POST')) {
        foreach ($_POST['groups_news_can_submit'] as $onegroup_id) {
            $grouppermHandler->addRight('nw_submit', $xt->topic_id(), $onegroup_id, $helper->getModule()->getVar('mid'));
        }
    }

    if (\Xmf\Request::hasVar('groups_news_can_view', 'POST')) {
        foreach ($_POST['groups_news_can_view'] as $onegroup_id) {
            $grouppermHandler->addRight('nw_view', $xt->topic_id(), $onegroup_id, $helper->getModule()->getVar('mid'));
        }
    }

    nw_updateCache();
    redirect_header('index.php?op=topicsmanager', 3, _AM_XNEWS_DBUPDATED);
}

// Delete a topic and its subtopics and its stories and the related stories
function delTopic()
{
    /** @var Xnews\Helper $helper */
    $helper           = Xnews\Helper::getInstance();
    $newsStoryHandler = \XoopsModules\Xnews\Helper::getInstance()->getHandler('NewsStory');

    if (!isset($_POST['ok'])) {
        xoops_cp_header();
        echo '<h2>' . _AM_XNEWS_TOPICSMNGR . '</h2>';
        $xt = new Xnews\Deprecate\DeprecateTopic($GLOBALS[Constants::XOOPSDB]->prefix('nw_topics'), \Xmf\Request::getInt('topic_id', 0, 'GET'));
        xoops_confirm(['op' => 'delTopic', 'topic_id' => \Xmf\Request::getInt('topic_id', 0, 'GET'), 'ok' => 1], 'index.php', _AM_XNEWS_WAYSYWTDTTAL . '<br>' . $xt->topic_title('S'));
    } else {
        xoops_cp_header();
        $xt = new Xnews\Deprecate\DeprecateTopic($GLOBALS[Constants::XOOPSDB]->prefix('nw_topics'), \Xmf\Request::getInt('topic_id', 0, 'POST'));
        if (\Xmf\Request::hasVar('items_count', 'SESSION')) {
            $_SESSION['items_count'] = -1;
        }
        // get all subtopics under the specified topic
        $topic_arr = $xt->getAllChildTopics();
        array_push($topic_arr, $xt);
        foreach ($topic_arr as $eachtopic) {
            // get all stories in each topic
            $story_arr = Xnews\NewsStory:: getByTopic($eachtopic->topic_id());
            foreach ($story_arr as $eachstory) {
                if (false !== $eachstory->delete()) {
                    xoops_comment_delete($helper->getModule()->getVar('mid'), $eachstory->storyid());
                    xoops_notification_deletebyitem($helper->getModule()->getVar('mid'), 'story', $eachstory->storyid());
                }
            }
            // all stories for each topic is deleted, now delete the topic data
            $eachtopic->delete();
            // Delete also the notifications and permissions
            xoops_notification_deletebyitem($helper->getModule()->getVar('mid'), 'category', $eachtopic->topic_id);
            xoops_groupperm_deletebymoditem($helper->getModule()->getVar('mid'), 'nw_approve', $eachtopic->topic_id);
            xoops_groupperm_deletebymoditem($helper->getModule()->getVar('mid'), 'nw_submit', $eachtopic->topic_id);
            xoops_groupperm_deletebymoditem($helper->getModule()->getVar('mid'), 'nw_view', $eachtopic->topic_id);
        }
        nw_updateCache();
        redirect_header('index.php?op=topicsmanager', 3, _AM_XNEWS_DBUPDATED);
    }
}

// Add a new topic
function addTopic()
{
    /** @var Xnews\Helper $helper */
    $helper           = Xnews\Helper::getInstance();
    $newsStoryHandler = new Xnews\NewsStory();

    $topicpid = \Xmf\Request::getInt('topic_pid', 0, 'POST');
    $xt       = new Xnews\NewsTopic();
    if (!$xt->topicExists($topicpid, $_POST['topic_title'])) {
        $xt->setTopicPid($topicpid);
        if (empty($_POST['topic_title']) || '' == xoops_trim($_POST['topic_title'])) {
            redirect_header('index.php?op=topicsmanager', 3, _AM_XNEWS_ERRORTOPICNAME);
        }
        $xt->setTopicTitle($_POST['topic_title']);
        //$xt->Settopic_rssurl($_POST['topic_rssfeed']);
        $xt->setTopic_color($_POST['topic_color']);
        if (\Xmf\Request::hasVar('topic_imgurl', 'POST') && '' != $_POST['topic_imgurl']) {
            $xt->setTopicImgurl($_POST['topic_imgurl']);
        }
        $xt->setMenu(\Xmf\Request::getInt('submenu', 0, 'POST'));
        $xt->setTopicFrontpage(\Xmf\Request::getInt('topic_frontpage', 0, 'POST'));
        if (\Xmf\Request::hasVar('items_count', 'SESSION')) {
            $_SESSION['items_count'] = -1;
        }
        if (\Xmf\Request::hasVar('xoops_upload_file', 'POST')) {
            $fldname = $_FILES[$_POST['xoops_upload_file'][0]];
            $fldname = @get_magic_quotes_gpc() ? stripslashes($fldname['name']) : $fldname['name'];
            if (xoops_trim('' != $fldname)) {
                $sfiles         = new Xnews\Files();
                $dstpath        = XNEWS_TOPICS_FILES_PATH;
                $destname       = $sfiles->createUploadName($dstpath, $fldname, true);
                $permittedtypes = ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'];
                $uploader       = new \XoopsMediaUploader($dstpath, $permittedtypes, $helper->getConfig('maxuploadsize'));
                $uploader->setTargetFileName($destname);
                if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                    if ($uploader->upload()) {
                        $xt->setTopicImgurl(basename($destname));
                    } else {
                        echo _AM_XNEWS_UPLOAD_ERROR . ' ' . $uploader->getErrors();
                    }
                } else {
                    echo $uploader->getErrors();
                }
            }
        }
        if (\Xmf\Request::hasVar('topic_description', 'POST')) {
            $xt->setTopicDescription($_POST['topic_description']);
        } else {
            $xt->setTopicDescription('');
        }
        $xt->store();
        // Permissions
        /** @var \XoopsGroupPermHandler $grouppermHandler */
        $grouppermHandler = xoops_getHandler('groupperm');
        if (\Xmf\Request::hasVar('groups_news_can_approve', 'POST')) {
            foreach ($_POST['groups_news_can_approve'] as $onegroup_id) {
                $grouppermHandler->addRight('nw_approve', $xt->topic_id(), $onegroup_id, $helper->getModule()->getVar('mid'));
            }
        }

        if (\Xmf\Request::hasVar('groups_news_can_submit', 'POST')) {
            foreach ($_POST['groups_news_can_submit'] as $onegroup_id) {
                $grouppermHandler->addRight('nw_submit', $xt->topic_id(), $onegroup_id, $helper->getModule()->getVar('mid'));
            }
        }

        if (\Xmf\Request::hasVar('groups_news_can_view', 'POST')) {
            foreach ($_POST['groups_news_can_view'] as $onegroup_id) {
                $grouppermHandler->addRight('nw_view', $xt->topic_id(), $onegroup_id, $helper->getModule()->getVar('mid'));
            }
        }
        nw_updateCache();
        /** @var \XoopsNotificationHandler $notificationHandler */
        $notificationHandler = xoops_getHandler('notification');
        $tags                = [];
        $tags['TOPIC_NAME']  = $_POST['topic_title'];
        $notificationHandler->triggerEvent('global', 0, 'new_category', $tags);
        redirect_header('index.php?op=topicsmanager', 3, _AM_XNEWS_DBUPDATED);
    } else {
        redirect_header('index.php?op=topicsmanager', 3, _AM_XNEWS_ADD_TOPIC_ERROR);
    }
    exit();
}

// **********************************************************************************************************************************************
// **** Main
// **********************************************************************************************************************************************

$op = Request::getString('op', 'default');

switch ($op) {
    case 'deletefile':
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation($currentFile);

        if ('newsletter' === $_GET['type']) {
            $newsfile = XOOPS_ROOT_PATH . '/uploads/newsletter.txt';
            if (unlink($newsfile)) {
                redirect_header('index.php', 3, _AM_XNEWS_DELETED_OK);
            } else {
                redirect_header('index.php', 3, _AM_XNEWS_DELETED_PB);
            }
        } else {
            if ('xml' === $_GET['type']) {
                $xmlfile = XOOPS_ROOT_PATH . '/uploads/nw_stories.xml';
                if (unlink($xmlfile)) {
                    redirect_header('index.php', 3, _AM_XNEWS_DELETED_OK);
                } else {
                    redirect_header('index.php', 3, _AM_XNEWS_DELETED_PB);
                }
            }
        }
        xoops_cp_footer();
        break;
    case 'newarticle':
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation('index.php?op=newarticle');

        require_once XOOPS_ROOT_PATH . '/class/module.textsanitizer.php';
        newSubmissions();
        autoStories();
        lastStories();
        expStories();
        echo '<br>';
        echo '<h4>' . _AM_XNEWS_POSTNEWARTICLE . '</h4>';
        $type         = 'admin';
        $title        = '';
        $topicdisplay = 0;
        $topicalign   = 'R';
        $ihome        = 0;
        $hometext     = '';
        $bodytext     = '';
        $notifypub    = 1;
        $nohtml       = 0;
        $approve      = 0;
        $nosmiley     = 0;
        $dobr         = 1;
        $imagerows    = 1;
        $pdfrows      = 1;
        $autodate     = '';
        $expired      = '';
        $topicid      = 0;
        $returnside   = 1;
        $published    = 0;
        $description  = '';
        $keywords     = '';
        xoops_loadLanguage('main', 'xnews');

        if (1 == $helper->getConfig('autoapprove')) {
            $approve = 1;
        }
        $approveprivilege = 1;
        require_once XNEWS_MODULE_PATH . '/include/storyform.original.php';
        xoops_cp_footer();
        break;
    case 'delete':

        $storyid = \Xmf\Request::getInt('storyid', 0, 'REQUEST');

        if (\Xmf\Request::hasVar('ok', 'POST')) {
            if (empty($storyid)) {
                redirect_header('index.php?op=newarticle', 3, _AM_XNEWS_EMPTYNODELETE);
            }
            $story = new Xnews\NewsStory($storyid);
            $story->delete();
            $sfiles   = new Xnews\Files();
            $filesarr = [];
            $filesarr = $sfiles->getAllbyStory($storyid);
            if (count($filesarr) > 0) {
                foreach ($filesarr as $onefile) {
                    $onefile->delete();
                }
            }
            xoops_comment_delete($helper->getModule()->getVar('mid'), $storyid);
            xoops_notification_deletebyitem($helper->getModule()->getVar('mid'), 'story', $storyid);
            nw_updateCache();
            redirect_header('index.php?op=newarticle', 3, _AM_XNEWS_DBUPDATED);
        } else {
            $story = new Xnews\NewsStory($storyid);
            xoops_cp_header();
            echo '<h4>' . _AM_XNEWS_CONFIG . '</h4>';
            xoops_confirm(['op' => 'delete', 'storyid' => $storyid, 'ok' => 1], 'index.php', _AM_XNEWS_RUSUREDEL . '<br>' . $story->title());
        }
        break;
    case 'topicsmanager':
        /*
         * Topics manager
         *
         * It's from here that you can list, add, modify an delete topics
         * At first, you can see a list of all the topics in your databases. This list contains the topic's ID, its name,
         * its parent topic, if it should be visible in the Xoops main menu and an action (Edit or Delete topic)
         * Below this list you find the form used to create and edit the topics.
         * use this form to :
         * - Type the topic's title
         * - Enter its description
         * - Select its parent topic
         * - Choose a color
         * - Select if it must appear in the Xoops main menu
         * - Choose if you want to see in the front page. If it's not the case, visitors will have to use the navigation box to see it
         * - And finally you ca select an image to represent the topic
         * The text box called "URL of RSS feed" is, for this moment, not used.
         */
        global $myts;

        //  admin navigation
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation('index.php?op=topicsmanager');

        xoops_load('XoopsFormLoader');
        xoops_load('XoopsTree');

        $uploadfolder   = sprintf(_AM_XNEWS_UPLOAD_WARNING, XNEWS_TOPICS_FILES_URL);
        $uploadirectory = '/uploads/' . $helper->getModule()->dirname() . '/assets/images/topics';
        $start          = \Xmf\Request::getInt('start', 0, 'GET');

        $xt          = new \XoopsTree($GLOBALS[Constants::XOOPSDB]->prefix('nw_topics'), 'topic_id', 'topic_pid');
        $topics_arr  = $xt->getChildTreeArray(0, 'topic_title');
        $totaltopics = count($topics_arr);
        $class       = '';

        xnews_collapsableBar('topicsmanager', 'toptopicsmanager');
        echo "<img onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='toptopicsmanager' name='toptopicsmanager' src='" . XNEWS_MODULE_URL . "/assets/images/close12.gif' alt=''></a>&nbsp;" . _AM_XNEWS_TOPICS . ' (' . $totaltopics . ')' . '</h4>';
        echo "<div id='topicsmanager'>";
        echo '<br>';
        echo "<div style='text-align: center;'>";
        echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr class='bg3'><td align='center'>"
             . _AM_XNEWS_TOPIC
             . "</td><td align='left'>"
             . _AM_XNEWS_TOPICNAME
             . "</td><td align='center'>"
             . _AM_XNEWS_PARENTTOPIC
             . "</td><td align='center'>"
             . _AM_XNEWS_SUB_MENU_YESNO
             . "</td><td align='center'>"
             . _AM_XNEWS_ACTION
             . '</td></tr>';
        if (is_array($topics_arr) && $totaltopics) {
            $cpt    = 1;
            $tmpcpt = $start;
            $ok     = true;
            $output = '';
            while ($ok) {
                if ($tmpcpt < $totaltopics) {
                    $linkedit   = XNEWS_MODULE_URL . '/admin/index.php?op=topicsmanager&amp;topic_id=' . $topics_arr[$tmpcpt]['topic_id'];
                    $linkdelete = XNEWS_MODULE_URL . '/admin/index.php?op=delTopic&amp;topic_id=' . $topics_arr[$tmpcpt]['topic_id'];
                    $action     = sprintf("<a href='%s'>%s</a> - <a href='%s'>%s</a>", $linkedit, _AM_XNEWS_EDIT, $linkdelete, _AM_XNEWS_DELETE);
                    $parent     = '&nbsp;';
                    if ($topics_arr[$tmpcpt]['topic_pid'] > 0) {
                        $xttmp  = new Xnews\Deprecate\DeprecateTopic($GLOBALS[Constants::XOOPSDB]->prefix('nw_topics'), $topics_arr[$tmpcpt]['topic_pid']);
                        $parent = $xttmp->topic_title();
                        unset($xttmp);
                    }
                    if (0 != $topics_arr[$tmpcpt]['topic_pid']) {
                        $topics_arr[$tmpcpt]['prefix'] = str_replace('.', '-', $topics_arr[$tmpcpt]['prefix']) . '&nbsp;';
                    } else {
                        $topics_arr[$tmpcpt]['prefix'] = str_replace('.', '', $topics_arr[$tmpcpt]['prefix']);
                    }
                    $submenu = $topics_arr[$tmpcpt]['menu'] ? _YES : _NO;
                    $class   = ('even' === $class) ? 'odd' : 'even';
                    $output  = $output
                               . "<tr class='"
                               . $class
                               . "'><td>"
                               . $topics_arr[$tmpcpt]['topic_id']
                               . "</td><td align='left'>"
                               . $topics_arr[$tmpcpt]['prefix']
                               . $myts->displayTarea($topics_arr[$tmpcpt]['topic_title'])
                               . "</td><td align='left'>"
                               . $parent
                               . '</td><td>'
                               . $submenu
                               . '</td><td>'
                               . $action
                               . '</td></tr>';
                } else {
                    $ok = false;
                }
                if ($cpt >= $helper->getConfig('storycountadmin')) {
                    $ok = false;
                }
                $tmpcpt++;
                $cpt++;
            }
            echo $output;
        }
        $pagenav = new \XoopsPageNav($totaltopics, $helper->getConfig('storycountadmin'), $start, 'start', 'op=topicsmanager');
        echo "</table><div align='right'>" . $pagenav->renderNav() . '</div><br>';
        echo "</div></div><br>\n";

        $topic_id = \Xmf\Request::getInt('topic_id', 0, 'GET');
        if ($topic_id > 0) {
            $xtmod             = new Xnews\NewsTopic($topic_id);
            $topic_title       = $xtmod->topic_title('E');
            $topic_description = $xtmod->topic_description('E');
            $topic_rssfeed     = $xtmod->topic_rssurl('E');
            $op                = 'modTopicS';
            if ('' != xoops_trim($xtmod->topic_imgurl())) {
                $topicimage = $xtmod->topic_imgurl();
            } else {
                $topicimage = 'blank.png';
            }
            $btnlabel        = _AM_XNEWS_MODIFY;
            $parent          = $xtmod->topic_pid();
            $formlabel       = _AM_XNEWS_MODIFYTOPIC;
            $submenu         = $xtmod->menu();
            $topic_frontpage = $xtmod->topic_frontpage();
            $topic_color     = $xtmod->topic_color();
            unset($xtmod);
        } else {
            $topic_title       = '';
            $topic_frontpage   = 1;
            $topic_description = '';
            $op                = 'addTopic';
            $topicimage        = 'xoops.gif';
            $btnlabel          = _AM_XNEWS_ADD;
            $parent            = -1;
            $submenu           = 0;
            $topic_rssfeed     = '';
            $formlabel         = _AM_XNEWS_ADD_TOPIC;
            $topic_color       = '000000';
        }

        $sform = new \XoopsThemeForm($formlabel, 'topicform', XNEWS_MODULE_URL . '/admin/index.php', 'post', true);
        $sform->setExtra('enctype="multipart/form-data"');
        $sform->addElement(new \XoopsFormText(_AM_XNEWS_TOPICNAME, 'topic_title', 50, 255, $topic_title), true);
        $editor = nw_getWysiwygForm(_AM_XNEWS_TOPIC_DESCR, 'topic_description', $topic_description, 15, 60, '100%', '350px', 'hometext_hidden');
        if ($editor) {
            $sform->addElement($editor, false);
        }

        $sform->addElement(new \XoopsFormHidden('op', $op), false);
        $sform->addElement(new \XoopsFormHidden('topic_id', $topic_id), false);

        // require_once XNEWS_MODULE_PATH . '/class/NewsTopic.php';
        $xt = new Xnews\NewsTopic();
        $sform->addElement(new \XoopsFormLabel(_AM_XNEWS_PARENTTOPIC, $xt->MakeMyTopicSelBox(1, $parent, 'topic_pid', '', false)));
        // Topic's color
        // Code stolen to Zoullou, thank you Zoullou ;-)
        $select_color = "\n<select name='topic_color'  onchange='xoopsGetElementById(\"NewsColorSelect\").style.backgroundColor = \"#\" + this.options[this.selectedIndex].value;'>\n<option value='000000'>" . _AM_XNEWS_COLOR . "</option>\n";
        $color_values = [
            '000000',
            '000033',
            '000066',
            '000099',
            '0000CC',
            '0000FF',
            '003300',
            '003333',
            '003366',
            '0033CC',
            '0033FF',
            '006600',
            '006633',
            '006666',
            '006699',
            '0066CC',
            '0066FF',
            '009900',
            '009933',
            '009966',
            '009999',
            '0099CC',
            '0099FF',
            '00CC00',
            '00CC33',
            '00CC66',
            '00CC99',
            '00CCCC',
            '00CCFF',
            '00FF00',
            '00FF33',
            '00FF66',
            '00FF99',
            '00FFCC',
            '00FFFF',
            '330000',
            '330033',
            '330066',
            '330099',
            '3300CC',
            '3300FF',
            '333300',
            '333333',
            '333366',
            '333399',
            '3333CC',
            '3333FF',
            '336600',
            '336633',
            '336666',
            '336699',
            '3366CC',
            '3366FF',
            '339900',
            '339933',
            '339966',
            '339999',
            '3399CC',
            '3399FF',
            '33CC00',
            '33CC33',
            '33CC66',
            '33CC99',
            '33CCCC',
            '33CCFF',
            '33FF00',
            '33FF33',
            '33FF66',
            '33FF99',
            '33FFCC',
            '33FFFF',
            '660000',
            '660033',
            '660066',
            '660099',
            '6600CC',
            '6600FF',
            '663300',
            '663333',
            '663366',
            '663399',
            '6633CC',
            '6633FF',
            '666600',
            '666633',
            '666666',
            '666699',
            '6666CC',
            '6666FF',
            '669900',
            '669933',
            '669966',
            '669999',
            '6699CC',
            '6699FF',
            '66CC00',
            '66CC33',
            '66CC66',
            '66CC99',
            '66CCCC',
            '66CCFF',
            '66FF00',
            '66FF33',
            '66FF66',
            '66FF99',
            '66FFCC',
            '66FFFF',
            '990000',
            '990033',
            '990066',
            '990099',
            '9900CC',
            '9900FF',
            '993300',
            '993333',
            '993366',
            '993399',
            '9933CC',
            '9933FF',
            '996600',
            '996633',
            '996666',
            '996699',
            '9966CC',
            '9966FF',
            '999900',
            '999933',
            '999966',
            '999999',
            '9999CC',
            '9999FF',
            '99CC00',
            '99CC33',
            '99CC66',
            '99CC99',
            '99CCCC',
            '99CCFF',
            '99FF00',
            '99FF33',
            '99FF66',
            '99FF99',
            '99FFCC',
            '99FFFF',
            'CC0000',
            'CC0033',
            'CC0066',
            'CC0099',
            'CC00CC',
            'CC00FF',
            'CC3300',
            'CC3333',
            'CC3366',
            'CC3399',
            'CC33CC',
            'CC33FF',
            'CC6600',
            'CC6633',
            'CC6666',
            'CC6699',
            'CC66CC',
            'CC66FF',
            'CC9900',
            'CC9933',
            'CC9966',
            'CC9999',
            'CC99CC',
            'CC99FF',
            'CCCC00',
            'CCCC33',
            'CCCC66',
            'CCCC99',
            'CCCCCC',
            'CCCCFF',
            'CCFF00',
            'CCFF33',
            'CCFF66',
            'CCFF99',
            'CCFFCC',
            'CCFFFF',
            'FF0000',
            'FF0033',
            'FF0066',
            'FF0099',
            'FF00CC',
            'FF00FF',
            'FF3300',
            'FF3333',
            'FF3366',
            'FF3399',
            'FF33CC',
            'FF33FF',
            'FF6600',
            'FF6633',
            'FF6666',
            'FF6699',
            'FF66CC',
            'FF66FF',
            'FF9900',
            'FF9933',
            'FF9966',
            'FF9999',
            'FF99CC',
            'FF99FF',
            'FFCC00',
            'FFCC33',
            'FFCC66',
            'FFCC99',
            'FFCCCC',
            'FFCCFF',
            'FFFF00',
            'FFFF33',
            'FFFF66',
            'FFFF99',
            'FFFFCC',
            'FFFFFF',
        ];

        foreach ($color_values as $color_value) {
            if ($topic_color == $color_value) {
                $selected = " selected='selected'";
            } else {
                $selected = '';
            }
            $select_color .= '<option' . $selected . " value='" . $color_value . "' style='background-color:#" . $color_value . ';color:#' . $color_value . ";'>#" . $color_value . "</option>\n";
        }

        $select_color .= "</select>&nbsp;\n<span id='NewsColorSelect'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
        $sform->addElement(new \XoopsFormLabel(_AM_XNEWS_TOPIC_COLOR, $select_color));
        // Sub menu ?
        $sform->addElement(new \XoopsFormRadioYN(_AM_XNEWS_SUB_MENU, 'submenu', $submenu, _YES, _NO));
        $sform->addElement(new \XoopsFormRadioYN(_AM_XNEWS_PUBLISH_FRONTPAGE, 'topic_frontpage', $topic_frontpage, _YES, _NO));
        // Unused for this moment... sorry
        //$sform->addElement(new \XoopsFormText(_AM_XNEWS_RSS_URL, 'topic_rssfeed', 50, 255, $topic_rssfeed), false);
        // ********** Picture
        $imgtray = new \XoopsFormElementTray(_AM_XNEWS_TOPICIMG, '<br>');

        $imgpath      = sprintf(_AM_XNEWS_IMGNAEXLOC, 'uploads/' . $helper->getModule()->dirname() . '/assets/images/topics/');
        $imageselect  = new \XoopsFormSelect($imgpath, 'topic_imgurl', $topicimage);
        $topics_array = \XoopsLists::getImgListAsArray(XNEWS_TOPICS_FILES_PATH);
        foreach ($topics_array as $image) {
            $imageselect->addOption((string)$image, $image);
        }
        $imageselect->setExtra("onchange='showImgSelected(\"image3\", \"topic_imgurl\", \"" . $uploadirectory . '", "", "' . XOOPS_URL . "\")'");
        $imgtray->addElement($imageselect, false);
        $imgtray->addElement(new \XoopsFormLabel('', "<br><img src='" . XOOPS_URL . '/' . $uploadirectory . '/' . $topicimage . "' name='image3' id='image3' alt='' >"));

        $uploadfolder = sprintf(_AM_XNEWS_UPLOAD_WARNING, XNEWS_TOPICS_FILES_URL);
        $fileseltray  = new \XoopsFormElementTray('', '<br>');
        $fileseltray->addElement(new \XoopsFormFile(_AM_XNEWS_TOPIC_PICTURE, 'attachedfile', $helper->getConfig('maxuploadsize')), false);
        $fileseltray->addElement(new \XoopsFormLabel($uploadfolder), false);
        $imgtray->addElement($fileseltray);
        $sform->addElement($imgtray);

        // Permissions
        /** @var \XoopsMemberHandler $memberHandler */
        $memberHandler = xoops_getHandler('member');
        $group_list    = $memberHandler->getGroupList();
        /** @var \XoopsGroupPermHandler $grouppermHandler */
        $grouppermHandler = xoops_getHandler('groupperm');
        $full_list        = array_keys($group_list);

        $groups_ids = [];
        if ($topic_id > 0) {        // Edit mode
            $groups_ids                       = $grouppermHandler->getGroupIds('nw_approve', $topic_id, $helper->getModule()->getVar('mid'));
            $groups_ids                       = array_values($groups_ids);
            $groups_news_can_approve_checkbox = new \XoopsFormCheckBox(_AM_XNEWS_APPROVEFORM, 'groups_news_can_approve[]', $groups_ids);
        } else {    // Creation mode
            $groups_news_can_approve_checkbox = new \XoopsFormCheckBox(_AM_XNEWS_APPROVEFORM, 'groups_news_can_approve[]', $full_list);
        }
        $groups_news_can_approve_checkbox->addOptionArray($group_list);
        $sform->addElement($groups_news_can_approve_checkbox);

        $groups_ids = [];
        if ($topic_id > 0) {        // Edit mode
            $groups_ids                      = $grouppermHandler->getGroupIds('nw_submit', $topic_id, $helper->getModule()->getVar('mid'));
            $groups_ids                      = array_values($groups_ids);
            $groups_news_can_submit_checkbox = new \XoopsFormCheckBox(_AM_XNEWS_SUBMITFORM, 'groups_news_can_submit[]', $groups_ids);
        } else {    // Creation mode
            $groups_news_can_submit_checkbox = new \XoopsFormCheckBox(_AM_XNEWS_SUBMITFORM, 'groups_news_can_submit[]', $full_list);
        }
        $groups_news_can_submit_checkbox->addOptionArray($group_list);
        $sform->addElement($groups_news_can_submit_checkbox);

        $groups_ids = [];
        if ($topic_id > 0) {        // Edit mode
            $groups_ids                    = $grouppermHandler->getGroupIds('nw_view', $topic_id, $helper->getModule()->getVar('mid'));
            $groups_ids                    = array_values($groups_ids);
            $groups_news_can_view_checkbox = new \XoopsFormCheckBox(_AM_XNEWS_VIEWFORM, 'groups_news_can_view[]', $groups_ids);
        } else {    // Creation mode
            $groups_news_can_view_checkbox = new \XoopsFormCheckBox(_AM_XNEWS_VIEWFORM, 'groups_news_can_view[]', $full_list);
        }
        $groups_news_can_view_checkbox->addOptionArray($group_list);
        $sform->addElement($groups_news_can_view_checkbox);

        // Submit buttons
        $buttonTray = new \XoopsFormElementTray('', '');
        $submit_btn = new \XoopsFormButton('', 'post', $btnlabel, 'submit');
        $buttonTray->addElement($submit_btn);
        $sform->addElement($buttonTray);
        $sform->display();
        echo "<script type='text/javascript'>\n";
        echo 'xoopsGetElementById("NewsColorSelect").style.backgroundColor = "#' . $topic_color . '";';
        echo "</script>\n";

        xoops_cp_footer();
        break;
    case 'addTopic':
        addTopic();
        xoops_cp_footer();
        break;
    case 'delTopic':
        delTopic();
        xoops_cp_footer();
        break;
    case 'modTopicS':
        modTopicS();
        xoops_cp_footer();
        break;
    case 'edit':
        xoops_loadLanguage('main', 'xnews');
        require_once XNEWS_MODULE_PATH . '/submit.php';
        xoops_cp_footer();
        break;
    case 'verifydb':
        xoops_cp_header();
        $tbllist = $GLOBALS[Constants::XOOPSDB]->prefix('nw_stories') . ',' . $GLOBALS[Constants::XOOPSDB]->prefix('nw_topics') . ',' . $GLOBALS[Constants::XOOPSDB]->prefix('nw_stories_files') . ',' . $GLOBALS[Constants::XOOPSDB]->prefix('nw_stories_votedata');
        $GLOBALS[Constants::XOOPSDB]->queryF("OPTIMIZE TABLE {$tbllist}");
        $GLOBALS[Constants::XOOPSDB]->queryF("CHECK TABLE {$tbllist}");
        $GLOBALS[Constants::XOOPSDB]->queryF("ANALYZE TABLE {$tbllist}");
        redirect_header('index.php', 3, _AM_XNEWS_DBUPDATED);
        break;
    case 'default':
    default:
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation($currentFile);
        // buttons
        //$adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->addItemButton(_AM_XNEWS_VERIFY_TABLES, $currentFile . '?op=verifydb', 'list');
        $adminObject->displayButton('left', '');

        $adminObject->displayIndex();

        echo $utility::getServerStats();
        require_once __DIR__ . '/admin_footer.php';
        break;
}
