<?php
/**
 * Article's page
 *
 * This page is used to see an article (or story) and is mainly called from
 * the module's index page.
 *
 * If no story Id has been placed on the URL or if the story is not yet published
 * then the page will redirect user to the module's index.
 * If the user does not have the permissions to see the article, he is also redirected
 * to the module's index page but with a error message saying :
 *     "Sorry, you don't have the permission to access this area"
 *
 * Each time a page is seen, and only if we are on the first page, its counter of hits is
 * updated
 *
 * Each file(s) attached to the article is visible at the bottom of the article and can
 * be downloaded
 *
 * Notes :
 * - To create more than one page in your story, use the tag [pagebreak]
 * - If you are a module's admin, you have the possibility to see two links at the bottom
 *   of the article, "Edit & Delete"
 *
 * @package                             News
 * @author                              Xoops Modules Dev Team
 * @copyright (c)                       The Xoops Project - www.xoops.org
 *
 * Parameters received by this page :
 * @param int storyid           Id of the story we want to see
 * @param int page              page's number (in the case where there are more than one page)
 *
 * @page_title                          Article's title - Topic's title - Module's name
 *
 * @template_name                       nw_news_article.tpl wich will call nw_news_item.tpl
 *
 * Template's variables :
 * @template_var                        string         pagenav some links to navigate thru pages
 * @template_var                        array          story Contains all the information about the story
 *                                  Structure :
 * @template_var                        int id Story's ID
 * @template_var                        string posttime Story's date of publication
 * @template_var                        string title A link to go and see all the articles in the same topic and the story's title
 * @template_var                        string news_title Just the news title
 * @template_var                        string topic_title Just the topic's title
 * @template_var                        string text Defined as "The scoop"
 * @template_var                        string poster A link to see the author's profil and his name or "Anonymous"
 * @template_var                        int posterid Author's uid (or 0 if it's an anonymous or a user wich does not exist any more)
 * @template_var                        string morelink Never used ???? May be it could be deleted
 * @template_var                        string adminlink A link to Edit or Delete the story or a blank string if you are not the module's admin
 * @template_var                        string topicid     news topic's Id
 * @template_var                        string topic_color Topic's color
 * @template_var                        string imglink A link to go and see the topic of the story with the topic's picture (if it exists)
 * @template_var                        string align Topic's image alignement
 * @template_var                        int hits Story's counter of visits
 * @template_var                        string mail_link A link (with a mailto) to email the story's URL to someone
 * @template_var                        string         lang_printerpage Used in the link and picture to have a "printable version" (fixed text)
 * @template_var                        string         lang_on Fixed text "On" ("published on")
 * @template_var                        string         lang_postedby Fixed text "Posted by"
 * @template_var                        string         lang_reads Fixed text "Reads"
 * @template_var                        string         news_by_the_same_author_link According the the module's option named "newsbythisauthor", it contains a link to see all the article's stories
 * @template_var                        int            summary_count Number of stories really visibles in the summary table
 * @template_var                        boolean        showsummary According to the module's option named "showsummarytable", this contains "True" of "False"
 * @template_var                        array          summary Contains the required information to create a summary table at the bottom of the article. Note, we use the module's option "storyhome" to determine the maximum number of stories visibles in this summary table
 *                                  Structure :
 * @template_var                        int story_id Story's ID
 * @template_var                        string story_title Story's title
 * @template_var                        int story_hits Counter of hits
 * @template_var                        string    story_published    Story's date of creation
 * @template_var                        string         lang_attached_files Fixed text "Attached Files:"
 * @template_var                        int            attached_files_count Number of files attached to the story
 * @template_var                        array    attached_files Contains the list of all the files attached to the story
 *                                  Structure :
 * @template_var                        int file_id File's ID
 * @template_var                        string visitlink Link to download the file
 * @template_var                        string file_realname Original filename (not the real one use to store the file but the one it have when it was on the user hard disk)
 * @template_var                        string file_attacheddate Date to wich the file was attached to the story (in general that's equal to the article's creation date)
 * @template_var                        string file_mimetype File's mime type
 * @template_var                        string file_downloadname Real name of the file on the webserver's disk (changed by the module)
 * @template_var                        boolean        nav_links According to the module's option named "showprevnextlink" it contains "True" or "False" to know if we have to show two links to go to the previous and next article
 * @template_var                        int            previous_story_id Id of the previous story (according to the published date and to the perms)
 * @template_var                        int            next_story_id Id of the next story (according to the published date and to the perms)
 * @template_var                        string         previous_story_title Title of the previous story
 * @template_var                        string         next_story_title Title of the next story
 * @template_var                        string         lang_previous_story Fixed text "Previous article"
 * @template_var                        string         lang_next_story Fixed text "Next article"
 * @template_var                        string         lang_other_story Fixed text "Other articles"
 * @template_var                        boolean        rates To know if rating is enable or not
 * @template_var                        string         lang_ratingc Fixed text "Rating: "
 * @template_var                        string         lang_ratethisnw Fixed text "Rate this News"
 * @template_var                        float          rating Article's rating
 * @template_var                        string         votes "1 vote" or "X votes"
 * @template_var                        string         topic_path A path from the root to the current topic (of the current news)
 */
