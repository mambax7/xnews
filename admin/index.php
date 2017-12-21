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

$myts        = \MyTextSanitizer::getInstance();
$topicscount = 0;

$storiesTableName = $GLOBALS['xoopsDB']->prefix('nw_stories');
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
    $xnews               = XnewsXnews::getInstance();
    $nw_NewsStoryHandler = new nw_NewsStory();
    //
    $start       = isset($_GET['startnew']) ? (int)$_GET['startnew'] : 0;
    $newsubcount = $nw_NewsStoryHandler->getAllStoriesCount(3, false);
    $storyarray  = $nw_NewsStoryHandler->getAllSubmitted($xnews->getConfig('storycountadmin'), true, $xnews->getConfig('restrictindex'), $start);
    if (count($storyarray) > 0) {
        $pagenav = new XoopsPageNav($newsubcount, $xnews->getConfig('storycountadmin'), $start, 'startnew', 'op=newarticle');
        xnews_collapsableBar('newsub', 'topnewsubicon');
        echo "<img onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='topnewsubicon' name='topnewsubicon' src='" . XNEWS_MODULE_URL . "/assets/images/close12.gif' alt=''></a>&nbsp;" . _AM_NW_NEWSUB . '</h4>';
        echo "<div id='newsub'>";
        echo '<br>';
        echo "<div style='text-align: center;'><table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr class='bg3'><td align='center'>"
             . _AM_NW_TITLE
             . "</td><td align='center'>"
             . _AM_NW_TOPIC
             . "</td><td align='center'>"
             . _AM_NW_POSTED
             . "</td><td align='center'>"
             . _AM_NW_POSTER
             . "</td><td align='center'>"
             . _AM_NW_ACTION
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
                 . formatTimestamp($newstory->created(), $xnews->getConfig('dateformat'))
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
                 . _AM_NW_DELETE
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
    $xnews               = XnewsXnews::getInstance();
    $nw_NewsStoryHandler = new nw_NewsStory();
    //
    $start        = isset($_GET['startauto']) ? (int)$_GET['startauto'] : 0;
    $storiescount = $nw_NewsStoryHandler->getAllStoriesCount(2, false);
    $storyarray   = $nw_NewsStoryHandler->getAllAutoStory($xnews->getConfig('storycountadmin'), true, $start);
    $class        = '';
    if (count($storyarray) > 0) {
        $pagenav = new XoopsPageNav($storiescount, $xnews->getConfig('storycountadmin'), $start, 'startauto', 'op=newarticle');
        xnews_collapsableBar('autostories', 'topautostories');
        echo "<img onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='topautostories' name='topautostories' src='" . XNEWS_MODULE_URL . "/assets/images/close12.gif' alt=''></a>&nbsp;" . _AM_NW_AUTOARTICLES . '</h4>';
        echo "<div id='autostories'>";
        echo '<br>';
        echo "<div style='text-align: center;'>\n";
        echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr class='bg3'><td align='center'>"
             . _AM_NW_STORYID
             . "</td><td align='center'>"
             . _AM_NW_TITLE
             . "</td><td align='center'>"
             . _AM_NW_TOPIC
             . "</td><td align='center'>"
             . _AM_NW_POSTER
             . "</td><td align='center' class='news'>"
             . _AM_NW_PROGRAMMED
             . "</td><td align='center' class='news'>"
             . _AM_NW_EXPIRED
             . "</td><td align='center'>"
             . _AM_NW_ACTION
             . '</td></tr>';
        foreach ($storyarray as $autostory) {
            $topic  = $autostory->topic();
            $expire = ($autostory->expired() > 0) ? formatTimestamp($autostory->expired(), $xnews->getConfig('dateformat')) : '';
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
                 . formatTimestamp($autostory->published(), $xnews->getConfig('dateformat'))
                 . "</td><td align='center'>"
                 . $expire
                 . "</td><td align='center'><a href='"
                 . XNEWS_MODULE_URL
                 . '/submit.php?returnside=1&amp;op=edit&amp;storyid='
                 . $autostory->storyid()
                 . "'>"
                 . _AM_NW_EDIT
                 . "</a>-<a href='"
                 . XNEWS_MODULE_URL
                 . '/admin/index.php?op=delete&amp;storyid='
                 . $autostory->storyid()
                 . "'>"
                 . _AM_NW_DELETE
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
    $xnews               = XnewsXnews::getInstance();
    $nw_NewsStoryHandler = new nw_NewsStory();
    //
    xnews_collapsableBar('laststories', 'toplaststories');
    echo "<img onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='toplaststories' name='toplaststories' src='" . XNEWS_MODULE_URL . "/assets/images/close12.gif' alt=''></a>&nbsp;" . sprintf(_AM_NW_LAST10ARTS, $xnews->getConfig('storycountadmin')) . '</h4>';
    echo "<div id='laststories'>";
    echo '<br>';
    echo "<div style='text-align: center;'>";
    $start        = isset($_GET['start']) ? (int)$_GET['start'] : 0;
    $storyarray   = $nw_NewsStoryHandler->getAllPublished($xnews->getConfig('storycountadmin'), $start, false, 0, 1);
    $storiescount = $nw_NewsStoryHandler->getAllStoriesCount(4, false);
    $pagenav      = new XoopsPageNav($storiescount, $xnews->getConfig('storycountadmin'), $start, 'start', 'op=newarticle');
    $class        = '';
    echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr class='bg3'><td align='center'>"
         . _AM_NW_STORYID
         . "</td><td align='center'>"
         . _AM_NW_TITLE
         . "</td><td align='center'>"
         . _AM_NW_TOPIC
         . "</td><td align='center'>"
         . _AM_NW_POSTER
         . "</td><td align='center' class='news'>"
         . _AM_NW_PUBLISHED
         . "</td><td align='center' class='news'>"
         . _AM_NW_HITS
         . "</td><td align='center'>"
         . _AM_NW_ACTION
         . '</td></tr>';
    foreach ($storyarray as $eachstory) {
        $published = formatTimestamp($eachstory->published(), $xnews->getConfig('dateformat'));
        // $expired = ( $eachstory -> expired() > 0) ? formatTimestamp($eachstory->expired(), $xnews->getConfig('dateformat')) : '---';
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
             . _AM_NW_EDIT
             . "</a>-<a href='"
             . XNEWS_MODULE_URL
             . '/admin/index.php?op=delete&amp;storyid='
             . $eachstory->storyid()
             . "'>"
             . _AM_NW_DELETE
             . '</a>';
        echo "</td></tr>\n";
    }
    echo '</table><br>';
    echo "<div align='right'>" . $pagenav->renderNav() . '</div><br>';

    echo "<form action='index.php' method='get'>" . _AM_NW_STORYID . " <input type='text' name='storyid' size='10'>
        <select name='op'>
            <option value='edit' selected='selected'>" . _AM_NW_EDIT . "</option>
            <option value='delete'>" . _AM_NW_DELETE . "</option>
        </select>
        <input type='hidden' name='returnside' value='1'>
        <input type='submit' value='" . _AM_NW_GO . "'>
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
    $xnews               = XnewsXnews::getInstance();
    $nw_NewsStoryHandler = new nw_NewsStory();
    //
    $start        = isset($_GET['startexp']) ? (int)$_GET['startexp'] : 0;
    $expiredcount = $nw_NewsStoryHandler->getAllStoriesCount(1, false);
    $storyarray   = $nw_NewsStoryHandler->getAllExpired($xnews->getConfig('storycountadmin'), $start, 0, 1);
    $pagenav      = new XoopsPageNav($expiredcount, $xnews->getConfig('storycountadmin'), $start, 'startexp', 'op=newarticle');

    if (count($storyarray) > 0) {
        $class = '';
        xnews_collapsableBar('expstories', 'topexpstories');
        echo "<img onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='topexpstories' name='topexpstories' src='" . XNEWS_MODULE_URL . "/assets/images/close12.gif' alt=''></a>&nbsp;" . _AM_NW_EXPARTS . '</h4>';
        echo "<div id='expstories'>";
        echo '<br>';
        echo "<div style='text-align: center;'>";
        echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr class='bg3'><td align='center'>"
             . _AM_NW_STORYID
             . "</td><td align='center'>"
             . _AM_NW_TITLE
             . "</td><td align='center'>"
             . _AM_NW_TOPIC
             . "</td><td align='center'>"
             . _AM_NW_POSTER
             . "</td><td align='center' class='news'>"
             . _AM_NW_CREATED
             . "</td><td align='center' class='news'>"
             . _AM_NW_EXPIRED
             . "</td><td align='center'>"
             . _AM_NW_ACTION
             . '</td></tr>';
        foreach ($storyarray as $eachstory) {
            $created = formatTimestamp($eachstory->created(), $xnews->getConfig('dateformat'));
            $expired = formatTimestamp($eachstory->expired(), $xnews->getConfig('dateformat'));
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
                 . _AM_NW_EDIT
                 . "</a>-<a href='"
                 . XNEWS_MODULE_URL
                 . '/admin/index.php?op=delete&amp;storyid='
                 . $eachstory->storyid()
                 . "'>"
                 . _AM_NW_DELETE
                 . '</a>';
            echo "</td></tr>\n";
        }
        echo '</table><br>';
        echo "<div align='right'>" . $pagenav->renderNav() . '</div><br>';
        echo "<form action='index.php' method='get'>
            " . _AM_NW_STORYID . " <input type='text' name='storyid' size='10'>
            <select name='op'>
                <option value='edit' selected='selected'>" . _AM_NW_EDIT . "</option>
                <option value='delete'>" . _AM_NW_DELETE . "</option>
            </select>
            <input type='hidden' name='returnside' value='1'>
            <input type='submit' value='" . _AM_NW_GO . "'>
            </form>
        </div>";
        echo '</div><br>';
    }
}

// Save a topic after it has been modified
function modTopicS()
{
    $xnews               = XnewsXnews::getInstance();
    $nw_NewsStoryHandler = new nw_NewsStory();
    //
    $xt = new nw_NewsTopic((int)$_POST['topic_id']);
    if ((int)$_POST['topic_pid'] == (int)$_POST['topic_id']) {
        redirect_header('index.php?op=topicsmanager', 3, _AM_NW_ADD_TOPIC_ERROR1);
    }
    $xt->setTopicPid((int)$_POST['topic_pid']);
    if (empty($_POST['topic_title'])) {
        redirect_header('index.php?op=topicsmanager', 3, _AM_NW_ERRORTOPICNAME);
    }
    if (isset($_SESSION['items_count'])) {
        $_SESSION['items_count'] = -1;
    }
    $xt->setTopicTitle($_POST['topic_title']);
    if (isset($_POST['topic_imgurl']) && '' != $_POST['topic_imgurl']) {
        $xt->setTopicImgurl($_POST['topic_imgurl']);
    }
    $xt->setMenu((int)$_POST['submenu']);
    $xt->setTopicFrontpage((int)$_POST['topic_frontpage']);
    if (isset($_POST['topic_description'])) {
        $xt->setTopicDescription($_POST['topic_description']);
    } else {
        $xt->setTopicDescription('');
    }
    //$xt->Settopic_rssurl($_POST['topic_rssfeed']);
    $xt->setTopic_color($_POST['topic_color']);

    if (isset($_POST['xoops_upload_file'])) {
        $fldname = $_FILES[$_POST['xoops_upload_file'][0]];
        $fldname = get_magic_quotes_gpc() ? stripslashes($fldname['name']) : $fldname['name'];
        if (xoops_trim('' != $fldname)) {
            $sfiles         = new nw_sFiles();
            $dstpath        = XNEWS_TOPICS_FILES_PATH;
            $destname       = $sfiles->createUploadName($dstpath, $fldname, true);
            $permittedtypes = ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'];
            $uploader       = new XoopsMediaUploader($dstpath, $permittedtypes, $xnews->getConfig('maxuploadsize'));
            $uploader->setTargetFileName($destname);
            if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                if ($uploader->upload()) {
                    $xt->setTopicImgurl(basename($destname));
                } else {
                    echo _AM_NW_UPLOAD_ERROR . ' ' . $uploader->getErrors();
                }
            } else {
                echo $uploader->getErrors();
            }
        }
    }
    $xt->store();

    // Permissions
    $gpermHandler = xoops_getHandler('groupperm');
    $criteria     = new CriteriaCompo();
    $criteria->add(new Criteria('gperm_itemid', $xt->topic_id(), '='));
    $criteria->add(new Criteria('gperm_modid', $xnews->getModule()->getVar('mid'), '='));
    $criteria->add(new Criteria('gperm_name', 'nw_approve', '='));
    $gpermHandler->deleteAll($criteria);
    //
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('gperm_itemid', $xt->topic_id(), '='));
    $criteria->add(new Criteria('gperm_modid', $xnews->getModule()->getVar('mid'), '='));
    $criteria->add(new Criteria('gperm_name', 'nw_submit', '='));
    $gpermHandler->deleteAll($criteria);
    //
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('gperm_itemid', $xt->topic_id(), '='));
    $criteria->add(new Criteria('gperm_modid', $xnews->getModule()->getVar('mid'), '='));
    $criteria->add(new Criteria('gperm_name', 'nw_view', '='));
    $gpermHandler->deleteAll($criteria);
    //
    if (isset($_POST['groups_news_can_approve'])) {
        foreach ($_POST['groups_news_can_approve'] as $onegroup_id) {
            $gpermHandler->addRight('nw_approve', $xt->topic_id(), $onegroup_id, $xnews->getModule()->getVar('mid'));
        }
    }
    //
    if (isset($_POST['groups_news_can_submit'])) {
        foreach ($_POST['groups_news_can_submit'] as $onegroup_id) {
            $gpermHandler->addRight('nw_submit', $xt->topic_id(), $onegroup_id, $xnews->getModule()->getVar('mid'));
        }
    }
    //
    if (isset($_POST['groups_news_can_view'])) {
        foreach ($_POST['groups_news_can_view'] as $onegroup_id) {
            $gpermHandler->addRight('nw_view', $xt->topic_id(), $onegroup_id, $xnews->getModule()->getVar('mid'));
        }
    }
    //
    nw_updateCache();
    redirect_header('index.php?op=topicsmanager', 3, _AM_NW_DBUPDATED);
}

