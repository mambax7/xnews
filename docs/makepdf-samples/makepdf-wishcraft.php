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

use XoopsModules\Xnews;

error_reporting(0);
require_once __DIR__ . '/header.php';

if (!is_file(XOOPS_ROOT_PATH . '/class/libraries/vendor/tecnickcom/tcpdf/tcpdf.php')) {
    redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewtopic.php?topic_id=' . $topic_id, 3, 'TCPDF for Xoops not installed');
} else {
    require_once XOOPS_ROOT_PATH . '/class/libraries/vendor/tecnickcom/tcpdf/tcpdf.php';
}

$myts = \MyTextSanitizer::getInstance();

require_once XNEWS_MODULE_PATH . '/class/NewsStory.php';
require_once XNEWS_MODULE_PATH . '/class/Files.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
require_once XNEWS_MODULE_PATH . '/include/functions.php';
require_once XNEWS_MODULE_PATH . '/class/NewsTopic.php';
require_once XNEWS_MODULE_PATH . '/class/Keyhighlighter.php';

// Verifications on the article
$storyid = isset($_GET['storyid']) ? (int)$_GET['storyid'] : 0;

if (empty($storyid)) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _MD_XNEWS_NOSTORY);
}

$sql = 'SELECT a.topic_title, b.title FROM ' . $GLOBALS['xoopsDB']->prefix('nw_stories') . ' b INNER JOIN ' . $GLOBALS['xoopsDB']->prefix('nw_topics') . " a on b.topicid = a.topic_id where b.storyid = $storyid";
$ret = $GLOBALS['xoopsDB']->query($sql);
$row = $GLOBALS['xoopsDB']->fetchArray($ret);
$url = XOOPS_URL . '/' . $GLOBALS['xoopsModuleConfig']['seopath'] . '/' . xoops_sef($row['topic_title']) . '/' . xoops_sef($row['title']) . '/pdf,' . $_REQUEST['storyid'] . $GLOBALS['xoopsModuleConfig']['seo_endofurl_pdf'];

if (!strpos($url, $_SERVER['REQUEST_URI']) && 1 == $GLOBALS['xoopsModuleConfig']['seo_enable']) {
    header('HTTP/1.1 301 Moved Permanently');
    header("Location: $url");
    exit(0);
}

xoops_loadLanguage('user');
if (!isset($GLOBALS['xoopsTpl']) || !is_object($GLOBALS['xoopsTpl'])) {
    require_once $GLOBALS['xoops']->path('/class/template.php');
    $GLOBALS['xoopsTpl'] = new \XoopsTpl();
}

$article = new Xnews\NewsStory($storyid);
if (0 == $article->published() || $article->published() > time()) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _MD_XNEWS_NOTYETSTORY);
}
// Expired
if (0 != $article->expired() && $article->expired() < time()) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _MD_XNEWS_NOSTORY);
}

$gpermHandler = xoops_getHandler('groupperm');
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = [XOOPS_GROUP_ANONYMOUS => XOOPS_GROUP_ANONYMOUS];
}
if (!$gpermHandler->checkRight('nw_view', $article->topicid(), $groups, $xoopsModule->getVar('mid'))) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _NOPERM);
}

$storypage  = isset($_GET['page']) ? (int)$_GET['page'] : 0;
$dateformat = $helper->getConfig('dateformat');
$hcontent   = '';

/**
 * update counter only when viewing top page and when you are not the author or an admin
 */
