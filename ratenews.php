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

/*
 * Enable users to note a news
 *
 * This page is called from the page "article.php" and "index.php", it
 * enables users to vote for a news, according to the module's option named
 * "ratenews". This code is *heavily* based on the file "ratefile.php" from
 * the mydownloads module.
 * Possible hack : Enable only registred users to vote
 * Notes :
 *            Anonymous users can only vote 1 time per day (except if their IP change)
 *            Author's can't vote for their own news
 *            Registred users can only vote one time
 *
 * @package News
 * @author Xoops Modules Dev Team
 * @copyright    (c) The Xoops Project - www.xoops.org
 *
 * Parameters received by this page :
 * @page_param     int        storyid    Id of the story we are going to vote for
 * @page_param     string    submit    The submit button of the rating form
 * @page_param     int        rating    User's rating
 *
 * @page_title            Story's title - "Rate this news" - Module's name
 *
 * @template_name        xnews_ratenews.tpl
 *
 * Template's variables :
 * @template_var    string    lang_voteonce    Fixed text "Please do not vote for the same resource more than once."
 * @template_var    string    lang_ratingscale    Fixed text "The scale is 1 - 10, with 1 being poor and 10 being excellent."
 * @template_var    string    lang_beobjective    Fixed text "Please be objective, if everyone receives a 1 or a 10, the ratings aren't very useful."
 * @template_var    string    lang_donotvote        Fixed text "Do not vote for your own resource."
 * @template_var    string    lang_rateit            Fixed text "Rate It!"
 * @template_var    string    lang_cancel            Fixed text "Cancel"
 * @template_var    array    news                Contains some information about the story
 *                                    Structure :
 * @template_var                    int        storyid        Story's ID
 * @template_var                    string    title        story's title
 */

use XoopsModules\Xnews;

require_once __DIR__ . '/header.php';
require_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
// require_once XNEWS_MODULE_PATH . '/class/NewsStory.php';

// Verify the perms
// 1) Is the vote activated in the module ?
$ratenews = $helper->getConfig('ratenews');
if (!$ratenews) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _NOPERM);
}

// Limit rating by registred users
if ($cfg['config_rating_registred_only']) {
    if (!isset($xoopsUser) || !is_object($xoopsUser)) {
        redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _NOPERM);
    }
}

// 2) Is the story published ?
$storyid = 0;
if (\Xmf\Request::hasVar('storyid', 'GET')) {
 $storyid =  \Xmf\Request::getInt('storyid', 0, 'GET');
} else {
 if (\Xmf\Request::hasVar('storyid', 'POST')) {
$storyid =  \Xmf\Request::getInt('storyid', 0, 'POST');
}
}

if (!empty($storyid)) {
    $article = new Xnews\NewsStory($storyid);
    if (0 == $article->published() || $article->published() > time()) {
        redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _MD_XNEWS_NOSTORY);
    }

    // Expired
    if (0 != $article->expired() && $article->expired() < time()) {
        redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _MD_XNEWS_NOSTORY);
    }
} else {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _MD_XNEWS_NOSTORY);
}

// 3) Does the user can see this news ? If he can't see it, he can't vote for
$grouppermHandler = xoops_getHandler('groupperm');
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
if (!$grouppermHandler->checkRight('nw_view', $article->topicid(), $groups, $xoopsModule->getVar('mid'))) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _NOPERM);
}