// Delete a topic and its subtopics and its stories and the related stories
function delTopic()
{
    $xnews               = XnewsXnews::getInstance();
    $nw_NewsStoryHandler = new nw_NewsStory();
    //
    if (!isset($_POST['ok'])) {
        xoops_cp_header();
        echo '<h2>' . _AM_NW_TOPICSMNGR . '</h2>';
        $xt = new XnewsDeprecateTopic($GLOBALS['xoopsDB']->prefix('nw_topics'), (int)$_GET['topic_id']);
        xoops_confirm(['op' => 'delTopic', 'topic_id' => (int)$_GET['topic_id'], 'ok' => 1], 'index.php', _AM_NW_WAYSYWTDTTAL . '<br>' . $xt->topic_title('S'));
    } else {
        xoops_cp_header();
        $xt = new XnewsDeprecateTopic($GLOBALS['xoopsDB']->prefix('nw_topics'), (int)$_POST['topic_id']);
        if (isset($_SESSION['items_count'])) {
            $_SESSION['items_count'] = -1;
        }
        // get all subtopics under the specified topic
        $topic_arr = $xt->getAllChildTopics();
        array_push($topic_arr, $xt);
        foreach ($topic_arr as $eachtopic) {
            // get all stories in each topic
            $story_arr = nw_NewsStory:: getByTopic($eachtopic->topic_id());
            foreach ($story_arr as $eachstory) {
                if (false !== $eachstory->delete()) {
                    xoops_comment_delete($xnews->getModule()->getVar('mid'), $eachstory->storyid());
                    xoops_notification_deletebyitem($xnews->getModule()->getVar('mid'), 'story', $eachstory->storyid());
                }
            }
            // all stories for each topic is deleted, now delete the topic data
            $eachtopic->delete();
            // Delete also the notifications and permissions
            xoops_notification_deletebyitem($xnews->getModule()->getVar('mid'), 'category', $eachtopic->topic_id);
            xoops_groupperm_deletebymoditem($xnews->getModule()->getVar('mid'), 'nw_approve', $eachtopic->topic_id);
            xoops_groupperm_deletebymoditem($xnews->getModule()->getVar('mid'), 'nw_submit', $eachtopic->topic_id);
            xoops_groupperm_deletebymoditem($xnews->getModule()->getVar('mid'), 'nw_view', $eachtopic->topic_id);
        }
        nw_updateCache();
        redirect_header('index.php?op=topicsmanager', 3, _AM_NW_DBUPDATED);
    }
}