require_once __DIR__ . '/header.php';

require_once XNEWS_MODULE_PATH . '/class/class.newsstory.php';
require_once XNEWS_MODULE_PATH . '/class/class.sfiles.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
require_once XNEWS_MODULE_PATH . '/class/class.newstopic.php';
require_once XNEWS_MODULE_PATH . '/class/keyhighlighter.class.php';

$storyid = (isset($_GET['storyid'])) ? (int)$_GET['storyid'] : 0;

if (empty($storyid)) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _MA_NW_NOSTORY);
}

$myts = MyTextSanitizer::getInstance();

// Not yet published
$article = new nw_NewsStory($storyid);
if ($article->published() == 0 || $article->published() > time()) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _MA_NW_NOTYETSTORY);
}
// Expired
if ($article->expired() != 0 && $article->expired() < time()) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _MA_NW_NOSTORY);
}

$gpermHandler = xoops_getHandler('groupperm');
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = array(XOOPS_GROUP_ANONYMOUS => XOOPS_GROUP_ANONYMOUS);
}
if (!$gpermHandler->checkRight('nw_view', $article->topicid(), $groups, $xoopsModule->getVar('mid'))) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _NOPERM);
}

$storypage  = isset($_GET['page']) ? (int)$_GET['page'] : 0;
$dateformat = $xnews->getConfig('dateformat');
$hcontent   = '';

//DNPROSSI - Added for adobe detection * does not work in msie
$browser = $_SERVER['HTTP_USER_AGENT'];
//'msie', 'firefox', 'safari', 'webkit', 'opera', 'netscape', 'konqueror', 'gecko'
if (!preg_match('/msie[^;]*/i', $browser)) {
    $has_adobe = nw_detect_adobe();
} else {
    $has_adobe = 1;
}
/**
 * update counter only when viewing top page and when you are not the author or an admin
 */
if (empty($_GET['com_id']) && $storypage == 0) {
    if (is_object($xoopsUser)) {
        if (($xoopsUser->getVar('uid') == $article->uid()) || nw_is_admin_group()) {
            // nothing ! ;-)
        } else {
            $article->updateCounter();
        }
    } else {
        $article->updateCounter();
    }
}
$GLOBALS['xoopsOption']['template_main'] = 'nw_news_article.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

//DNPROSSI - ADDED
$seo_enabled = $xnews->getConfig('seo_enable');

$xoopsTpl->assign('newsmodule_url', XNEWS_MODULE_URL);

$story['id']              = $storyid;
$story['posttime']        = formatTimestamp($article->published(), $dateformat);
$story['news_title']      = $article->title();
$story['title']           = $article->textlink() . '&nbsp;:&nbsp;' . $article->title();
$story['topic_title']     = $article->textlink();
$story['topic_separator'] = ($article->textlink() != '') ? _MA_NW_SP : '';

$story['text'] = $article->hometext();
$bodytext      = $article->bodytext();

