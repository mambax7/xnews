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

use Xmf\Request;
use XoopsModules\Xnews;

$currentFile = basename(__FILE__);
require_once __DIR__ . '/admin_header.php';

// require_once XNEWS_MODULE_PATH . '/class/deprecate/xnewstopic.php';
require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';

// require_once XNEWS_MODULE_PATH . '/class/NewsStory.php';
// require_once XNEWS_MODULE_PATH . '/class/NewsTopic.php';
// require_once XNEWS_MODULE_PATH . '/class/Files.php';
// require_once XNEWS_MODULE_PATH . '/class/blacklist.php';
// require_once XNEWS_MODULE_PATH . '/class/registryfile.php';

require_once XOOPS_ROOT_PATH . '/class/uploader.php';
xoops_load('xoopspagenav');
require_once XOOPS_ROOT_PATH . '/class/tree.php';

$myts        = \MyTextSanitizer::getInstance();
$topicscount = 0;

$storiesTableName = $GLOBALS['xoopsDB']->prefix('nw_stories');
if (!nw_FieldExists('picture', $storiesTableName)) {
    nw_AddField('`picture` VARCHAR( 50 ) NOT NULL', $storiesTableName);
}

$newsStoryHandler = new Xnews\NewsStory();

/**
 * Delete (purge/prune) old stories
 *
 * You can use this function in the module's admin when you click on the tab named "Prune News"
 * It's useful to remove old stories. It is, of course, recommended
 * to backup (or export) your news before to purge news.
 * You must first specify a date. This date will be used as a reference, everything
 * that was published before this date will be deleted.
 * The option "Only remove stories who have expired" will enable you to only remove
 * expired stories published before the given date.
 * Finally, you can select the topics inside wich you will remove news.
 * Once you have set all the parameters, the script will first show you a confirmation's
 * message with the number of news that will be removed.
 * Note, the topics are not deleted (even if there are no more news inside them).
 */

$op = Request::getString('op', 'default');

switch ($op) {
    default:
    case 'prune':
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation($currentFile);
        //
        require_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        $sform = new \XoopsThemeForm(_AM_XNEWS_PRUNENEWS, 'pruneform', $currentFile, 'post', true);
        $sform->addElement(new \XoopsFormTextDateSelect(_AM_XNEWS_PRUNE_BEFORE, 'prune_date', 15, time()), true);
        $onlyexpired = new \XoopsFormCheckBox('', 'onlyexpired');
        $onlyexpired->addOption(1, _AM_XNEWS_PRUNE_EXPIREDONLY);
        $sform->addElement($onlyexpired, false);
        $sform->addElement(new \XoopsFormHidden('op', 'confirmbeforetoprune'), false);
        $topiclist  = new \XoopsFormSelect(_AM_XNEWS_PRUNE_TOPICS, 'pruned_topics', '', 5, true);
        $topics_arr = [];
        $xt         = new Xnews\NewsTopic();
        $allTopics  = $xt->getAllTopics(false); // The webmaster can see everything
        $topic_tree = new \XoopsObjectTree($allTopics, 'topic_id', 'topic_pid');
        $topics_arr = $topic_tree->getAllChild(0);
        if (count($topics_arr)) {
            foreach ($topics_arr as $onetopic) {
                $topiclist->addOption($onetopic->topic_id(), $onetopic->topic_title());
            }
        }
        $topiclist->setDescription(_AM_XNEWS_EXPORT_PRUNE_DSC);
        $sform->addElement($topiclist, false);
        $button_tray = new \XoopsFormElementTray('', '');
        $submit_btn  = new \XoopsFormButton('', 'post', _SUBMIT, 'submit');
        $button_tray->addElement($submit_btn);
        $sform->addElement($button_tray);
        $sform->display();
        xoops_cp_footer();
        break;

    case 'confirmbeforetoprune':
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation($currentFile);
        //
        $story     = new Xnews\NewsStory();
        $topiclist = '';
        if (isset($_POST['pruned_topics'])) {
            $topiclist = implode(',', $_POST['pruned_topics']);
        }

        $expired = \Xmf\Request::getInt('onlyexpired', 0, 'POST');

        $dateTimeObj = DateTime::createFromFormat(_SHORTDATESTRING, $_POST['prune_date']);
        $dateTimeObj->setTime(0, 0, 0);
        $timestamp = $dateTimeObj->getTimestamp();
        unset($dateTimeObj);
        $count = $story->GetCountStoriesPublishedBefore($timestamp, $expired, $topiclist);
        if ($count) {
            $displaydate = formatTimestamp($timestamp, $helper->getConfig('dateformat'));
            $msg         = sprintf(_AM_XNEWS_PRUNE_CONFIRM, $displaydate, $count);
            xoops_confirm(['op' => 'prunenews', 'expired' => $expired, 'pruned_topics' => $topiclist, 'prune_date' => $timestamp, 'ok' => 1], 'index.php', $msg);
        } else {
            printf(_AM_XNEWS_NOTHING_PRUNE);
        }
        unset($story);
        xoops_cp_footer();
        break;

    case 'prunenews':
        $story     = new Xnews\NewsStory();
        $timestamp = \Xmf\Request::getInt('prune_date', 0, 'POST');
        $expired   = \Xmf\Request::getInt('expired', 0, 'POST');
        $topiclist = '';
        if (isset($_POST['pruned_topics'])) {
            $topiclist = $_POST['pruned_topics'];
        }
        if (1 == \Xmf\Request::getInt('ok', 0, 'POST')) {
            $story = new Xnews\NewsStory();
            xoops_cp_header();
            $count = $story->GetCountStoriesPublishedBefore($timestamp, $expired, $topiclist);
            $msg   = sprintf(_AM_XNEWS_PRUNE_DELETED, $count);
            $story->DeleteBeforeDate($timestamp, $expired, $topiclist);
            unset($story);
            nw_updateCache();
            redirect_header('index.php', 3, $msg);
        }
        xoops_cp_footer();
        break;
}