// Add a new topic
function addTopic()
{
    $xnews               = XnewsXnews::getInstance();
    $nw_NewsStoryHandler = new nw_NewsStory();
    //
    $topicpid = isset($_POST['topic_pid']) ? (int)$_POST['topic_pid'] : 0;
    $xt       = new nw_NewsTopic();
    if (!$xt->topicExists($topicpid, $_POST['topic_title'])) {
        $xt->setTopicPid($topicpid);
        if (empty($_POST['topic_title']) || '' == xoops_trim($_POST['topic_title'])) {
            redirect_header('index.php?op=topicsmanager', 3, _AM_NW_ERRORTOPICNAME);
        }
        $xt->setTopicTitle($_POST['topic_title']);
        //$xt->Settopic_rssurl($_POST['topic_rssfeed']);
        $xt->setTopic_color($_POST['topic_color']);
        if (isset($_POST['topic_imgurl']) && '' != $_POST['topic_imgurl']) {
            $xt->setTopicImgurl($_POST['topic_imgurl']);
        }
        $xt->setMenu((int)$_POST['submenu']);
        $xt->setTopicFrontpage((int)$_POST['topic_frontpage']);
        if (isset($_SESSION['items_count'])) {
            $_SESSION['items_count'] = -1;
        }
        if (isset($_POST['xoops_upload_file'])) {
            $fldname = $_FILES[$_POST['xoops_upload_file'][0]];
            $fldname = get_magic_quotes_gpc() ? stripslashes($fldname['name']) : $fldname['name'];
            if (xoops_trim('' != $fldname)) {
                $sfiles         = new nw_sFiles();
                $dstpath        = XNEWS_TOPICS_FILES_PATH;
                $destname       = $sfiles->createUploadName($dstpath, $fldname, true);
                $permittedtypes = ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'];
                $uploader       = new XoopsMediaUploader($dstpath, $permittedtypes, $xnews->getConfig('maxuploadsize'));
                $uploader->setTargetFileName($destname);
                if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                    if ($uploader->upload()) {
                        $xt->setTopicImgurl(basename($destname));
                    } else {
                        echo _AM_NW_UPLOAD_ERROR . ' ' . $uploader->getErrors();
                    }
                } else {
                    echo $uploader->getErrors();
                }
            }
        }
        if (isset($_POST['topic_description'])) {
            $xt->setTopicDescription($_POST['topic_description']);
        } else {
            $xt->setTopicDescription('');
        }
        $xt->store();
        // Permissions
        $gpermHandler = xoops_getHandler('groupperm');
        if (isset($_POST['groups_news_can_approve'])) {
            foreach ($_POST['groups_news_can_approve'] as $onegroup_id) {
                $gpermHandler->addRight('nw_approve', $xt->topic_id(), $onegroup_id, $xnews->getModule()->getVar('mid'));
            }
        }

        if (isset($_POST['groups_news_can_submit'])) {
            foreach ($_POST['groups_news_can_submit'] as $onegroup_id) {
                $gpermHandler->addRight('nw_submit', $xt->topic_id(), $onegroup_id, $xnews->getModule()->getVar('mid'));
            }
        }

        if (isset($_POST['groups_news_can_view'])) {
            foreach ($_POST['groups_news_can_view'] as $onegroup_id) {
                $gpermHandler->addRight('nw_view', $xt->topic_id(), $onegroup_id, $xnews->getModule()->getVar('mid'));
            }
        }
        nw_updateCache();

        $notificationHandler = xoops_getHandler('notification');
        $tags                = [];
        $tags['TOPIC_NAME']  = $_POST['topic_title'];
        $notificationHandler->triggerEvent('global', 0, 'new_category', $tags);
        redirect_header('index.php?op=topicsmanager', 3, _AM_NW_DBUPDATED);
    } else {
        redirect_header('index.php?op=topicsmanager', 3, _AM_NW_ADD_TOPIC_ERROR);
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
        //
        if ('newsletter' === $_GET['type']) {
            $newsfile = XOOPS_ROOT_PATH . '/uploads/newsletter.txt';
            if (unlink($newsfile)) {
                redirect_header('index.php', 3, _AM_NW_DELETED_OK);
            } else {
                redirect_header('index.php', 3, _AM_NW_DELETED_PB);
            }
        } else {
            if ('xml' === $_GET['type']) {
                $xmlfile = XOOPS_ROOT_PATH . '/uploads/nw_stories.xml';
                if (unlink($xmlfile)) {
                    redirect_header('index.php', 3, _AM_NW_DELETED_OK);
                } else {
                    redirect_header('index.php', 3, _AM_NW_DELETED_PB);
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
        echo '<h4>' . _AM_NW_POSTNEWARTICLE . '</h4>';
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

        if (1 == $xnews->getConfig('autoapprove')) {
            $approve = 1;
        }
        $approveprivilege = 1;
        require_once XNEWS_MODULE_PATH . '/include/storyform.original.php';
        xoops_cp_footer();
        break;

    case 'delete':
        if (isset($_REQUEST['storyid'])) {
            $storyid = (int)$_REQUEST['storyid'];
        } else {
            $storyid = 0;
        }
        if (!empty($_POST['ok'])) {
            if (empty($storyid)) {
                redirect_header('index.php?op=newarticle', 3, _AM_NW_EMPTYNODELETE);
            }
            $story = new nw_NewsStory($storyid);
            $story->delete();
            $sfiles   = new nw_sFiles();
            $filesarr = [];
            $filesarr = $sfiles->getAllbyStory($storyid);
            if (count($filesarr) > 0) {
                foreach ($filesarr as $onefile) {
                    $onefile->delete();
                }
            }
            xoops_comment_delete($xnews->getModule()->getVar('mid'), $storyid);
            xoops_notification_deletebyitem($xnews->getModule()->getVar('mid'), 'story', $storyid);
            nw_updateCache();
            redirect_header('index.php?op=newarticle', 3, _AM_NW_DBUPDATED);
        } else {
            $story = new nw_NewsStory($storyid);
            xoops_cp_header();
            echo '<h4>' . _AM_NW_CONFIG . '</h4>';
            xoops_confirm(['op' => 'delete', 'storyid' => $storyid, 'ok' => 1], 'index.php', _AM_NW_RUSUREDEL . '<br>' . $story->title());
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
        //
        xoops_load('XoopsFormLoader');

        $uploadfolder   = sprintf(_AM_NW_UPLOAD_WARNING, XNEWS_TOPICS_FILES_URL);
        $uploadirectory = '/uploads/' . $xnews->getModule()->dirname() . '/assets/images/topics';
        $start          = isset($_GET['start']) ? (int)$_GET['start'] : 0;

        $xt          = new XoopsTree($GLOBALS['xoopsDB']->prefix('nw_topics'), 'topic_id', 'topic_pid');
        $topics_arr  = $xt->getChildTreeArray(0, 'topic_title');
        $totaltopics = count($topics_arr);
        $class       = '';

        xnews_collapsableBar('topicsmanager', 'toptopicsmanager');
        echo "<img onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='toptopicsmanager' name='toptopicsmanager' src='" . XNEWS_MODULE_URL . "/assets/images/close12.gif' alt=''></a>&nbsp;" . _AM_NW_TOPICS . ' (' . $totaltopics . ')' . '</h4>';
        echo "<div id='topicsmanager'>";
        echo '<br>';
        echo "<div style='text-align: center;'>";
        echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr class='bg3'><td align='center'>"
             . _AM_NW_TOPIC
             . "</td><td align='left'>"
             . _AM_NW_TOPICNAME
             . "</td><td align='center'>"
             . _AM_NW_PARENTTOPIC
             . "</td><td align='center'>"
             . _AM_NW_SUB_MENU_YESNO
             . "</td><td align='center'>"
             . _AM_NW_ACTION
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
                    $action     = sprintf("<a href='%s'>%s</a> - <a href='%s'>%s</a>", $linkedit, _AM_NW_EDIT, $linkdelete, _AM_NW_DELETE);
                    $parent     = '&nbsp;';
                    if ($topics_arr[$tmpcpt]['topic_pid'] > 0) {
                        $xttmp  = new XnewsDeprecateTopic($GLOBALS['xoopsDB']->prefix('nw_topics'), $topics_arr[$tmpcpt]['topic_pid']);
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
                if ($cpt >= $xnews->getConfig('storycountadmin')) {
                    $ok = false;
                }
                $tmpcpt++;
                $cpt++;
            }
            echo $output;
        }
        $pagenav = new XoopsPageNav($totaltopics, $xnews->getConfig('storycountadmin'), $start, 'start', 'op=topicsmanager');
        echo "</table><div align='right'>" . $pagenav->renderNav() . '</div><br>';
        echo "</div></div><br>\n";

        $topic_id = isset($_GET['topic_id']) ? (int)$_GET['topic_id'] : 0;
        if ($topic_id > 0) {
            $xtmod             = new nw_NewsTopic($topic_id);
            $topic_title       = $xtmod->topic_title('E');
            $topic_description = $xtmod->topic_description('E');
            $topic_rssfeed     = $xtmod->topic_rssurl('E');
            $op                = 'modTopicS';
            if ('' != xoops_trim($xtmod->topic_imgurl())) {
                $topicimage = $xtmod->topic_imgurl();
            } else {
                $topicimage = 'blank.png';
            }
            $btnlabel        = _AM_NW_MODIFY;
            $parent          = $xtmod->topic_pid();
            $formlabel       = _AM_NW_MODIFYTOPIC;
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
            $btnlabel          = _AM_NW_ADD;
            $parent            = -1;
            $submenu           = 0;
            $topic_rssfeed     = '';
            $formlabel         = _AM_NW_ADD_TOPIC;
            $topic_color       = '000000';
        }

        $sform = new XoopsThemeForm($formlabel, 'topicform', XNEWS_MODULE_URL . '/admin/index.php', 'post', true);
        $sform->setExtra('enctype="multipart/form-data"');
        $sform->addElement(new XoopsFormText(_AM_NW_TOPICNAME, 'topic_title', 50, 255, $topic_title), true);
        $editor = nw_getWysiwygForm(_AM_NW_TOPIC_DESCR, 'topic_description', $topic_description, 15, 60, '100%', '350px', 'hometext_hidden');
        if ($editor) {
            $sform->addElement($editor, false);
        }

        $sform->addElement(new XoopsFormHidden('op', $op), false);
        $sform->addElement(new XoopsFormHidden('topic_id', $topic_id), false);

        require_once XNEWS_MODULE_PATH . '/class/class.newstopic.php';
        $xt = new nw_NewsTopic();
        $sform->addElement(new XoopsFormLabel(_AM_NW_PARENTTOPIC, $xt->MakeMyTopicSelBox(1, $parent, 'topic_pid', '', false)));
        // Topic's color
        // Code stolen to Zoullou, thank you Zoullou ;-)
        $select_color = "\n<select name='topic_color'  onchange='xoopsGetElementById(\"NewsColorSelect\").style.backgroundColor = \"#\" + this.options[this.selectedIndex].value;'>\n<option value='000000'>" . _AM_NW_COLOR . "</option>\n";
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
            'FFFFFF'
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
        $sform->addElement(new XoopsFormLabel(_AM_NW_TOPIC_COLOR, $select_color));
        // Sub menu ?
        $sform->addElement(new XoopsFormRadioYN(_AM_NW_SUB_MENU, 'submenu', $submenu, _YES, _NO));
        $sform->addElement(new XoopsFormRadioYN(_AM_NW_PUBLISH_FRONTPAGE, 'topic_frontpage', $topic_frontpage, _YES, _NO));
        // Unused for this moment... sorry
        //$sform->addElement(new XoopsFormText(_AM_NW_RSS_URL, 'topic_rssfeed', 50, 255, $topic_rssfeed), false);
        // ********** Picture
        $imgtray = new XoopsFormElementTray(_AM_NW_TOPICIMG, '<br>');

        $imgpath      = sprintf(_AM_NW_IMGNAEXLOC, 'uploads/' . $xnews->getModule()->dirname() . '/assets/images/topics/');
        $imageselect  = new XoopsFormSelect($imgpath, 'topic_imgurl', $topicimage);
        $topics_array = XoopsLists::getImgListAsArray(XNEWS_TOPICS_FILES_PATH);
        foreach ($topics_array as $image) {
            $imageselect->addOption("$image", $image);
        }
        $imageselect->setExtra("onchange='showImgSelected(\"image3\", \"topic_imgurl\", \"" . $uploadirectory . '", "", "' . XOOPS_URL . "\")'");
        $imgtray->addElement($imageselect, false);
        $imgtray->addElement(new XoopsFormLabel('', "<br><img src='" . XOOPS_URL . '/' . $uploadirectory . '/' . $topicimage . "' name='image3' id='image3' alt=''>"));

        $uploadfolder = sprintf(_AM_NW_UPLOAD_WARNING, XNEWS_TOPICS_FILES_URL);
        $fileseltray  = new XoopsFormElementTray('', '<br>');
        $fileseltray->addElement(new XoopsFormFile(_AM_NW_TOPIC_PICTURE, 'attachedfile', $xnews->getConfig('maxuploadsize')), false);
        $fileseltray->addElement(new XoopsFormLabel($uploadfolder), false);
        $imgtray->addElement($fileseltray);
        $sform->addElement($imgtray);

        // Permissions
        $memberHandler = xoops_getHandler('member');
        $group_list    = $memberHandler->getGroupList();
        $gpermHandler  = xoops_getHandler('groupperm');
        $full_list     = array_keys($group_list);

        $groups_ids = [];
        if ($topic_id > 0) { // Edit mode
            $groups_ids                       = $gpermHandler->getGroupIds('nw_approve', $topic_id, $xnews->getModule()->getVar('mid'));
            $groups_ids                       = array_values($groups_ids);
            $groups_news_can_approve_checkbox = new XoopsFormCheckBox(_AM_NW_APPROVEFORM, 'groups_news_can_approve[]', $groups_ids);
        } else {    // Creation mode
            $groups_news_can_approve_checkbox = new XoopsFormCheckBox(_AM_NW_APPROVEFORM, 'groups_news_can_approve[]', $full_list);
        }
        $groups_news_can_approve_checkbox->addOptionArray($group_list);
        $sform->addElement($groups_news_can_approve_checkbox);

        $groups_ids = [];
        if ($topic_id > 0) { // Edit mode
            $groups_ids                      = $gpermHandler->getGroupIds('nw_submit', $topic_id, $xnews->getModule()->getVar('mid'));
            $groups_ids                      = array_values($groups_ids);
            $groups_news_can_submit_checkbox = new XoopsFormCheckBox(_AM_NW_SUBMITFORM, 'groups_news_can_submit[]', $groups_ids);
        } else {    // Creation mode
            $groups_news_can_submit_checkbox = new XoopsFormCheckBox(_AM_NW_SUBMITFORM, 'groups_news_can_submit[]', $full_list);
        }
        $groups_news_can_submit_checkbox->addOptionArray($group_list);
        $sform->addElement($groups_news_can_submit_checkbox);

        $groups_ids = [];
        if ($topic_id > 0) {        // Edit mode
            $groups_ids                    = $gpermHandler->getGroupIds('nw_view', $topic_id, $xnews->getModule()->getVar('mid'));
            $groups_ids                    = array_values($groups_ids);
            $groups_news_can_view_checkbox = new XoopsFormCheckBox(_AM_NW_VIEWFORM, 'groups_news_can_view[]', $groups_ids);
        } else {    // Creation mode
            $groups_news_can_view_checkbox = new XoopsFormCheckBox(_AM_NW_VIEWFORM, 'groups_news_can_view[]', $full_list);
        }
        $groups_news_can_view_checkbox->addOptionArray($group_list);
        $sform->addElement($groups_news_can_view_checkbox);

        // Submit buttons
        $button_tray = new XoopsFormElementTray('', '');
        $submit_btn  = new XoopsFormButton('', 'post', $btnlabel, 'submit');
        $button_tray->addElement($submit_btn);
        $sform->addElement($button_tray);
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
        $tbllist = $GLOBALS['xoopsDB']->prefix('nw_stories') . ',' . $GLOBALS['xoopsDB']->prefix('nw_topics') . ',' . $GLOBALS['xoopsDB']->prefix('nw_stories_files') . ',' . $GLOBALS['xoopsDB']->prefix('nw_stories_votedata');
        $GLOBALS['xoopsDB']->queryF("OPTIMIZE TABLE {$tbllist}");
        $GLOBALS['xoopsDB']->queryF("CHECK TABLE {$tbllist}");
        $GLOBALS['xoopsDB']->queryF("ANALYZE TABLE {$tbllist}");
        redirect_header('index.php', 3, _AM_NW_DBUPDATED);
        break;

    case 'default':
    default:
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation($currentFile);
        // buttons
        //$adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->addItemButton(_AM_NW_VERIFY_TABLES, $currentFile . '?op=verifydb', 'list');
        $adminObject->displayButton('left', '');

        $adminObject->displayIndex();

        xoops_cp_footer();
        break;
}