if (xoops_trim($bodytext) != '') {
    $articletext = array();
    if ($xnews->getConfig('enhanced_pagenav')) {
        $articletext             = preg_split('/(\[pagebreak:|\[pagebreak)(.*)(\])/iU', $bodytext);
        $arr_titles              = array();
        $auto_summary            = $article->auto_summary($bodytext, $arr_titles);
        $bodytext                = str_replace('[summary]', $auto_summary, $bodytext);
        $articletext[$storypage] = str_replace('[summary]', $auto_summary, $articletext[$storypage]);
        $story['text']           = str_replace('[summary]', $auto_summary, $story['text']);
    } else {
        $articletext = explode('[pagebreak]', $bodytext);
    }

    $story_pages = count($articletext);

    if ($story_pages > 1) {
        xoops_load('xoopspagenav');
        $pagenav = new XoopsPageNav($story_pages, 1, $storypage, 'page', 'storyid=' . $storyid);
        if (nw_isbot()) { // A bot is reading the articles, we are going to show him all the links to the pages
            $xoopsTpl->assign('pagenav', $pagenav->renderNav($story_pages));
        } else {
            if ($xnews->getConfig('enhanced_pagenav')) {
                $xoopsTpl->assign('pagenav', $pagenav->renderEnhancedSelect(true, $arr_titles));
            } else {
                $xoopsTpl->assign('pagenav', $pagenav->renderNav());
            }
        }

        if ($storypage == 0) {
            $story['text'] = $story['text'] . '<br>' . $xnews->getConfig('advertisement') . '<br>' . $articletext[$storypage];
        } else {
            $story['text'] = $articletext[$storypage];
        }
    } else {
        $story['text'] = $story['text'] . '<br>' . $xnews->getConfig('advertisement') . '<br>' . $bodytext;
    }
}
// Publicité
$xoopsTpl->assign('advertisement', $xnews->getConfig('advertisement'));

// ****************************************************************************************************************
function my_highlighter($matches)
{
    $color = $xnews->getConfig('highlightcolor');
    if (substr($color, 0, 1) != '#') {
        $color = '#' . $color;
    }

    return '<span style="font-weight: bolder; background-color: ' . $color . ';">' . $matches[0] . '</span>';
}

$highlight = false;
$highlight = $xnews->getConfig('keywordshighlight');

if ($highlight && isset($_GET['keywords'])) {
    $keywords      = $myts->htmlSpecialChars(trim(urldecode($_GET['keywords'])));
    $h             = new nw_keyhighlighter($keywords, true, 'my_highlighter');
    $story['text'] = $h->highlight($story['text']);
}
// ****************************************************************************************************************

$story['poster'] = $article->uname();
if ($story['poster']) {
    $story['posterid']         = $article->uid();
    $story['poster']           = '<a href="' . XOOPS_URL . '/userinfo.php?uid=' . $story['posterid'] . '">' . $story['poster'] . '</a>';
    $tmp_user                  = new XoopsUser($article->uid());
    $story['poster_avatar']    = XOOPS_UPLOAD_URL . '/' . $tmp_user->getVar('user_avatar');
    $story['poster_signature'] = $tmp_user->getVar('user_sig');
    $story['poster_email']     = $tmp_user->getVar('email');
    $story['poster_url']       = $tmp_user->getVar('url');
    $story['poster_from']      = $tmp_user->getVar('user_from');
    unset($tmp_user);
} else {
    $story['poster']           = '';
    $story['posterid']         = 0;
    $story['poster_avatar']    = '';
    $story['poster_signature'] = '';
    $story['poster_email']     = '';
    $story['poster_url']       = '';
    $story['poster_from']      = '';
    if ($xnews->getConfig('displayname') != 3) {
        $story['poster'] = $xoopsConfig['anonymous'];
    }
}
$story['morelink']  = '';
$story['adminlink'] = '';
unset($isadmin);

if (is_object($xoopsUser)) {
    if ($xoopsUser->isAdmin($xoopsModule->getVar('mid')) || ($xnews->getConfig('authoredit') && $article->uid() == $xoopsUser->getVar('uid'))) {
        $isadmin            = true;
        $story['adminlink'] = $article->adminlink();
    }
}
$story['topicid']     = $article->topicid();
$story['topic_color'] = '#' . $myts->displayTarea($article->topic_color);

