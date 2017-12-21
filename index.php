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

/**
 * Module's index
 *
 * This page displays a list of the published articles and can also display the
 * stories of a particular topic.
 *
 * @package                     News
 * @author                      Xoops Modules Dev Team
 * @copyright (c)               The Xoops Project - www.xoops.org
 *
 * Parameters received by this page :
 * @page_param                  int              topic_id Topic's ID
 * @page_param                  int              storynum Number of news per page
 * @page_param                  int              start   First news to display
 *
 * @page_title                  Topic's title - Story's title - Module's name
 *
 * @template_name         nw_news_index.tpl or nw_news_by_topic.tpl
 *
 * Template's variables :
 * For each article
 * @template_var                int            id story's ID
 * @template_var                string         poster Complete link to the author's profile
 * @template_var                string         author_name Author's name according to the module's option called displayname
 * @template_var                int            author_uid Author's ID
 * @template_var                float          rating New's rating
 * @template_var                int            votes number of votes
 * @template_var                int            posttimestamp Timestamp representing the published date
 * @template_var                string         posttime Formated published date
 * @template_var                string         text The introduction's text
 * @template_var                string         morelink The link to read the full article (points to article.php)
 * @template_var                string         adminlink Link reserved to the admin to edit and delete the news
 * @template_var                string         mail_link Link used to send the story's url by email
 * @template_var                string         title Story's title presented on the form of a link
 * @template_varstring          news_title Just the news title
 * @template_var                string         topic_title Just the topic's title
 * @template_var                int            hits Number of times the article was read
 * @template_var                int            files_attached Number of files attached to this news
 * @template_var                string         attached_link An URL pointing to the attached files
 * @template_var                string topic_color The topic's color
 * @template_var                int            column_width column's width
 * @template_var                int            displaynav To know if we must display the navigation's box
 * @template_var                string         lang_go fixed text : Go!
 * @template_var                string         lang_morereleases fixed text : More releases in
 * @template_var                string         lang_on fixed text : on
 * @template_var                string         lang_postedby fixed text : Posted by
 * @template_var                string         lang_printerpage fixed text : Printer Friendly Page
 * @template_var                string         lang_ratethisnews fixed text : Rate this News
 * @template_var                string         lang_ratingc fixed text : Rating:
 * @template_var                string         lang_reads fixed text : reads
 * @template_var                string         lang_sendstory fixed text : Send this Story to a Friend
 * @template_var                string         topic_select contains the topics selector
 */
require_once __DIR__ . '/header.php';

require_once XNEWS_MODULE_PATH . '/class/class.newsstory.php';
require_once XNEWS_MODULE_PATH . '/class/class.sfiles.php';
require_once XNEWS_MODULE_PATH . '/class/class.newstopic.php';
require_once XNEWS_MODULE_PATH . '/class/Utility.php';
require_once XOOPS_ROOT_PATH . '/class/tree.php';

$nw_NewsStoryHandler = new nw_NewsStory();

$topic_id = 0;
if (isset($_GET['topic_id'])) {
    $topic_id = (int)$_GET['topic_id'];
}

if ($topic_id) {
    $groups       = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gpermHandler = xoops_getHandler('groupperm');
    if (!$gpermHandler->checkRight('nw_view', $topic_id, $groups, $xnews->getModule()->getVar('mid'))) {
        redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _NOPERM);
    }
    $xoopsOption['storytopic'] = $topic_id;
} else {
    $xoopsOption['storytopic'] = 0;
}
if (isset($_GET['storynum'])) {
    $xoopsOption['storynum'] = (int)$_GET['storynum'];
    if ($xoopsOption['storynum'] > 30) {
        $xoopsOption['storynum'] = $xnews->getConfig('storyhome');
    }
} else {
    $xoopsOption['storynum'] = $xnews->getConfig('storyhome');
}

if (isset($_GET['start'])) {
    $start = (int)$_GET['start'];
} else {
    $start = 0;
}

if ('Classic' === $xnews->getConfig('newsdisplay') || $xoopsOption['storytopic'] > 0) {
    $showclassic = 1;
} else {
    $showclassic = 0;
}

$firsttitle = '';
$topictitle = '';
$myts       = \MyTextSanitizer::getInstance();
$sfiles     = new nw_sFiles();

$column_count = $xnews->getConfig('columnmode');

