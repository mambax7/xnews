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

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

/**
 * Solves issue when upgrading xoops version
 * Paths not set and block would not work
 */
if (!defined('XNEWS_MODULE_PATH')) {
    define('XNEWS_SUBPREFIX', 'nw');
    define('XNEWS_MODULE_DIRNAME', 'xnews');
    define('XNEWS_MODULE_PATH', XOOPS_ROOT_PATH . '/modules/' . XNEWS_MODULE_DIRNAME);
    define('XNEWS_MODULE_URL', XOOPS_URL . '/modules/' . XNEWS_MODULE_DIRNAME);
    define('XNEWS_UPLOADS_NEWS_PATH', XOOPS_ROOT_PATH . '/uploads/' . XNEWS_MODULE_DIRNAME);
    define('XNEWS_TOPICS_FILES_PATH', XOOPS_ROOT_PATH . '/uploads/' . XNEWS_MODULE_DIRNAME . '/topics');
    define('XNEWS_ATTACHED_FILES_PATH', XOOPS_ROOT_PATH . '/uploads/' . XNEWS_MODULE_DIRNAME . '/attached');
    define('XNEWS_TOPICS_FILES_URL', XOOPS_URL . '/uploads/' . XNEWS_MODULE_DIRNAME . '/topics');
    define('XNEWS_ATTACHED_FILES_URL', XOOPS_URL . '/uploads/' . XNEWS_MODULE_DIRNAME . '/attached');
}

require_once XNEWS_MODULE_PATH . '/class/class.newsstory.php';
require_once XNEWS_MODULE_PATH . '/include/functions.php';

/**
 * @param $options
 * @return array
 */
function nw_b_news_randomnews_show($options)
{
    $myts                = MyTextSanitizer::getInstance();
    $xnews               = XnewsXnews::getInstance();
    $nw_NewsStoryHandler = new nw_NewsStory();
    //
    $block         = [];
    $block['sort'] = $options[0];

    //DNPROSSI Added - xlanguage installed and active
    $moduleHandler = xoops_getHandler('module');
    $xlanguage     = $moduleHandler->getByDirname('xlanguage');
    if (is_object($xlanguage) && true === $xlanguage->getVar('isactive')) {
        $xlang = true;
    } else {
        $xlang = false;
    }

    $tmpstory   = new nw_NewsStory;
    $restricted = $xnews->getConfig('restrictindex');
    $dateformat = $xnews->getConfig('dateformat');
    $infotips   = $xnews->getConfig('infotips');
    if ('' == $dateformat) {
        $dateformat = 's';
    }
    if (0 == $options[4]) {
        $stories = $tmpstory->getRandomNews($options[1], 0, $restricted, 0, 1, $options[0]);
    } else {
        $topics  = array_slice($options, 4);
        $stories = $tmpstory->getRandomNews($options[1], 0, $restricted, $topics, 1, $options[0]);
    }
    unset($tmpstory);
    if (0 == count($stories)) {
        return '';
    }
    foreach ($stories as $story) {
        $news  = [];
        $title = $story->title();
        if (strlen($title) > $options[2]) {
            //DNPROSSI Added - xlanguage installed and active
            $title = $thisstory->hometext;
            if (true === $xlang) {
                require_once XOOPS_ROOT_PATH . '/modules/xlanguage/include/functions.php';
                $title = xlanguage_ml($title);
            }
            //DNPROSSI changed xoops_substr to mb_substr for utf-8 support
            $title = mb_substr($title, 0, $options[2] + 3, 'UTF-8');
        }
        $news['title']       = $title;
        $news['id']          = $story->storyid();
        $news['date']        = formatTimestamp($story->published(), $dateformat);
        $news['hits']        = $story->counter();
        $news['rating']      = $story->rating();
        $news['votes']       = $story->votes();
        $news['author']      = sprintf('%s %s', _POSTEDBY, $story->uname());
        $news['topic_title'] = $story->topic_title();
        $news['topic_color'] = '#' . $myts->displayTarea($story->topic_color);

        if ($options[3] > 0) {
            $html = 1 == $story->nohtml() ? 0 : 1;
            //$news['teaser'] = nw_truncate_tagsafe($myts->displayTarea($story->hometext, $html), $options[3]+3);
            //DNPROSSI New truncate function - now works correctly with html and utf-8
            $news['teaser']   = nw_truncate($story->hometext(), $options[3] + 3, '...', true, $html);
            $news['infotips'] = '';
        } else {
            $news['teaser'] = '';
            if ($infotips > 0) {
                $news['infotips'] = ' title="' . nw_make_infotips($story->hometext()) . '"';
            } else {
                $news['infotips'] = '';
            }
        }
        $block['stories'][] = $news;
    }
    //DNPROSSI ADDED
    $block['newsmodule_url'] = XNEWS_MODULE_URL;

    $block['lang_read_more'] = _MB_NW_READMORE;

    // DNPROSSI SEO
    $seo_enabled = $xnews->getConfig('seo_enable');
    if (0 != $seo_enabled) {
        $block['urlrewrite'] = 'true';
    } else {
        $block['urlrewrite'] = 'false';
    }

    return $block;
}