$story['imglink'] = '';
$story['align']   = '';
if ($article->topicdisplay()) {
    $story['imglink'] = $article->imglink();
    $story['align']   = $article->topicalign();
}
$story['hits']      = $article->counter();
$story['mail_link'] = 'mailto:?subject=' . sprintf(_MA_NW_INTARTICLE, $xoopsConfig['sitename']) . '&amp;body=' . sprintf(_MA_NW_INTARTFOUND, $xoopsConfig['sitename']) . ':  ' . XNEWS_MODULE_URL . '/article.php?storyid=' . $article->storyid();
$xoopsTpl->assign('lang_printerpage', _MA_NW_PRINTERFRIENDLY);
$xoopsTpl->assign('lang_sendstory', _MA_NW_SENDSTORY);
$xoopsTpl->assign('lang_pdfstory', _MA_NW_MAKEPDF);

if ($article->uname() != '') {
    $xoopsTpl->assign('lang_on', _ON);
    $xoopsTpl->assign('lang_postedby', _POSTEDBY);
} else {
    $xoopsTpl->assign('lang_on', '' . _MA_NW_POSTED . ' ' . _ON . ' ');
    $xoopsTpl->assign('lang_postedby', '');
}

$xoopsTpl->assign('lang_reads', _READS);
$xoopsTpl->assign('mail_link', 'mailto:?subject=' . sprintf(_MA_NW_INTARTICLE, $xoopsConfig['sitename']) . '&amp;body=' . sprintf(_MA_NW_INTARTFOUND, $xoopsConfig['sitename']) . ':  ' . XNEWS_MODULE_URL . '/article.php?storyid=' . $article->storyid());

//DNPROSSI - Added -1.71 adobe reader detection - diplay_pdf - diplay_images
if ($xnews->getConfig('pdf_detect') == 1) {
    $xoopsTpl->assign('has_adobe', $has_adobe);
} else {
    $xoopsTpl->assign('has_adobe', 1);
}

$xoopsTpl->assign('diplay_pdf', $xnews->getConfig('pdf_display'));
$xoopsTpl->assign('display_images', $xnews->getConfig('images_display'));

$xoopsTpl->assign('lang_attached_files', _MA_NW_ATTACHEDFILES);
$sfiles     = new nw_sFiles();
$filesarr   = $newsfiles = array();
$filesarr   = $sfiles->getAllbyStory($storyid);
$filescount = count($filesarr);
//DNPROSSI - Added count variables for pdf - images columns
$row_images       = array();
$row_pdf          = array();
$row_images_count = $article->imagerows();
$row_pdf_count    = $article->pdfrows();
$k                = 0;
$j                = 0;
$xoopsTpl->assign('attached_files_count', $filescount);
if ($filescount > 0) {
    foreach ($filesarr as $onefile) {
        if (strstr($onefile->getMimetype(), 'image')) {
            $mime = 'image';
            //DNPROSSI - Added file_downloadname
            // IN PROGRESS
            // IN PROGRESS
            // IN PROGRESS
            $newsfiles[]      = array(
                'visitlink'         => XNEWS_ATTACHED_FILES_URL . '/' . $onefile->getDownloadname(),
                'file_realname'     => $onefile->getFileRealName(),
                'file_mimetype'     => $mime,
                'file_downloadname' => XNEWS_ATTACHED_FILES_URL . '/' . $onefile->getDownloadname()
            );
            $newsimages       = array(
                'visitlink'     => XNEWS_ATTACHED_FILES_URL . '/' . $onefile->getDownloadname(),
                'file_realname' => $onefile->getFileRealName(),
                'file_mimetype' => $mime,
                'thumbname'     => XNEWS_ATTACHED_FILES_URL . '/thumb_' . $onefile->getDownloadname()
            );
            $row_images[$j][] = $newsimages;
            $j++;
            if ($j == $row_images_count) {
                $j = 0;
            }
        } else {
            $newsfiles[]   = array(
                'file_id'           => $onefile->getFileid(),
                'visitlink'         => XNEWS_MODULE_URL . '/visit.php?fileid=' . $onefile->getFileid(),
                'file_realname'     => $onefile->getFileRealName(),
                'file_attacheddate' => formatTimestamp($onefile->getDate(), $dateformat),
                'file_mimetype'     => $onefile->getMimetype(),
                'file_downloadname' => XNEWS_ATTACHED_FILES_URL . '/' . $onefile->getDownloadname()
            );
            $newspdf       = array(
                'file_id'           => $onefile->getFileid(),
                'visitlink'         => XNEWS_MODULE_URL . '/visit.php?fileid=' . $onefile->getFileid(),
                'file_realname'     => $onefile->getFileRealName(),
                'file_attacheddate' => formatTimestamp($onefile->getDate(), $dateformat),
                'file_mimetype'     => $onefile->getMimetype(),
                'file_downloadname' => XNEWS_ATTACHED_FILES_URL . '/' . $onefile->getDownloadname()
            );
            $row_pdf[$k][] = $newspdf;
            $k++;
            if ($k == $row_pdf_count) {
                $k = 0;
            }
        }
    }
    $xoopsTpl->assign('attached_files', $newsfiles);
    $xoopsTpl->assign('attached_images', $row_images);
    $xoopsTpl->assign('attached_pdf', $row_pdf);
    $xoopsTpl->assign('images_count', count($row_images));
    $xoopsTpl->assign('pdf_count', count($row_pdf));
}

