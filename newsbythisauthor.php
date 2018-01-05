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

/*
 * Display all the news by the author of a certain story
 *
 * This page is called from the page "article.php" and it will
 * show all the articles writen by an author. We use the module's
 * option named "restrictindex" to show or hide stories according
 * to users permissions and this page can only be called if the
 * module's option "newsbythisauthor" is set to "Yes"
 *
 * @package News
 * @author Xoops Modules Dev Team
 * @copyright	(c) The Xoops Project - www.xoops.org
 *
 * Parameters received by this page :
 * @page_param int              uid Id of the user you want to treat
 *
 * @page_title                  "News by the same author" - Author's name - Module's name
 *
 * @template_name               nw_news_by_this_author.html
 *
 * Template's variables :
 * @template_var string         lang_page_title contains "News by the same author"
 * @template_var int            author_id contains the user ID
 * @template_var string         author_name Name of the author (according to the user preferences (username or full name or nothing))
 * @template_var string	author_name_with_link Name of the author with an hyperlink pointing to userinfo.php (to see his "identity")
 * @template_var int            articles_count Total number of visibles articles (for the current user and according to the permissions)
 * @template_var string         lang_date Fixed string, "Date"
 * @template_var string         lang_hits Fixed string, 'Views'
 * @template_var string         lang_title Fixed string, 'Title'
 * @template_var int            articles_count Total number of articles by this author (permissions are used)
 * @template_var boolean        nw_rating News are rated ?
 * @template_var string         lang_rating Fixed text "Rating"
 * @template_var array          topics Contains all the topics where the author have written some articles.
 *                                  Structure :
 *                                  topic_id int Topic's ID
 *                                  topic_title string Topic's title
 *                                  topic_color string Topic's color
 *                                  topic_link	string	Link to see all the articles in this topic + topic's title
 *                                  news array List of all the articles from this author for this topic
 *                                      Structure :
 *                                          int id Article's Id
 *                                          string hometext The scoop
 *                                          string title Article's title
 *                                          int hits Counter of visits
 *                                          string created Date of creation formated (according to user's prefs)
 *                                          string article_link Link to see the article + article's title
 *                                          string published Date of publication formated (according to user's prefs)
 *                                          int rating rating for this news
 */
require_once __DIR__ . '/header.php';

require_once XNEWS_MODULE_PATH . '/class/class.newsstory.php';
require_once XNEWS_MODULE_PATH . '/class/class.newstopic.php';
require_once XNEWS_MODULE_PATH . '/class/class.sfiles.php';

global $xoopsUser;

xoops_loadLanguage('modinfo', XNEWS_MODULE_DIRNAME);

$uid = isset($_GET['uid']) ? (int)$_GET['uid'] : 0;
if (empty($uid)) {
    redirect_header('index.php', 3, _ERRORS);
}

if (!$xnews->getConfig('newsbythisauthor')) {
    redirect_header('index.php', 3, _ERRORS);
}

$myts                                    = \MyTextSanitizer::getInstance();
$articles                                = new XNewsStory();
$GLOBALS['xoopsOption']['template_main'] = 'xnews_by_this_author.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

$dateformat = $xnews->getConfig('dateformat');
$infotips   = $xnews->getConfig('infotips');
$thisuser   = new \XoopsUser($uid);

switch ($xnews->getConfig('displayname')) {
    case 1: // Username
        $authname = $thisuser->getVar('uname');
        break;
    case 2: // Display full name (if it is not empty)
        if ('' == xoops_trim($thisuser->getVar('name'))) {
            $authname = $thisuser->getVar('uname');
        } else {
            $authname = $thisuser->getVar('name');
        }
        break;
    case 3: // Nothing
        $authname = '';
        break;
}
$xoopsTpl->assign('lang_page_title', _MI_XNEWS_NEWSBYTHISAUTHOR . ' - ' . $authname);
$xoopsTpl->assign('lang_nw_by_this_author', _MI_XNEWS_NEWSBYTHISAUTHOR);
$xoopsTpl->assign('author_id', $uid);
$xoopsTpl->assign('user_avatarurl', XOOPS_URL . '/uploads/' . $thisuser->getVar('user_avatar'));
$xoopsTpl->assign('author_name', $authname);
$xoopsTpl->assign('lang_date', _MD_XNEWS_DATE);
$xoopsTpl->assign('lang_hits', _MD_XNEWS_VIEWS);
$xoopsTpl->assign('lang_title', _MD_XNEWS_TITLE);
$xoopsTpl->assign('nw_rating', $xnews->getConfig('ratenews'));
$xoopsTpl->assign('lang_rating', _MD_XNEWS_RATING);
$xoopsTpl->assign('author_name_with_link', sprintf("<a href='%s'>%s</a>", XOOPS_URL . '/userinfo.php?uid=' . $uid, $authname));