if ($showclassic) {
    $GLOBALS['xoopsOption']['template_main'] = 'nw_news_index.tpl';
    require_once XOOPS_ROOT_PATH . '/header.php';
    $xt = new nw_NewsTopic();
    //DNPROSSI - ADDED
    $xoopsTpl->assign('newsmodule_url', XNEWS_MODULE_URL);

    $xoopsTpl->assign('column_width', (int)(1 / $column_count * 100));
    if ($xnews->getConfig('ratenews')) {
        $xoopsTpl->assign('rates', true);
        $xoopsTpl->assign('lang_ratingc', _MA_NW_RATINGC);
        $xoopsTpl->assign('lang_ratethisnews', _MA_NW_RATETHISNEWS);
    } else {
        $xoopsTpl->assign('rates', false);
    }

    if ($xoopsOption['storytopic']) {
        $xt->getTopic($xoopsOption['storytopic']);
        $xoopsTpl->assign('topic_description', $xt->topic_description('S'));
        $xoopsTpl->assign('topic_color', '#' . $xt->topic_color('S'));
        $topictitle = $xt->topic_title();
    }

    if (1 == $xnews->getConfig('displaynav')) {
        $xoopsTpl->assign('displaynav', true);

        $allTopics  = $xt->getAllTopics($xnews->getConfig('restrictindex'));
        $topic_tree = new XoopsObjectTree($allTopics, 'topic_id', 'topic_pid');
        //        $topic_select = $topic_tree->makeSelBox('topic_id', 'topic_title', '-- ', $xoopsOption['storytopic'], true);
        //        $xoopsTpl->assign('topic_select', $topic_select);

        $moduleDirName = basename(__DIR__);
        $helper        = \Xmf\Module\Helper::getHelper($moduleDirName);
        $module        = $helper->getModule();

        if (XNewsUtility::checkVerXoops($module, '2.5.9')) {
            $topic_select = $topic_tree->makeSelectElement('topic_id', 'topic_title', '--', $xoopsOption['storytopic'], true, 0, '', '');
            $xoopsTpl->assign('topic_select', $topic_select->render());
        } else {
            $topic_select = $topic_tree->makeSelBox('topic_id', 'topic_title', '-- ', $xoopsOption['storytopic'], true);
            $xoopsTpl->assign('topic_select', $topic_select);
        }

        $storynum_options = '';
        for ($i = 5; $i <= 30; $i = $i + 5) {
            $sel = '';
            if ($i == $xoopsOption['storynum']) {
                $sel = ' selected="selected"';
            }
            $storynum_options .= '<option value="' . $i . '"' . $sel . '>' . $i . '</option>';
        }
        $xoopsTpl->assign('storynum_options', $storynum_options);
    } else {
        $xoopsTpl->assign('displaynav', false);
    }
    if (0 == $xoopsOption['storytopic']) {
        $topic_frontpage = true;
    } else {
        $topic_frontpage = false;
    }
    $sarray = $nw_NewsStoryHandler->getAllPublished($xoopsOption['storynum'], $start, $xnews->getConfig('restrictindex'), $xoopsOption['storytopic'], 0, true, 'published', $topic_frontpage);

    $scount = count($sarray);
    $xoopsTpl->assign('story_count', $scount);
    $k       = 0;
    $columns = [];
    if ($scount > 0) {
        $storieslist = [];
        foreach ($sarray as $storyid => $thisstory) {
            $storieslist[] = $storyid;
        }
        $filesperstory = $sfiles->getCountbyStories($storieslist);

        foreach ($sarray as $storyid => $thisstory) {
            $filescount = array_key_exists($thisstory->storyid(), $filesperstory) ? $filesperstory[$thisstory->storyid()] : 0;
            $story      = $thisstory->prepare2show($filescount);
            // The line below can be used to display a Permanent Link image
            // $story['title'] .= "&nbsp;&nbsp;<a href='" . XNEWS_MODULE_URL . "/article.php?storyid=" . $sarray[$i]->storyid() . "'><img src='".XNEWS_MODULE_URL . "/assets/images/x.gif' alt='Permanent Link'></a>";
            $story['news_title']      = $thisstory->storylink(); //$story['title'];
            $story['title']           = $thisstory->textlink() . '&nbsp;:&nbsp;' . $story['title'];
            $story['topic_title']     = $thisstory->textlink();
            $story['topic_separator'] = ('' != $thisstory->textlink()) ? _MA_NW_SP : '';
            $story['topic_color']     = '#' . $myts->displayTarea($thisstory->topic_color);
            if ('' == $firsttitle) {
                $firsttitle = $myts->htmlSpecialChars($thisstory->topic_title()) . ' - ' . $myts->htmlSpecialChars($thisstory->title());
            }
            $columns[$k][] = $story;
            $k++;
            if ($k == $column_count) {
                $k = 0;
            }
        }
    }

    $xoopsTpl->assign('columns', $columns);
    unset($story);

    $totalcount = $nw_NewsStoryHandler->countPublishedByTopic($xoopsOption['storytopic'], $xnews->getConfig('restrictindex'));
    if ($totalcount > $scount) {
        xoops_load('xoopspagenav');
        $pagenav = new XoopsPageNav($totalcount, $xoopsOption['storynum'], $start, 'start', 'topic_id=' . $xoopsOption['storytopic']);
        if (nw_isbot()) { // A bot is reading the news, we are going to show it all the links so that he can read everything
            $xoopsTpl->assign('pagenav', $pagenav->renderNav($totalcount));
        } else {
            $xoopsTpl->assign('pagenav', $pagenav->renderNav());
        }
    } else {
        $xoopsTpl->assign('pagenav', '');
    }
} else {    // Affichage par sujets
    $GLOBALS['xoopsOption']['template_main'] = 'nw_news_by_topic.tpl';
    require_once XOOPS_ROOT_PATH . '/header.php';

    //DNPROSSI - ADDED
    $xoopsTpl->assign('newsmodule_url', XNEWS_MODULE_URL);

    $xoopsTpl->assign('column_width', (int)(1 / $column_count * 100));
    if ($xnews->getConfig('ratenews')) {
        $xoopsTpl->assign('rates', true);
        $xoopsTpl->assign('lang_ratingc', _MA_NW_RATINGC);
        $xoopsTpl->assign('lang_ratethisnews', _MA_NW_RATETHISNEWS);
    } else {
        $xoopsTpl->assign('rates', false);
    }

    $xt            = new nw_NewsTopic();
    $alltopics     =& $xt->getTopicsList(true, $xnews->getConfig('restrictindex'));
    $smarty_topics = [];
    $topicstories  = [];

    foreach ($alltopics as $topicid => $topic) {
        $allstories  = $nw_NewsStoryHandler->getAllPublished($xnews->getConfig('storyhome'), 0, $xnews->getConfig('restrictindex'), $topicid);
        $storieslist = [];
        foreach ($allstories as $thisstory) {
            $storieslist[] = $thisstory->storyid();
        }
        $filesperstory = $sfiles->getCountbyStories($storieslist);
        foreach ($allstories as $thisstory) {
            $filescount               = array_key_exists($thisstory->storyid(), $filesperstory) ? $filesperstory[$thisstory->storyid()] : 0;
            $story                    = $thisstory->prepare2show($filescount);
            $story['topic_title']     = $thisstory->textlink();
            $story['topic_separator'] = ('' != $thisstory->textlink()) ? _MA_NW_SP : '';
            $story['news_title']      = $story['title'];//$thisstory->storylink();
            $topicstories[$topicid][] = $story;
        }
        if (isset($topicstories[$topicid])) {
            $smarty_topics[$topicstories[$topicid][0]['posttimestamp']] = ['title' => $topic['title'], 'stories' => $topicstories[$topicid], 'id' => $topicid, 'topic_color' => $topic['color']];
        }
    }

    krsort($smarty_topics);
    $columns = [];
    $i       = 0;
    foreach ($smarty_topics as $thistopictimestamp => $thistopic) {
        $columns[$i][] = $thistopic;
        $i++;
        if ($i == $column_count) {
            $i = 0;
        }
    }
    //$xoopsTpl->assign('topics', $smarty_topics);
    $xoopsTpl->assign('columns', $columns);
}