/**
 * Create page's title
 */
$complement = '';
if ($xnews->getConfig('enhanced_pagenav') && (isset($arr_titles) && is_array($arr_titles) && isset($arr_titles, $storypage) && $storypage > 0)) {
    $complement = ' - ' . $arr_titles[$storypage];
}
$xoopsTpl->assign('xoops_pagetitle', $article->title() . $complement . ' - ' . $article->topic_title() . ' - ' . $myts->htmlSpecialChars($xoopsModule->name()));

if ($xnews->getConfig('newsbythisauthor')) {
    $xoopsTpl->assign('news_by_the_same_author_link', sprintf("<a href='%s?uid=%d'>%s</a>", XNEWS_MODULE_URL . '/newsbythisauthor.php', $article->uid(), _MA_NW_NEWSSAMEAUTHORLINK));
}

/**
 * Create a clickable path from the root to the current topic (if we are viewing a topic)
 * Actually this is not used in the default's templates but you can use it as you want
 * Uncomment the code to be able to use it
 */
if ($cfg['create_clickable_path']) {
    $mytree    = new XoopsTree($xoopsDB->prefix('nw_topics'), 'topic_id', 'topic_pid');
    $topicpath = $mytree->getNicePathFromId($article->topicid(), 'topic_title', 'index.php?op=1');
    $xoopsTpl->assign('topic_path', $topicpath);
    unset($mytree);
}

/**
 * Summary table
 *
 * When you are viewing an article, you can see a summary table containing
 * the first n links to the last published news.
 * This summary table is visible according to a module's option (showsummarytable)
 * The number of items is equal to the module's option "storyhome" ("Select the number
 * of news items to display on top page")
 * We also use the module's option "restrictindex" ("Restrict Topics on Index Page"), like
 * this you (the webmaster) select if users can see restricted stories or not.
 */
if ($xnews->getConfig('showsummarytable')) {
    $xoopsTpl->assign('showsummary', true);
    $xoopsTpl->assign('lang_other_story', _MA_NW_OTHER_ARTICLES);
    $count      = 0;
    $tmparticle = new nw_NewsStory();
    $infotips   = $xnews->getConfig('infotips');
    $sarray     = $tmparticle->getAllPublished($cfg['article_summary_items_count'], 0, $xnews->getConfig('restrictindex'));
    if (count($sarray) > 0) {
        foreach ($sarray as $onearticle) {
            $count++;
            $htmltitle = '';
            $tooltips  = '';
            $htmltitle = '';
            if ($infotips > 0) {
                $tooltips  = nw_make_infotips($onearticle->hometext());
                $htmltitle = ' title="' . $tooltips . '"';
            }
            //DNPROSSI SEO
            $story_path = '';
            if ($seo_enabled != 0) {
                $story_path = nw_remove_accents($onearticle->title());
                $storyTitle = "<a href='" . nw_seo_UrlGenerator(_MA_NW_SEO_ARTICLES, $onearticle->storyid(), $story_path) . "'>" . $onearticle->title() . '</a>';
                $xoopsTpl->append('summary', array(
                    'story_id'        => $onearticle->storyid(),
                    'htmltitle'       => $htmltitle,
                    'infotips'        => $tooltips,
                    'story_title'     => $storyTitle,
                    'story_hits'      => $onearticle->counter(),
                    'story_published' => formatTimestamp($onearticle->published, $dateformat)
                ));
            } else {
                $xoopsTpl->append('summary', array(
                    'story_id'        => $onearticle->storyid(),
                    'htmltitle'       => $htmltitle,
                    'infotips'        => $tooltips,
                    'story_title'     => $onearticle->title(),
                    'story_hits'      => $onearticle->counter(),
                    'story_published' => formatTimestamp($onearticle->published, $dateformat)
                ));
            }
        }
    }
    $xoopsTpl->assign('summary_count', $count);
    unset($tmparticle);
} else {
    $xoopsTpl->assign('showsummary', false);
}