/**
 * @param $options
 * @return string
 */
function nw_b_news_randomnews_edit($options)
{
    $form = _MB_NW_ORDER . "&nbsp;<select name='options[]'>";
    $form .= "<option value='published'";
    if ('published' === $options[0]) {
        $form .= " selected='selected'";
    }
    $form .= '>' . _MB_NW_DATE . "</option>\n";

    $form .= "<option value='counter'";
    if ('counter' === $options[0]) {
        $form .= " selected='selected'";
    }
    $form .= '>' . _MB_NW_HITS . '</option>';

    $form .= "<option value='rating'";
    if ('rating' === $options[0]) {
        $form .= " selected='selected'";
    }
    $form .= '>' . _MB_NW_RATE . '</option>';
    $form .= "</select>\n";
    $form .= '&nbsp;' . _MB_NW_DISP . "&nbsp;<input type='text' name='options[]' value='" . $options[1] . "'>&nbsp;" . _MB_NW_ARTCLS;
    $form .= '&nbsp;<br><br>' . _MB_NW_CHARS . "&nbsp;<input type='text' name='options[]' value='" . $options[2] . "'>&nbsp;" . _MB_NW_LENGTH . '<br><br>';
    $form .= _MB_NW_TEASER . " <input type='text' name='options[]' value='" . $options[3] . "'>" . _MB_NW_LENGTH;
    $form .= '<br><br>' . _MB_NW_SPOTLIGHT_TOPIC . "<br><select id='options[4]' name='options[]' multiple='multiple'>";

    require_once XNEWS_MODULE_PATH . '/class/deprecate/xnewsstory.php';
    $xt                    = new XnewsDeprecateTopic($GLOBALS['xoopsDB']->prefix('nw_topics'));
    $alltopics             = $xt->getTopicsList();
    $alltopics[0]['title'] = _MB_NW_SPOTLIGHT_ALL_TOPICS;
    ksort($alltopics);
    $size = count($options);
    foreach ($alltopics as $topicid => $topic) {
        $sel = '';
        for ($i = 4; $i < $size; $i++) {
            if ($options[$i] == $topicid) {
                $sel = " selected='selected'";
            }
        }
        $form .= "<option value='$topicid'$sel>" . $topic['title'] . '</option>';
    }
    $form .= '</select><br>';

    return $form;
}

/**
 * @param $options
 */
function nw_b_news_randomnews_onthefly($options)
{
    $options = explode('|', $options);
    $block   =& nw_b_news_randomnews_show($options);

    $tpl = new XoopsTpl();
    $tpl->assign('block', $block);
    $tpl->display('db:nw_news_block_moderate.tpl');
}