$oldtopic      = -1;
$oldtopictitle = '';
$oldtopiccolor = '';
$articlelist   = [];
$articlestpl   = [];
$articlelist   = $articles->getAllPublishedByAuthor($uid, $xnews->getConfig('restrictindex'), false);
$articlescount = count($articlelist);
$xoopsTpl->assign('articles_count', $articlescount);
$count_articles = $count_reads = 0;

// DNPROSSI SEO
$seo_enabled = $xnews->getConfig('seo_enable');

if ($articlescount > 0) {
    foreach ($articlelist as $article) {
        if ($oldtopic != $article['topicid']) {
            if (count($articlestpl) > 0) {
                // DNPROSSI SEO
                $cat_path = '';
                if (0 != $seo_enabled) {
                    $cat_path = nw_remove_accents($oldtopictitle);
                }
                $topic_link = "<a href='" . nw_seo_UrlGenerator(_MD_XNEWS_SEO_TOPICS, $oldtopic, $cat_path) . "'>" . $oldtopictitle . '</a>';
                $xoopsTpl->append('topics', [
                    'topic_id'             => $oldtopic,
                    'topic_count_articles' => sprintf(_AM_XNEWS_TOTAL, $count_articles),
                    'topic_count_reads'    => $count_reads,
                    'topic_color'          => $oldtopiccolor,
                    'topic_title'          => $oldtopictitle,
                    'topic_link'           => $topic_link,
                    'news'                 => $articlestpl
                ]);
            }
            $oldtopic       = $article['topicid'];
            $oldtopictitle  = $article['topic_title'];
            $oldtopiccolor  = '#' . $myts->displayTarea($article['topic_color']);
            $articlestpl    = [];
            $count_articles = $count_reads = 0;
        }
        $htmltitle = '';
        if ($infotips > 0) {
            $htmltitle = ' title="' . nw_make_infotips($article['hometext']) . '"';
        }
        $count_articles++;
        $count_reads += $article['counter'];
        // DNPROSSI SEO
        $story_path = '';
        if (0 != $seo_enabled) {
            $story_path = nw_remove_accents($article['title']);
        }
        $storyTitle    = "<a href='" . nw_seo_UrlGenerator(_MD_XNEWS_SEO_ARTICLES, $article['storyid'], $story_path) . "' " . $htmltitle . '>' . $article['title'] . '</a>';
        $articlestpl[] = [
            'id'           => $article['storyid'],
            'hometext'     => $article['hometext'],
            'title'        => $article['title'],
            'hits'         => $article['counter'],
            'created'      => formatTimestamp($article['created'], $dateformat),
            'article_link' => $storyTitle,
            'published'    => formatTimestamp($article['published'], $dateformat),
            'rating'       => $article['rating']
        ];
    }
}

// DNPROSSI SEO
$cat_path = '';
if (0 != $seo_enabled) {
    $cat_path = nw_remove_accents($article['topic_title']);
}
$topic_link = "<a href='" . nw_seo_UrlGenerator(_MD_XNEWS_SEO_TOPICS, $oldtopic, $cat_path) . "'>" . $article['topic_title'] . '</a>';

$xoopsTpl->append('topics', ['topic_id' => $oldtopic, 'topic_title' => $oldtopictitle, 'topic_link' => $topic_link, 'news' => $articlestpl]);
$xoopsTpl->assign('xoops_pagetitle', _MI_XNEWS_NEWSBYTHISAUTHOR . ' - ' . $authname . ' - ' . $myts->htmlSpecialChars($xoopsModule->name()));
$xoopsTpl->assign('advertisement', $xnews->getConfig('advertisement'));

/**
 * Create the meta datas
 */
nw_CreateMetaDatas();

$meta_description = _MI_XNEWS_NEWSBYTHISAUTHOR . ' - ' . $authname . ' - ' . $myts->htmlSpecialChars($xoopsModule->name());
if (isset($xoTheme) && is_object($xoTheme)) {
    $xoTheme->addMeta('meta', 'description', $meta_description);
} else { // Compatibility for old Xoops versions
    $xoopsTpl->assign('xoops_meta_description', $meta_description);
}

require_once XOOPS_ROOT_PATH . '/include/comment_view.php';
require_once XOOPS_ROOT_PATH . '/footer.php';