if (empty($_GET['com_id']) && 0 == $storypage) {
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

//DNPROSSI - ADDED
$seo_enabled = $helper->getConfig('seo_enable');

$GLOBALS['xoopsTpl']->assign('newsmodule_url', XNEWS_MODULE_URL);

$story['id']              = $storyid;
$story['posttime']        = formatTimestamp($article->published(), $dateformat);
$story['news_title']      = $article->title();
$story['title']           = $article->textlink() . '&nbsp;:&nbsp;' . $article->title();
$story['topic_title']     = $article->textlink();
$story['topic_separator'] = ('' != $article->textlink()) ? _MD_XNEWS_SP : '';

$story['text'] = $article->hometext();
$bodytext      = $article->bodytext();

if ('' != xoops_trim($bodytext)) {
    $articletext = [];
    if ($helper->getConfig('enhanced_pagenav')) {
        $articletext             = preg_split('/(\[pagebreak:|\[pagebreak)(.*)(\])/iU', $bodytext);
        $arr_titles              = [];
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
        $pagenav = new \XoopsPageNav($story_pages, 1, $storypage, 'page', 'storyid=' . $storyid);
        if (nw_isbot()) {         // A bot is reading the articles, we are going to show him all the links to the pages
            $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav($story_pages));
        } else {
            if ($helper->getConfig('enhanced_pagenav')) {
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderEnhancedSelect(true, $arr_titles));
            } else {
                $GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav());
            }
        }

        if (0 == $storypage) {
            $story['text'] = $story['text'] . '<br>' . $helper->getConfig('advertisement') . '<br>' . $articletext[$storypage];
        } else {
            $story['text'] = $articletext[$storypage];
        }
    } else {
        $story['text'] = $story['text'] . '<br>' . $helper->getConfig('advertisement') . '<br>' . $bodytext;
    }
}
// Publicité
$GLOBALS['xoopsTpl']->assign('advertisement', $helper->getConfig('advertisement'));

// ****************************************************************************************************************
/**
 * @param $matches
 * @return string
 */
function my_highlighter($matches)
{
    $color = $helper->getConfig('highlightcolor');
    if (0 !== strpos($color, '#')) {
        $color = '#' . $color;
    }

    return '<span style="font-weight: bolder; background-color: ' . $color . ';">' . $matches[0] . '</span>';
}

$highlight = false;
$highlight = $helper->getConfig('keywordshighlight');

if ($highlight && isset($_GET['keywords'])) {
    $keywords      = $myts->htmlSpecialChars(trim(urldecode($_GET['keywords'])));
    $h             = new Keyhighlighter($keywords, true, 'my_highlighter');
    $story['text'] = $h->highlight($story['text']);
}
// ****************************************************************************************************************

$story['poster'] = $article->uname();
if ($story['poster']) {
    $story['posterid']         = $article->uid();
    $story['poster']           = '<a href="' . XOOPS_URL . '/userinfo.php?uid=' . $story['posterid'] . '">' . $story['poster'] . '</a>';
    $tmp_user                  = new \XoopsUser($article->uid());
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
    if (3 != $helper->getConfig('displayname')) {
        $story['poster'] = $xoopsConfig['anonymous'];
    }
}
$story['morelink']  = '';
$story['adminlink'] = '';
unset($isadmin);