/**
 * Show a link to go to the previous article and to the next article
 *
 * According to a module's option "showprevnextlink" ("Show Previous and Next link ?")
 * you can display, at the bottom of each article, two links used to navigate thru stories.
 * This feature uses the module's option "restrictindex" so that we can, or can't see
 * restricted stories
 */
if ($xnews->getConfig('showprevnextlink')) {
    $xoopsTpl->assign('nav_links', $xnews->getConfig('showprevnextlink'));
    $tmparticle    = new nw_NewsStory();
    $nextId        = $previousId = -1;
    $next          = $previous = array();
    $previousTitle = $nextTitle = '';

    $next = $tmparticle->getNextArticle($storyid, $xnews->getConfig('restrictindex'));
    if (count($next) > 0) {
        $nextId    = $next['storyid'];
        $nextTitle = $next['title'];
    }

    $previous = $tmparticle->getPreviousArticle($storyid, $xnews->getConfig('restrictindex'));
    if (count($previous) > 0) {
        $previousId    = $previous['storyid'];
        $previousTitle = $previous['title'];
    }

    $xoopsTpl->assign('previous_story_id', $previousId);
    $xoopsTpl->assign('next_story_id', $nextId);
    if ($previousId > 0) {
        $xoopsTpl->assign('previous_story_title', $previousTitle);
        $hcontent .= sprintf("<link rel=\"Prev\" title=\"%s\" href=\"%s/\">\n", $previousTitle, XNEWS_MODULE_URL . '/article.php?storyid=' . $previousId);

        //DNPROSSI SEO
        $item_path = '';
        if ($seo_enabled != 0) {
            $item_path = nw_remove_accents($previousTitle);
        }
        $prevStory = "<a href='" . nw_seo_UrlGenerator(_MA_NW_SEO_ARTICLES, $previousId, $item_path) . "' title='" . _MA_NW_PREVIOUS_ARTICLE . "'>";
        $prevStory .= "<img src='" . XNEWS_MODULE_URL . "/assets/images/leftarrow22.png' border='0' alt='" . _MA_NW_PREVIOUS_ARTICLE . "'></a>";
        $xoopsTpl->assign('previous_story', $prevStory);
    }

    if ($nextId > 0) {
        //DNPROSSI SEO
        $item_path = '';
        if ($seo_enabled != 0) {
            $item_path = nw_remove_accents($nextTitle);
        }
        $nextStory = "<a href='" . nw_seo_UrlGenerator(_MA_NW_SEO_ARTICLES, $nextId, $item_path) . "' title='" . _MA_NW_NEXT_ARTICLE . "'>";
        $nextStory .= "<img src='" . XNEWS_MODULE_URL . "/assets/images/rightarrow22.png' border='0' alt='" . _MA_NW_NEXT_ARTICLE . "'></a>";
        $xoopsTpl->assign('next_story', $nextStory);

        $hcontent .= sprintf("<link rel=\"Next\" title=\"%s\" href=\"%s/\">\n", $nextTitle, XNEWS_MODULE_URL . '/article.php?storyid=' . $nextId);
    }

    $xoopsTpl->assign('lang_previous_story', _MA_NW_PREVIOUS_ARTICLE);
    $xoopsTpl->assign('lang_next_story', _MA_NW_NEXT_ARTICLE);
    unset($tmparticle);
} else {
    $xoopsTpl->assign('nav_links', 0);
}