$xoopsTpl->assign('advertisement', $xnews->getConfig('advertisement'));

/**
 * Create the Meta Datas
 */
nw_CreateMetaDatas();

/**
 * Create a clickable path from the root to the current topic (if we are viewing a topic)
 * Actually this is not used in the default templates but you can use it as you want
 * You can comment the code to optimize the requests count
 */
if ($xoopsOption['storytopic']) {
    require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
    $mytree    = new XoopsTree($xoopsDB->prefix('nw_topics'), 'topic_id', 'topic_pid');
    $topicpath = $mytree->getNicePathFromId($xoopsOption['storytopic'], 'topic_title', 'index.php?op=1');
    $xoopsTpl->assign('topic_path', $topicpath);
    unset($mytree);
}

/**
 * Create a link for the RSS feed (if the module's option is activated)
 */
if ($xnews->getConfig('topicsrss') && $xoopsOption['storytopic']) {
    $link = sprintf("<a href='%s' title='%s'><img src='%s' border='0' alt='%s'></a>", XNEWS_MODULE_URL . '/backendt.php?topicid=' . $xoopsOption['storytopic'], _MA_NW_RSSFEED, XNEWS_MODULE_URL . '/assets/images/rss.gif', _MA_NW_RSSFEED);
    $xoopsTpl->assign('topic_rssfeed_link', $link);
}

/**
 * Assign page's title
 */
if ('' != $firsttitle) {
    $xoopsTpl->assign('xoops_pagetitle', $myts->htmlSpecialChars($firsttitle) . ' - ' . $myts->htmlSpecialChars($xnews->getModule()->name()));
} else {
    if ('' != $topictitle) {
        $xoopsTpl->assign('xoops_pagetitle', $myts->htmlSpecialChars($topictitle));
    } else {
        $xoopsTpl->assign('xoops_pagetitle', $myts->htmlSpecialChars($xnews->getModule()->name()));
    }
}

$xoopsTpl->assign('lang_go', _GO);

if (isset($story['poster']) && '' != $story['poster']) {
    $xoopsTpl->assign('lang_on', _ON);
    $xoopsTpl->assign('lang_postedby', _POSTEDBY);
} else {
    $xoopsTpl->assign('lang_on', '' . _MA_NW_POSTED . ' ' . _ON . ' ');
    $xoopsTpl->assign('lang_postedby', '');
}

$xoopsTpl->assign('lang_printerpage', _MA_NW_PRINTERFRIENDLY);
$xoopsTpl->assign('lang_sendstory', _MA_NW_SENDSTORY);
$xoopsTpl->assign('lang_reads', _READS);
$xoopsTpl->assign('lang_morereleases', _MA_NW_MORERELEASES);

require_once XOOPS_ROOT_PATH . '/footer.php';