if (is_object($xoopsUser)) {
    if ($xoopsUser->isAdmin($xoopsModule->getVar('mid')) || ($helper->getConfig('authoredit') && $article->uid() == $xoopsUser->getVar('uid'))) {
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
$story['mail_link'] = 'mailto:?subject=' . sprintf(_MD_XNEWS_INTARTICLE, $xoopsConfig['sitename']) . '&amp;body=' . sprintf(_MD_XNEWS_INTARTFOUND, $xoopsConfig['sitename']) . ':  ' . XNEWS_MODULE_URL . '/article.php?storyid=' . $article->storyid();
$GLOBALS['xoopsTpl']->assign('lang_printerpage', _MD_XNEWS_PRINTERFRIENDLY);
$GLOBALS['xoopsTpl']->assign('lang_sendstory', _MD_XNEWS_SENDSTORY);
$GLOBALS['xoopsTpl']->assign('lang_pdfstory', _MD_XNEWS_MAKEPDF);

if ('' != $article->uname()) {
    $GLOBALS['xoopsTpl']->assign('lang_on', _ON);
    $GLOBALS['xoopsTpl']->assign('lang_postedby', _POSTEDBY);
} else {
    $GLOBALS['xoopsTpl']->assign('lang_on', '' . _MD_XNEWS_POSTED . ' ' . _ON . ' ');
    $GLOBALS['xoopsTpl']->assign('lang_postedby', '');
}

$GLOBALS['xoopsTpl']->assign('lang_reads', _READS);
$GLOBALS['xoopsTpl']->assign('mail_link', 'mailto:?subject=' . sprintf(_MD_XNEWS_INTARTICLE, $xoopsConfig['sitename']) . '&amp;body=' . sprintf(_MD_XNEWS_INTARTFOUND, $xoopsConfig['sitename']) . ':  ' . XNEWS_MODULE_URL . '/article.php?storyid=' . $article->storyid());

$GLOBALS['xoopsTpl']->assign('lang_attached_files', _MD_XNEWS_ATTACHEDFILES);

$sfiles     = new Xnews\Files();
$filesarr   = $newsfiles = [];
$filesarr   = $sfiles->getAllbyStory($storyid);
$filescount = count($filesarr);
$GLOBALS['xoopsTpl']->assign('attached_files_count', $filescount);
if ($filescount > 0) {
    foreach ($filesarr as $onefile) {
        if (false !== strpos($onefile->getMimetype(), 'image')) {
            $mime        = 'image';
            $newsfiles[] = ['visitlink' => XNEWS_ATTACHED_FILES_URL . '/' . $onefile->getDownloadname(), 'file_realname' => $onefile->getFileRealName(), 'file_mimetype' => $mime];
            //trigger_error($mime, E_USER_WARNING);
        } else {
            $newsfiles[] = [
                'file_id'           => $onefile->getFileid(),
                'visitlink'         => XNEWS_MODULE_URL . '/visit.php?fileid=' . $onefile->getFileid(),
                'file_realname'     => $onefile->getFileRealName(),
                'file_attacheddate' => formatTimestamp($onefile->getDate(), $dateformat),
                'file_mimetype'     => $onefile->getMimetype(),
                'file_downloadname' => XNEWS_ATTACHED_FILES_URL . '/' . $onefile->getDownloadname()
            ];
        }
    }
    $GLOBALS['xoopsTpl']->assign('attached_files', $newsfiles);
}

/**
 * Create page's title
 */
$complement = '';
if ($helper->getConfig('enhanced_pagenav') && (isset($arr_titles) && is_array($arr_titles) && isset($arr_titles, $storypage) && $storypage > 0)) {
    $complement = ' - ' . $arr_titles[$storypage];
}

if ($helper->getConfig('newsbythisauthor')) {
    $GLOBALS['xoopsTpl']->assign('news_by_the_same_author_link', sprintf("<a href='%s?uid=%d'>%s</a>", XNEWS_MODULE_URL . '/newsbythisauthor.php', $article->uid(), _MD_XNEWS_NEWSSAMEAUTHORLINK));
}

/**
 * Create a clickable path from the root to the current topic (if we are viewing a topic)
 * Actually this is not used in the default's templates but you can use it as you want
 * Uncomment the code to be able to use it
 */
if ($cfg['create_clickable_path']) {
    $mytree    = new \XoopsTree($GLOBALS['xoopsDB']->prefix('nw_topics'), 'topic_id', 'topic_pid');
    $topicpath = $mytree->getNicePathFromId($article->topicid(), 'topic_title', 'index.php?op=1');
    $GLOBALS['xoopsTpl']->assign('topic_path', $topicpath);
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
if ($helper->getConfig('showsummarytable')) {
    $GLOBALS['xoopsTpl']->assign('showsummary', true);
    $GLOBALS['xoopsTpl']->assign('lang_other_story', _MD_XNEWS_OTHER_ARTICLES);
    $count      = 0;
    $tmparticle = new Xnews\NewsStory();
    $infotips   = $helper->getConfig('infotips');
    $sarray     = $tmparticle->getAllPublished($cfg['article_summary_items_count'], 0, $helper->getConfig('restrictindex'));
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
            if (0 != $seo_enabled) {
                $story_path = nw_remove_accents($onearticle->title());
                $storyTitle = "<a href='" . nw_seo_UrlGenerator(_MD_XNEWS_SEO_ARTICLES, $onearticle->storyid(), $story_path) . "'>" . $onearticle->title() . '</a>';
                $GLOBALS['xoopsTpl']->append('summary', [
                    'story_id'        => $onearticle->storyid(),
                    'htmltitle'       => $htmltitle,
                    'infotips'        => $tooltips,
                    'story_title'     => $storyTitle,
                    'story_hits'      => $onearticle->counter(),
                    'story_published' => formatTimestamp($onearticle->published, $dateformat)
                ]);
            } else {
                $GLOBALS['xoopsTpl']->append('summary', [
                    'story_id'        => $onearticle->storyid(),
                    'htmltitle'       => $htmltitle,
                    'infotips'        => $tooltips,
                    'story_title'     => $onearticle->title(),
                    'story_hits'      => $onearticle->counter(),
                    'story_published' => formatTimestamp($onearticle->published, $dateformat)
                ]);
            }
        }
    }
    $GLOBALS['xoopsTpl']->assign('summary_count', $count);
    unset($tmparticle);
} else {
    $GLOBALS['xoopsTpl']->assign('showsummary', false);
}

/**
 * Show a link to go to the previous article and to the next article
 *
 * According to a module's option "showprevnextlink" ("Show Previous and Next link ?")
 * you can display, at the bottom of each article, two links used to navigate thru stories.
 * This feature uses the module's option "restrictindex" so that we can, or can't see
 * restricted stories
 */
if ($helper->getConfig('showprevnextlink')) {
    $GLOBALS['xoopsTpl']->assign('nav_links', $helper->getConfig('showprevnextlink'));
    $tmparticle = new Xnews\NewsStory();
    $nextId     = $previousId = -1;
    $next       = $previous = [];

    $previousTitle = $nextTitle = '';

    $next = $tmparticle->getNextArticle($storyid, $helper->getConfig('restrictindex'));
    if (count($next) > 0) {
        $nextId    = $next['storyid'];
        $nextTitle = $next['title'];
    }

    $previous = $tmparticle->getPreviousArticle($storyid, $helper->getConfig('restrictindex'));
    if (count($previous) > 0) {
        $previousId    = $previous['storyid'];
        $previousTitle = $previous['title'];
    }

    $GLOBALS['xoopsTpl']->assign('previous_story_id', $previousId);
    $GLOBALS['xoopsTpl']->assign('next_story_id', $nextId);
    if ($previousId > 0) {
        $GLOBALS['xoopsTpl']->assign('previous_story_title', $previousTitle);
        $hcontent .= sprintf("<link rel=\"Prev\" title=\"%s\" href=\"%s/\">\n", $previousTitle, XNEWS_MODULE_URL . '/article.php?storyid=' . $previousId);

        //DNPROSSI SEO
        $item_path = '';
        if (0 != $seo_enabled) {
            $item_path = nw_remove_accents($previousTitle);
        }
        $prevStory = "<a href='" . nw_seo_UrlGenerator(_MD_XNEWS_SEO_ARTICLES, $previousId, $item_path) . "' title='" . _MD_XNEWS_PREVIOUS_ARTICLE . "'>";
        $prevStory .= "<img src='" . XNEWS_MODULE_URL . "/assets/images/leftarrow22.png' border='0' alt='" . _MD_XNEWS_PREVIOUS_ARTICLE . "'></a>";
        $GLOBALS['xoopsTpl']->assign('previous_story', $prevStory);
    }

    if ($nextId > 0) {
        //DNPROSSI SEO
        $item_path = '';
        if (0 != $seo_enabled) {
            $item_path = nw_remove_accents($nextTitle);
        }
        $nextStory = "<a href='" . nw_seo_UrlGenerator(_MD_XNEWS_SEO_ARTICLES, $nextId, $item_path) . "' title='" . _MD_XNEWS_NEXT_ARTICLE . "'>";
        $nextStory .= "<img src='" . XNEWS_MODULE_URL . "/images/rightarrow22.png' border='0' alt='" . _MD_XNEWS_NEXT_ARTICLE . "'></a>";
        $GLOBALS['xoopsTpl']->assign('next_story', $nextStory);

        $hcontent .= sprintf("<link rel=\"Next\" title=\"%s\" href=\"%s/\">\n", $nextTitle, XNEWS_MODULE_URL . '/article.php?storyid=' . $nextId);
    }

    $GLOBALS['xoopsTpl']->assign('lang_previous_story', _MD_XNEWS_PREVIOUS_ARTICLE);
    $GLOBALS['xoopsTpl']->assign('lang_next_story', _MD_XNEWS_NEXT_ARTICLE);
    unset($tmparticle);
} else {
    $GLOBALS['xoopsTpl']->assign('nav_links', 0);
}

/**
 * Manage all the meta datas
 */
nw_CreateMetaDatas($article);

/**
 * Show a "Bookmark this article at these sites" block ?
 */
if ($helper->getConfig('bookmarkme')) {
    $GLOBALS['xoopsTpl']->assign('bookmarkme', true);
    $GLOBALS['xoopsTpl']->assign('encoded_title', rawurlencode($article->title()));
} else {
    $GLOBALS['xoopsTpl']->assign('bookmarkme', false);
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

if ($helper->getConfig('ratenews') && $other_test) {
    $GLOBALS['xoopsTpl']->assign('rates', true);
    $GLOBALS['xoopsTpl']->assign('lang_ratingc', _MD_XNEWS_RATINGC);
    $GLOBALS['xoopsTpl']->assign('lang_ratethisnews', _MD_XNEWS_RATETHISNEWS);
    $story['rating'] = number_format($article->rating(), 2);
    if (1 == $article->votes) {
        $story['votes'] = _MD_XNEWS_ONEVOTE;
    } else {
        $story['votes'] = sprintf(_MD_XNEWS_NUMVOTES, $article->votes);
    }
} else {
    $GLOBALS['xoopsTpl']->assign('rates', false);
}

$GLOBALS['xoopsTpl']->assign('story', $story);

//DNPROSSI - ADDED
$temp = $helper->getConfig('displaylinkicns');
$GLOBALS['xoopsTpl']->assign('display_icons', $temp);

// IN PROGRESS
// IN PROGRESS
// IN PORGRESS
//-------------------- DNPROSSI SEO ---------------------------
$item_path = '';
if (0 != $seo_enabled) {
    $item_path = nw_remove_accents($article->title());
}
$storyURL = nw_seo_UrlGenerator(_MD_XNEWS_SEO_ARTICLES, $storyid, $item_path);
$GLOBALS['xoopsTpl']->assign('story_url', $storyURL);

$print_item = '';
if (0 != $seo_enabled) {
    $print_item = nw_remove_accents(_MD_XNEWS_PRINTERFRIENDLY);
}
$printLink = "<a target='_blank' href='" . nw_seo_UrlGenerator(_MD_XNEWS_SEO_PRINT, $storyid, $print_item) . "' title='" . _MD_XNEWS_PRINTERFRIENDLY . "'>";
$printLink .= "<img src='" . XNEWS_MODULE_URL . "/images/print.png' width='28px' height='28px' border='0' alt='" . _MD_XNEWS_PRINTERFRIENDLY . "'></a>";
$GLOBALS['xoopsTpl']->assign('print_link', $printLink);

$pdf_item = '';
if (0 != $seo_enabled) {
    $pdf_item = nw_remove_accents($article->title());
}
$pdfLink = "<a target='_blank' href='" . nw_seo_UrlGenerator(_MD_XNEWS_SEO_PDF, $storyid, $pdf_item) . "' title='" . _MD_XNEWS_MAKEPDF . "'>";
$pdfLink .= "<img src='" . XNEWS_MODULE_URL . "/images/acrobat.png' width='28px' height='28px' border='0' alt='" . _MD_XNEWS_MAKEPDF . "'></a>";
$GLOBALS['xoopsTpl']->assign('pdf_link', $pdfLink);

if (0 != $seo_enabled) {
    $GLOBALS['xoopsTpl']->assign('urlrewrite', true);
} else {
    $GLOBALS['xoopsTpl']->assign('urlrewrite', false);
}

//-------------------- DNPROSSI SEO ---------------------------

// Added in version 1.63, TAGS
if ($helper->getConfig('tags')) {
    require_once XOOPS_ROOT_PATH . '/modules/tag/include/tagbar.php';
    $GLOBALS['xoopsTpl']->assign('tags', true);
    $GLOBALS['xoopsTpl']->assign('tagbar', tagBar($storyid, 0));
} else {
    $GLOBALS['xoopsTpl']->assign('tags', false);
}
$pdf_data['title']    = $article->title();
$pdf_data['subtitle'] = $article->topic_title();

$pdf_data['subsubtitle'] = $xoopsModule->name();
$pdf_data['date']        = ': ' . date(_DATESTRING, $article->published());

$memberHandler = xoops_getHandler('member');
$author        = $memberHandler->getUser($article->uid());
if ($author->getVar('name')) {
    $pdf_data['author'] = $author->getVar('name') . '(' . $author->getVar('uname') . ')';
} else {
    $pdf_data['author'] = $author->getVar('uname');
}

ob_start();
$GLOBALS['xoopsTpl']->display('db:xnews_article_pdf.tpl');
$content = ob_get_contents();
ob_end_clean();

$pdf_data['content'] = $content;

define('PDF_CREATOR', $GLOBALS['xoopsConfig']['sitename']);
define('PDF_AUTHOR', $pdf_data['author']);
define('PDF_HEADER_TITLE', $pdf_data['title']);
define('PDF_HEADER_STRING', $pdf_data['subtitle']);
define('PDF_HEADER_LOGO', 'logo.png');
define('K_PATH_IMAGES', XOOPS_ROOT_PATH . '/images/');

//$filename = XOOPS_ROOT_PATH . '/Frameworks/tcpdf/config/lang/' . _LANGCODE . '.php';
//if (file_exists($filename)) {
//    require_once $filename;
//} else {
//    require_once XOOPS_ROOT_PATH . '/Frameworks/tcpdf/config/lang/en.php';
//}

//DNPROSSI Added - xlanguage installed and active
$moduleHandler = xoops_getHandler('module');
$xlanguage     = $moduleHandler->getByDirname('xlanguage');
if (is_object($xlanguage) && true === $xlanguage->getVar('isactive')) {
    $xlang = true;
} else {
    $xlang = false;
}

$content = '';
$content .= '<b><i><u>'
            . $myts->undoHtmlSpecialChars($pdf_data['title'])
            . '</u></i></b><br><b>'
            . $myts->undoHtmlSpecialChars($pdf_data['subtitle'])
            . '</b><br>'
            . _POSTEDBY
            . ' : '
            . $myts->undoHtmlSpecialChars($pdf_data['author'])
            . '<br>'
            . _OBJS_POSTEDON
            . ' '
            . $pdf_data['date']
            . '<br><br><br>';
//$content .= $myts->undoHtmlSpecialChars($article->hometext()) . '<br><br><br>' . $myts->undoHtmlSpecialChars($article->bodytext());
//$content = str_replace('[pagebreak]','<br>',$content);
$content .= $myts->undoHtmlSpecialChars($pdf_data['content']);

//DNPROSSI Added - Get correct language and remove tags from text to be sent to PDF
if (true === $xlang) {
    require_once XOOPS_ROOT_PATH . '/modules/xlanguage/include/functions.php';
    $content = xlanguage_ml($content);
}

$pdf          = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$doc_title    = $myts->undoHtmlSpecialChars($pdf_data['title']);
$doc_keywords = 'XOOPS';

//DNPROSSI ADDED gbsn00lp chinese to tcpdf fonts dir
if (_LANGCODE === 'cn') {
    $pdf->SetFont('gbsn00lp', '', 10);
}

// set document information
$pdf->SetCreator($pdf_data['author']);
$pdf->SetAuthor($pdf_data['author']);
$pdf->SetTitle($pdf_data['title']);
$pdf->SetSubject($pdf_data['subtitle']);
$pdf->SetKeywords($doc_keywords);

// set default header data
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
//$pdf->SetHeaderData('', '', $firstLine, $secondLine);
//$pdf->SetHeaderData('logo_example.png', '25', $firstLine, $secondLine);
//UTF-8 char sample
//$pdf->SetHeaderData(PDF_HEADER_LOGO, '25', 'Éèéàùìò', $article->title());

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 10, PDF_MARGIN_RIGHT);
//set auto page breaks
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(1); //set image scale factor

//DNPROSSI ADDED FOR SCHINESE
if (_LANGCODE === 'cn') {
    $pdf->setHeaderFont(['gbsn00lp', '', 10]);
    $pdf->setFooterFont(['gbsn00lp', '', 10]);
} else {
    $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
    $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
}

$pdf->setLanguageArray($l); //set language items

//initialize document
$pdf->AliasNbPages();

// ***** For Testing Purposes
/*$pdf->AddPage();

// print a line using Cell()
*$pdf->Cell(0, 10, K_PATH_URL. '  ---- Path Url', 1, 1, 'C');
$pdf->Cell(0, 10, K_PATH_MAIN. '  ---- Path Main', 1, 1, 'C');
$pdf->Cell(0, 10, K_PATH_FONTS. '  ---- Path Fonts', 1, 1, 'C');
$pdf->Cell(0, 10, K_PATH_IMAGES. '  ---- Path Images', 1, 1, 'C');
*/
// ***** End Test

$pdf->AddPage();
$pdf->writeHTML($content, true, 0);
//Added for buffer error in TCPDF when using chinese charset
ob_end_clean();
$pdf->Output();