/**
 * Manage all the meta datas
 */
nw_CreateMetaDatas($article);

/**
 * Show a "Bookmark this article at these sites" block ?
 */
if ($xnews->getConfig('bookmarkme')) {
    $xoopsTpl->assign('bookmarkme', true);
    $xoopsTpl->assign('encoded_title', rawurlencode($article->title()));
} else {
    $xoopsTpl->assign('bookmarkme', false);
}

/**
 * Enable users to vote
 *
 * According to a module's option, "ratenews", you can display a link to rate the current news
 * The actual rate in showed (and the number of votes)
 * Possible modification, restrict votes to registred users
 */
$other_test = true;
if ($cfg['config_rating_registred_only']) {
    if (isset($xoopsUser) && is_object($xoopsUser)) {
        $other_test = true;
    } else {
        $other_test = false;
    }
}

if ($xnews->getConfig('ratenews') && $other_test) {
    $xoopsTpl->assign('rates', true);
    $xoopsTpl->assign('lang_ratingc', _MA_NW_RATINGC);
    $xoopsTpl->assign('lang_ratethisnews', _MA_NW_RATETHISNEWS);
    $story['rating'] = number_format($article->rating(), 2);
    if ($article->votes == 1) {
        $story['votes'] = _MA_NW_ONEVOTE;
    } else {
        $story['votes'] = sprintf(_MA_NW_NUMVOTES, $article->votes);
    }
} else {
    $xoopsTpl->assign('rates', false);
}

$xoopsTpl->assign('story', $story);

//DNPROSSI - ADDED
$xoopsTpl->assign('display_icons', $xnews->getConfig('displaylinkicns'));

//DNPROSSI SEO
$item_path = '';
if ($seo_enabled != 0) {
    $item_path = nw_remove_accents($article->title());
}
$storyURL = nw_seo_UrlGenerator(_MA_NW_SEO_ARTICLES, $storyid, $item_path);
$xoopsTpl->assign('story_url', $storyURL);

$print_item = '';
if ($seo_enabled != 0) {
    $print_item = nw_remove_accents(_MA_NW_PRINTERFRIENDLY);
}
$printLink = "<a target='_blank' href='" . nw_seo_UrlGenerator(_MA_NW_SEO_PRINT, $storyid, $print_item) . "' title='" . _MA_NW_PRINTERFRIENDLY . "'>";
$printLink .= "<img src='" . XNEWS_MODULE_URL . "/assets/images/print.png' width='28px' height='28px' border='0' alt='" . _MA_NW_PRINTERFRIENDLY . "'></a>";
$xoopsTpl->assign('print_link', $printLink);

$pdf_item = '';
if ($seo_enabled != 0) {
    $pdf_item = nw_remove_accents($article->title());
}
$pdfLink = "<a target='_blank' href='" . nw_seo_UrlGenerator(_MA_NW_SEO_PDF, $storyid, $pdf_item) . "' title='" . _MA_NW_MAKEPDF . "'>";
$pdfLink .= "<img src='" . XNEWS_MODULE_URL . "/assets/images/acrobat.png' width='28px' height='28px' border='0' alt='" . _MA_NW_MAKEPDF . "'></a>";
$xoopsTpl->assign('pdf_link', $pdfLink);

if ($seo_enabled != 0) {
    $xoopsTpl->assign('urlrewrite', true);
} else {
    $xoopsTpl->assign('urlrewrite', false);
}

// Added in version 1.63, TAGS
if ($xnews->getConfig('tags')) {
    require_once XOOPS_ROOT_PATH . '/modules/tag/include/tagbar.php';
    $xoopsTpl->assign('tags', true);
    $xoopsTpl->assign('tagbar', tagBar($storyid, 0));
} else {
    $xoopsTpl->assign('tags', false);
}