if (!empty($_POST['submit'])) {            // The form was submited
    $eh = new ErrorHandler;             //ErrorHandler object
    if (!is_object($xoopsUser)) {
        $ratinguser = 0;
    } else {
        $ratinguser = $xoopsUser->getVar('uid');
    }

    //Make sure only 1 anonymous from an IP in a single day.
    $anonwaitdays = 1;
    $ip           = getenv('REMOTE_ADDR');
    $storyid      = \Xmf\Request::getInt('storyid', 0, 'POST');
    $rating       = \Xmf\Request::getInt('rating', 0, 'POST');

    // Check if Rating is Null
    if ('--' == $rating) {
        redirect_header(XNEWS_MODULE_URL . '/ratenews.php?storyid=' . $storyid, 3, _MD_XNEWS_NORATING);
    }

    if ($rating < 1 || $rating > 10) {
        die(_ERROR);
    }

    // Check if News POSTER is voting (UNLESS Anonymous users allowed to post)
    if (0 != $ratinguser) {
        $result = $xoopsDB->query('SELECT uid FROM ' . $xoopsDB->prefix('nw_stories') . " WHERE storyid={$storyid}");
        while (false !== (list($ratinguserDB) = $xoopsDB->fetchRow($result))) {
            if ($ratinguserDB == $ratinguser) {
                redirect_header(XNEWS_MODULE_URL . '/article.php?storyid=' . $storyid, 3, _MD_XNEWS_CANTVOTEOWN);
            }
        }

        // Check if REG user is trying to vote twice.
        $result = $xoopsDB->query('SELECT ratinguser FROM ' . $xoopsDB->prefix('nw_stories_votedata') . " WHERE storyid={$storyid}");
        while (false !== (list($ratinguserDB) = $xoopsDB->fetchRow($result))) {
            if ($ratinguserDB == $ratinguser) {
                redirect_header(XNEWS_MODULE_URL . '/article.php?storyid=' . $storyid, 3, _MD_XNEWS_VOTEONCE);
            }
        }
    } else {
        // Check if ANONYMOUS user is trying to vote more than once per day.
        $yesterday = (time() - (86400 * $anonwaitdays));
        $result    = $xoopsDB->query("SELECT COUNT(*) FROM {$xoopsDB->prefix('nw_stories_votedata')} WHERE storyid={$storyid} AND ratinguser=0 AND ratinghostname = '{$ip}' AND ratingtimestamp > {$yesterday}");
        list($anonvotecount) = $xoopsDB->fetchRow($result);
        if ($anonvotecount >= 1) {
            redirect_header(XNEWS_MODULE_URL . '/article.php?storyid=' . $storyid, 3, _MD_XNEWS_VOTEONCE);
        }
    }

    //All is well.  Add to Line Item Rate to DB.
    $newid    = $xoopsDB->genId($xoopsDB->prefix('nw_stories_votedata') . '_ratingid_seq');
    $datetime = time();
    $sql      = sprintf("INSERT INTO %s (ratingid, storyid, ratinguser, rating, ratinghostname, ratingtimestamp) VALUES (%u, %u, %u, %u, '%s', %u)", $xoopsDB->prefix('nw_stories_votedata'), $newid, $storyid, $ratinguser, $rating, $ip, $datetime);
    $xoopsDB->query($sql);// or $eh('0013');

    //All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.
    nw_updaterating($storyid);
    $ratemessage = _MD_XNEWS_VOTEAPPRE . '<br>' . sprintf(_MD_XNEWS_THANKYOU, $xoopsConfig['sitename']);
    redirect_header(XNEWS_MODULE_URL . '/article.php?storyid=' . $storyid, 3, $ratemessage);
} else {        // Display the form to vote
    $GLOBALS['xoopsOption']['template_main'] = 'xnews_ratenews.tpl';
    require_once XOOPS_ROOT_PATH . '/header.php';
    $news = null;
    $news = new Xnews\NewsStory($storyid);
    if (is_object($news)) {
        $title = $news->title('Show');
    } else {
        redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _ERRORS);
    }
    //DNPROSSI - ADDED
    $xoopsTpl->assign('newsmodule_url', XNEWS_MODULE_URL);

    $xoopsTpl->assign('advertisement', $helper->getConfig('advertisement'));
    $xoopsTpl->assign('news', ['storyid' => $storyid, 'title' => $title]);
    $xoopsTpl->assign('lang_voteonce', _MD_XNEWS_VOTEONCE);
    $xoopsTpl->assign('lang_ratingscale', _MD_XNEWS_RATINGSCALE);
    $xoopsTpl->assign('lang_beobjective', _MD_XNEWS_BEOBJECTIVE);
    $xoopsTpl->assign('lang_donotvote', _MD_XNEWS_DONOTVOTE);
    $xoopsTpl->assign('lang_rateit', _MD_XNEWS_RATEIT);
    $xoopsTpl->assign('lang_cancel', _CANCEL);
    $xoopsTpl->assign('xoops_pagetitle', $title . ' - ' . _MD_XNEWS_RATETHISNEWS . ' - ' . $myts->htmlSpecialChars($xoopsModule->name()));
    nw_CreateMetaDatas();
    require_once XOOPS_ROOT_PATH . '/footer.php';
}
require_once XOOPS_ROOT_PATH . '/footer.php';