// Include the comments
// Problem with url_rewrite and posting comments :
if ($xnews->getConfig('com_rule') != 0) {
    require_once XOOPS_ROOT_PATH . '/include/comment_view.php';
    require_once XOOPS_ROOT_PATH . '/class/commentrenderer.php';

    if ($seo_enabled != 0) {
        $navbar = '
<form method="get" action="' . XNEWS_MODULE_URL . '/' . $comment_config['pageName'] . '">
<table width="95%" class="outer" cellspacing="1">
  <tr>
    <td class="even" align="center"><select name="com_mode"><option value="flat"';
        if ($com_mode == 'flat') {
            $navbar .= ' selected="selected"';
        }
        $navbar .= '>' . _FLAT . '</option><option value="thread"';
        if ($com_mode == 'thread' || $com_mode == '') {
            $navbar .= ' selected="selected"';
        }
        $navbar .= '>' . _THREADED . '</option><option value="nest"';
        if ($com_mode == 'nest') {
            $navbar .= ' selected="selected"';
        }
        $navbar .= '>' . _NESTED . '</option></select> <select name="com_order"><option value="' . XOOPS_COMMENT_OLD1ST . '"';
        if ($com_order == XOOPS_COMMENT_OLD1ST) {
            $navbar .= ' selected="selected"';
        }
        $navbar .= '>' . _OLDESTFIRST . '</option><option value="' . XOOPS_COMMENT_NEW1ST . '"';
        if ($com_order == XOOPS_COMMENT_NEW1ST) {
            $navbar .= ' selected="selected"';
        }
        unset($postcomment_link);
        $navbar .= '>' . _NEWESTFIRST . '</option></select><input type="hidden" name="' . $comment_config['itemName'] . '" value="' . $com_itemid . '"> <input type="submit" value="' . _CM_REFRESH . '" class="formButton">';
        if (!empty($xnews->getConfig('com_anonpost')) || is_object($xoopsUser)) {
            $postcomment_link = 'comment_new.php?com_itemid=' . $com_itemid . '&amp;com_order=' . $com_order . '&amp;com_mode=' . $com_mode;

            $xoopsTpl->assign('anon_canpost', true);
        }
        $link_extra = '';
        if (isset($comment_config['extraParams']) && is_array($comment_config['extraParams'])) {
            foreach ($comment_config['extraParams'] as $extra_param) {
                if (isset(${$extra_param})) {
                    $link_extra      .= '&amp;' . $extra_param . '=' . ${$extra_param};
                    $hidden_value    = htmlspecialchars(${$extra_param}, ENT_QUOTES);
                    $extra_param_val = ${$extra_param};
                } elseif (isset($_POST[$extra_param])) {
                    $extra_param_val = $_POST[$extra_param];
                } elseif (isset($_GET[$extra_param])) {
                    $extra_param_val = $_GET[$extra_param];
                }
                if (isset($extra_param_val)) {
                    $link_extra   .= '&amp;' . $extra_param . '=' . $extra_param_val;
                    $hidden_value = htmlspecialchars($extra_param_val, ENT_QUOTES);
                    $navbar       .= '<input type="hidden" name="' . $extra_param . '" value="' . $hidden_value . '">';
                }
            }
        }
        if (isset($postcomment_link)) {
            $navbar .= '&nbsp;<input type="button" onclick="self.location.href=\'' . $postcomment_link . '' . $link_extra . '\'" class="formButton" value="' . _CM_POSTCOMMENT . '">';
        }
        $navbar .= '
    </td>
  </tr>
</table>
</form>';

        $xoopsTpl->assign(array(
                              'commentsnav'        => $navbar,
                              'editcomment_link'   => XNEWS_MODULE_URL . '/comment_edit.php?com_itemid=' . $com_itemid . '&amp;com_order=' . $com_order . '&amp;com_mode=' . $com_mode . $link_extra,
                              'deletecomment_link' => XNEWS_MODULE_URL . '/comment_delete.php?com_itemid=' . $com_itemid . '&amp;com_order=' . $com_order . '&amp;com_mode=' . $com_mode . $link_extra,
                              'replycomment_link'  => XNEWS_MODULE_URL . '/comment_reply.php?com_itemid=' . $com_itemid . '&amp;com_order=' . $com_order . '&amp;com_mode=' . $com_mode . $link_extra
                          ));
        $xoopsTpl->_tpl_vars['commentsnav'] = str_replace("self.location.href='", "self.location.href='" . XNEWS_MODULE_URL . '/', $xoopsTpl->_tpl_vars['commentsnav']);
    }
}

require_once XOOPS_ROOT_PATH . '/footer.php';
