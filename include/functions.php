<?php
defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

/**
 * Checks if a user is admin of Module_skeleton
 *
 * @return boolean
 */
function xnews_userIsAdmin()
{
    global $xoopsUser;
    $xnews = XnewsXnews::getInstance();

    static $xnews_isAdmin;
    if (isset($xnews_isAdmin)) {
        return $xnews_isAdmin;
    }

    $xnews_isAdmin = (!is_object($xoopsUser)) ? false : $xoopsUser->isAdmin($xnews->getModule()->getVar('mid'));

    return $xnews_isAdmin;
}

function xnews_collapsableBar($tablename = '', $iconname = '')
{
    $xnews = XnewsXnews::getInstance(); ?>
    <script type="text/javascript"><!--
        function goto_URL(object) {
            window.location.href = object.options[object.selectedIndex].value;
        }

        function toggle(id) {
            if (document.getElementById) {
                obj = document.getElementById(id);
            }
            if (document.all) {
                obj = document.all[id];
            }
            if (document.layers) {
                obj = document.layers[id];
            }
            if (obj) {
                if (obj.style.display == "none") {
                    obj.style.display = "";
                } else {
                    obj.style.display = "none";
                }
            }
            return false;
        }

        var iconClose = new Image();
        iconClose.src = '../assets/images/close12.gif';
        var iconOpen = new Image();
        iconOpen.src = '../assets/images/open12.gif';

        function toggleIcon(iconName) {
            if (document.images[iconName].src == window.iconOpen.src) {
                document.images[iconName].src = window.iconClose.src;
            } else if (document.images[iconName].src == window.iconClose.src) {
                document.images[iconName].src = window.iconOpen.src;
            }
            return;
        }

        //-->
    </script>
    <?php
    echo "<h4 style=\"color: #2F5376; margin: 6px 0 0 0; \"><a href='#' onClick=\"toggle('" . $tablename . "'); toggleIcon('" . $iconname . "');\">";
}

/**
 * Check if a module exist and return module verision
 * @author luciorota
 *
 * @param string $dirname
 * @return boolean|int      FALSE if module is not installed or not active, module version if module is installed
 */
function xnews_checkModule($dirname)
{
    if (!xoops_isActiveModule($dirname)) {
        return false;
    }
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname($dirname);

    return $module->getVar('version');
}

/**
 * Updates rating data in item table for a given item
 *
 * @package       News
 * @author        Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 */
function nw_updaterating($storyid)
{
    global $xoopsDB;
    $xnews = XnewsXnews::getInstance();

    $query       = 'SELECT rating FROM ' . $xoopsDB->prefix('nw_stories_votedata') . ' WHERE storyid = ' . $storyid;
    $voteresult  = $xoopsDB->query($query);
    $votesDB     = $xoopsDB->getRowsNum($voteresult);
    $totalrating = 0;
    while (list($rating) = $xoopsDB->fetchRow($voteresult)) {
        $totalrating += $rating;
    }
    $finalrating = $totalrating / $votesDB;
    $finalrating = number_format($finalrating, 4);
    $sql         = sprintf('UPDATE %s SET rating = %u, votes = %u WHERE storyid = %u', $xoopsDB->prefix('nw_stories'), $finalrating, $votesDB, $storyid);
    $xoopsDB->queryF($sql);
}

/**
 * Internal function for permissions
 *
 * Returns a list of all the permitted topics Ids for the current user
 *
 * @return array $topics    Permitted topics Ids
 *
 * @package       News
 * @author        Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 */
function nw_MygetItemIds($permtype = 'nw_view')
{
    global $xoopsUser;
    static $tblperms = array();
    $xnews = XnewsXnews::getInstance();

    if (is_array($tblperms) && array_key_exists($permtype, $tblperms)) {
        return $tblperms[$permtype];
    }

    $moduleHandler       = xoops_getHandler('module');
    $newsModule          = $moduleHandler->getByDirname(XNEWS_MODULE_DIRNAME);
    $groups              = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gpermHandler        = xoops_getHandler('groupperm');
    $topics              = $gpermHandler->getItemIds($permtype, $groups, $newsModule->getVar('mid'));
    $tblperms[$permtype] = $topics;

    return $topics;
}

function nw_html2text($document)
{
    // PHP Manual:: function preg_replace
    // $document should contain an HTML document.
    // This will remove HTML tags, javascript sections
    // and white space. It will also convert some
    // common HTML entities to their text equivalent.

    $search = array(
        "'<script[^>]*?" . ">.*?</script>'si", // Strip out javascript
        "'<[\/\!]*?[^<>]*?" . ">'si", // Strip out HTML tags
        "'([\r\n])[\s]+'", // Strip out white space
        "'&(quot|#34);'i", // Replace HTML entities
        "'&(amp|#38);'i",
        "'&(lt|#60);'i",
        "'&(gt|#62);'i",
        "'&(nbsp|#160);'i",
        "'&(iexcl|#161);'i",
        "'&(cent|#162);'i",
        "'&(pound|#163);'i",
        "'&(copy|#169);'i",
        "'&#(\d+);'e"
    ); // evaluate as php

    $replace = array(
        '',
        '',
        "\\1",
        '"',
        '&',
        '<',
        '>',
        ' ',
        chr(161),
        chr(162),
        chr(163),
        chr(169),
        "chr(\\1)"
    );

    $text = preg_replace($search, $replace, $document);

    return $text;
}

/**
 * Is Xoops 2.3.x ?
 *
 * @return boolean need to say it ?
 */
function nw_isX23()
{
    $x23 = false;
    $xv  = str_replace('XOOPS ', '', XOOPS_VERSION);
    if (substr($xv, 2, 1) >= '3') {
        $x23 = true;
    }

    return $x23;
}

/**
 * version of xoops
 *
 * @return string
 */
function nw_xoops_version()
{
    $xv = '';
    $xv = str_replace('XOOPS ', '', XOOPS_VERSION);
    $xv = substr($xv, 0, 3);

    return $xv;
}

/**
 * Retreive an editor according to the module's option "form_options"
 * @package       News
 * @author        Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 */
//function &nw_getWysiwygForm($caption, $name, $value = '', $rows, $cols, $supplemental='')
function &nw_getWysiwygForm($caption, $name, $value, $rows, $cols, $width, $height, $supplemental)
{
    $xnews = XnewsXnews::getInstance();
    //
    $editor_option            = strtolower($xnews->getConfig('form_options'));
    $editor                   = false;
    $editor_configs           = array();
    $editor_configs['name']   = $name;
    $editor_configs['value']  = $value;
    $editor_configs['rows']   = $rows;
    $editor_configs['cols']   = $cols;
    $editor_configs['width']  = $width;
    $editor_configs['height'] = $height;
    $editor_configs['editor'] = $editor_option;

    $editor = new XoopsFormEditor($caption, $name, $editor_configs);

    return $editor;
}

/**
 * Internal function
 *
 * @package       News
 * @author        Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 */
function nw_DublinQuotes($text)
{
    return str_replace('"', ' ', $text);
}

/**
 * Creates all the meta datas :
 * - For Mozilla/Netscape and Opera the site navigation's bar
 * - The Dublin's Core Metadata
 * - The link for Firefox 2 micro summaries
 * - The meta keywords
 * - The meta description
 *
 * @package       News
 * @author        Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 */
function nw_CreateMetaDatas($story = null)
{
    global $xoopsConfig, $xoTheme, $xoopsTpl;
    $xnews = XnewsXnews::getInstance();

    $content = '';
    $myts    = MyTextSanitizer::getInstance();
    require_once XNEWS_MODULE_PATH . '/class/class.newstopic.php';

    /**
     * Firefox and Opera Navigation's Bar
     */
    if ($xnews->getConfig('sitenavbar')) {
        $content .= sprintf("<link rel=\"Home\" title=\"%s\" href=\"%s/\">\n", $xoopsConfig['sitename'], XOOPS_URL);
        $content .= sprintf("<link rel=\"Contents\" href=\"%s\">\n", XNEWS_MODULE_URL . '/index.php');
        $content .= sprintf("<link rel=\"Search\" href=\"%s\">\n", XOOPS_URL . '/search.php');
        $content .= sprintf("<link rel=\"Glossary\" href=\"%s\">\n", XNEWS_MODULE_URL . '/archive.php');
        $content .= sprintf("<link rel=\"%s\" href=\"%s\">\n", $myts->htmlSpecialChars(_MA_NW_SUBMITNEWS), XNEWS_MODULE_URL . '/submit.php');
        $content .= sprintf("<link rel=\"alternate\" type=\"application/rss+xml\" title=\"%s\" href=\"%s/\">\n", $xoopsConfig['sitename'], XOOPS_URL . '/backend.php');

        // Create chapters
        require_once XOOPS_ROOT_PATH . '/class/tree.php';
        require_once XNEWS_MODULE_PATH . '/class/class.newstopic.php';
        $xt         = new nw_NewsTopic();
        $allTopics  = $xt->getAllTopics($xnews->getConfig('restrictindex'));
        $topic_tree = new XoopsObjectTree($allTopics, 'topic_id', 'topic_pid');
        $topics_arr = $topic_tree->getAllChild(0);
        foreach ($topics_arr as $onetopic) {
            $content .= sprintf("<link rel=\"Chapter\" title=\"%s\" href=\"%s\">\n", $onetopic->topic_title(), XNEWS_MODULE_URL . '/index.php?topic_id=' . $onetopic->topic_id());
        }
    }

    /**
     * Meta Keywords and Description
     * If you have set this module's option to 'yes' and if the information was entered, then they are rendered in the page else they are computed
     */
    $meta_keywords = '';
    if (isset($story) && is_object($story)) {
        if (xoops_trim($story->keywords()) != '') {
            $meta_keywords = $story->keywords();
        } else {
            $meta_keywords = nw_createmeta_keywords($story->hometext() . ' ' . $story->bodytext());
        }
        if (xoops_trim($story->description()) != '') {
            $meta_description = strip_tags($story->description);
        } else {
            $meta_description = strip_tags($story->title);
        }
        if (isset($xoTheme) && is_object($xoTheme)) {
            $xoTheme->addMeta('meta', 'keywords', $meta_keywords);
            $xoTheme->addMeta('meta', 'description', $meta_description);
        } elseif (isset($xoopsTpl) && is_object($xoopsTpl)) { // Compatibility for old Xoops versions
            $xoopsTpl->assign('xoops_meta_keywords', $meta_keywords);
            $xoopsTpl->assign('xoops_meta_description', $meta_description);
        }
    }

    /**
     * Dublin Core's meta datas
     */
    if ($xnews->getConfig('dublincore') && isset($story) && is_object($story)) {
        $configHandler         = xoops_getHandler('config');
        $xoopsConfigMetaFooter = $configHandler->getConfigsByCat(XOOPS_CONF_METAFOOTER);
        $content               .= '<meta name="DC.Title" content="' . nw_DublinQuotes($story->title()) . "\">\n";
        $content               .= '<meta name="DC.Creator" content="' . nw_DublinQuotes($story->uname()) . "\">\n";
        $content               .= '<meta name="DC.Subject" content="' . nw_DublinQuotes($meta_keywords) . "\">\n";
        $content               .= '<meta name="DC.Description" content="' . nw_DublinQuotes($story->title()) . "\">\n";
        $content               .= '<meta name="DC.Publisher" content="' . nw_DublinQuotes($xoopsConfig['sitename']) . "\">\n";
        $content               .= '<meta name="DC.Date.created" scheme="W3CDTF" content="' . date('Y-m-d', $story->created) . "\">\n";
        $content               .= '<meta name="DC.Date.issued" scheme="W3CDTF" content="' . date('Y-m-d', $story->published) . "\">\n";
        $content               .= '<meta name="DC.Identifier" content="' . XNEWS_MODULE_URL . '/article.php?storyid=' . $story->storyid() . "\">\n";
        $content               .= '<meta name="DC.Source" content="' . XOOPS_URL . "\">\n";
        $content               .= '<meta name="DC.Language" content="' . _LANGCODE . "\">\n";
        $content               .= '<meta name="DC.Relation.isReferencedBy" content="' . XNEWS_MODULE_URL . '/index.php?topic_id=' . $story->topicid() . "\">\n";
        if (isset($xoopsConfigMetaFooter['meta_copyright'])) {
            $content .= '<meta name="DC.Rights" content="' . nw_DublinQuotes($xoopsConfigMetaFooter['meta_copyright']) . "\">\n";
        }
    }

    /**
     * Firefox 2 micro summaries
     */
    if ($xnews->getConfig('firefox_microsummaries')) {
        $content .= sprintf("<link rel=\"microsummary\" href=\"%s\">\n", XNEWS_MODULE_URL . '/micro_summary.php');
    }

    if (isset($xoopsTpl) && is_object($xoopsTpl)) {
        $xoopsTpl->assign('xoops_module_header', $content);
    }
}

/**
 * Create the meta keywords based on the content
 *
 * @package       News
 * @author        Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 */
function nw_createmeta_keywords($content)
{
    require_once XNEWS_MODULE_PATH . '/class/blacklist.php';
    require_once XNEWS_MODULE_PATH . '/class/registryfile.php';
    $xnews = XnewsXnews::getInstance();

    if (!$cfg['meta_keywords_auto_generate']) {
        return '';
    }
    $registry = new nw_registryfile('nw_metagen_options.txt');
    $tcontent = '';
    $tcontent = $registry->getfile();
    if (xoops_trim($tcontent) != '') {
        list($keywordscount, $keywordsorder) = explode(',', $tcontent);
    } else {
        $keywordscount = $cfg['meta_keywords_count'];
        $keywordsorder = $cfg['meta_keywords_order'];
    }

    $tmp = array();
    // Search for the "Minimum keyword length"
    if (isset($_SESSION['nw_keywords_limit'])) {
        $limit = $_SESSION['nw_keywords_limit'];
    } else {
        $configHandler                 = xoops_getHandler('config');
        $xoopsConfigSearch             = $configHandler->getConfigsByCat(XOOPS_CONF_SEARCH);
        $limit                         = $xoopsConfigSearch['keyword_min'];
        $_SESSION['nw_keywords_limit'] = $limit;
    }
    $myts            = MyTextSanitizer::getInstance();
    $content         = str_replace('<br>', ' ', $content);
    $content         = $myts->undoHtmlSpecialChars($content);
    $content         = strip_tags($content);
    $content         = strtolower($content);
    $search_pattern  = array('&nbsp;', "\t", "\r\n", "\r", "\n", ',', '.', "'", ';', ':', ')', '(', '"', '?', '!', '{', '}', '[', ']', '<', '>', '/', '+', '-', '_', '\\', '*');
    $replace_pattern = array(' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
    $content         = str_replace($search_pattern, $replace_pattern, $content);
    $keywords        = explode(' ', $content);
    switch ($keywordsorder) {
        case 0: // Ordre d'apparition dans le texte
            $keywords = array_unique($keywords);
            break;
        case 1: // Ordre de fréquence des mots
            $keywords = array_count_values($keywords);
            asort($keywords);
            $keywords = array_keys($keywords);
            break;
        case 2: // Ordre inverse de la fréquence des mots
            $keywords = array_count_values($keywords);
            arsort($keywords);
            $keywords = array_keys($keywords);
            break;
    }
    // Remove black listed words
    $metablack = new nw_blacklist();
    $words     = $metablack->getAllKeywords();
    $keywords  = $metablack->remove_blacklisted($keywords);

    foreach ($keywords as $keyword) {
        if (strlen($keyword) >= $limit && !is_numeric($keyword)) {
            $tmp[] = $keyword;
        }
    }
    $tmp = array_slice($tmp, 0, $keywordscount);
    if (count($tmp) > 0) {
        return implode(',', $tmp);
    } else {
        if (!isset($configHandler) || !is_object($configHandler)) {
            $configHandler = xoops_getHandler('config');
        }
        $xoopsConfigMetaFooter = $configHandler->getConfigsByCat(XOOPS_CONF_METAFOOTER);
        if (isset($xoopsConfigMetaFooter['meta_keywords'])) {
            return $xoopsConfigMetaFooter['meta_keywords'];
        } else {
            return '';
        }
    }
}

/**
 * Remove module's cache
 *
 * @package       News
 * @author        Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 */
function nw_updateCache()
{
    global $xoopsModule;
    $xnews = XnewsXnews::getInstance();

    $folder  = $xoopsModule->getVar('dirname');
    $tpllist = array();
    require_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';
    require_once XOOPS_ROOT_PATH . '/class/template.php';
    $tplfileHandler = xoops_getHandler('tplfile');
    $tpllist        = $tplfileHandler->find(null, null, null, $folder);
    $xoopsTpl       = new XoopsTpl();
    xoops_template_clear_module_cache($xoopsModule->getVar('mid')); // Clear module's blocks cache

    // Remove cache for each page.
    foreach ($tpllist as $onetemplate) {
        if ($onetemplate->getVar('tpl_type') == 'module') {
            // Note, I've been testing all the other methods (like the one of Smarty) and none of them run, that's why I have used this code
            $files_del = array();
            $files_del = glob(XOOPS_CACHE_PATH . '/*' . $onetemplate->getVar('tpl_file') . '*');
            if (count($files_del) > 0) {
                foreach ($files_del as $one_file) {
                    unlink($one_file);
                }
            }
        }
    }
}

/**
 * Verify that a mysql table exists
 *
 * @package       News
 * @author        Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 */
function nw_TableExists($tablename)
{
    global $xoopsDB;
    //
    $result = $xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");

    return ($xoopsDB->getRowsNum($result) > 0);
}

/**
 * Verify that a field exists inside a mysql table
 *
 * @package       News
 * @author        Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 */
function nw_FieldExists($fieldname, $table)
{
    global $xoopsDB;
    //
    $result = $xoopsDB->queryF("SHOW COLUMNS FROM $table LIKE '$fieldname'");

    return ($xoopsDB->getRowsNum($result) > 0);
}

/**
 * Add a field to a mysql table
 *
 * @package       News
 * @author        Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 */
function nw_AddField($field, $table)
{
    global $xoopsDB;
    //
    $result = $xoopsDB->queryF('ALTER TABLE ' . $table . " ADD $field;");

    return $result;
}

/**
 * Verify that the current user is a member of the Admin group
 */
function nw_is_admin_group()
{
    global $xoopsUser, $xoopsModule;
    $xnews = XnewsXnews::getInstance();
    //
    if (is_object($xoopsUser)) {
        if (in_array('1', $xoopsUser->getGroups())) {
            return true;
        } else {
            if ($xoopsUser->isAdmin($xoopsModule->mid())) {
                return true;
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
}

/**
 * Verify if the current "user" is a bot or not
 *
 * If you have a problem with this function, insert the folowing code just before the line if(isset($_SESSION['nw_cache_bot'])) { :
 * return false;
 *
 * @package       News
 * @author        Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 */
function nw_isbot()
{
    if (isset($_SESSION['nw_cache_bot'])) {
        return $_SESSION['nw_cache_bot'];
    } else {
        // Add here every bot you know separated by a pipe | (not matter with the upper or lower cases)
        // If you want to see the result for yourself, add your navigator's user agent at the end (mozilla for example)
        $botlist      = 'AbachoBOT|Arachnoidea|ASPSeek|Atomz|cosmos|crawl25-public.alexa.com|CrawlerBoy Pinpoint.com|Crawler|DeepIndex|EchO!|exabot|Excalibur Internet Spider|FAST-WebCrawler|Fluffy the spider|GAIS Robot/1.0B2|GaisLab data gatherer|Google|Googlebot-Image|googlebot|Gulliver|ia_archiver|Infoseek|Links2Go|Lycos_Spider_(modspider)|Lycos_Spider_(T-Rex)|MantraAgent|Mata Hari|Mercator|MicrosoftPrototypeCrawler|Mozilla@somewhere.com|MSNBOT|NEC Research Agent|NetMechanic|Nokia-WAPToolkit|nttdirectory_robot|Openfind|Oracle Ultra Search|PicoSearch|Pompos|Scooter|Slider_Search_v1-de|Slurp|Slurp.so|SlySearch|Spider|Spinne|SurferF3|Surfnomore Spider|suzuran|teomaagent1|TurnitinBot|Ultraseek|VoilaBot|vspider|W3C_Validator|Web Link Validator|WebTrends|WebZIP|whatUseek_winona|WISEbot|Xenu Link Sleuth|ZyBorg';
        $botlist      = strtoupper($botlist);
        $currentagent = strtoupper(xoops_getenv('HTTP_USER_AGENT'));
        $retval       = false;
        $botarray     = explode('|', $botlist);
        foreach ($botarray as $onebot) {
            if (strstr($currentagent, $onebot)) {
                $retval = true;
                break;
            }
        }
    }
    $_SESSION['nw_cache_bot'] = $retval;

    return $retval;
}

/**
 * Create an infotip
 *
 * @package       News
 * @author        Instant Zero (http://xoops.instant-zero.com)
 * @copyright (c) Instant Zero
 */
function nw_make_infotips($text)
{
    $infotips = $xnews->getConfig('infotips');
    if ($infotips > 0) {
        $myts = MyTextSanitizer::getInstance();

        //DNPROSSI changed xoops_substr to mb_substr for utf-8 support
        return $myts->htmlSpecialChars(mb_substr(strip_tags($text), 0, $infotips, 'UTF-8'));
    }
}

/**
 * @author   Monte Ohrt <monte at ohrt dot com>, modified by Amos Robinson
 *           <amos dot robinson at gmail dot com>
 */
function nw_close_tags($string)
{
    // match opened tags
    if (preg_match_all('/<([a-z\:\-]+)[^\/]>/', $string, $start_tags)) {
        $start_tags = $start_tags[1];
        // match closed tags
        if (preg_match_all('/<\/([a-z]+)>/', $string, $end_tags)) {
            $complete_tags = array();
            $end_tags      = $end_tags[1];

            foreach ($start_tags as $key => $val) {
                $posb = array_search($val, $end_tags);
                if (is_integer($posb)) {
                    unset($end_tags[$posb]);
                } else {
                    $complete_tags[] = $val;
                }
            }
        } else {
            $complete_tags = $start_tags;
        }

        $complete_tags = array_reverse($complete_tags);
        for ($i = 0; $i < count($complete_tags); $i++) {
            $string .= '</' . $complete_tags[$i] . '>';
        }
    }

    return $string;
}

/**
 * Smarty truncate_tagsafe modifier plugin
 *
 * Type:     modifier<br>
 * Name:     truncate_tagsafe<br>
 * Purpose:  Truncate a string to a certain length if necessary,
 *           optionally splitting in the middle of a word, and
 *           appending the $etc string or inserting $etc into the middle.
 *           Makes sure no tags are left half-open or half-closed
 *           (e.g. "Banana in a <a...")
 * @author   Monte Ohrt <monte at ohrt dot com>, modified by Amos Robinson
 *           <amos dot robinson at gmail dot com>
 * @param string
 * @param integer
 * @param string
 * @param boolean
 * @param boolean
 * @return string
 */
function nw_truncate_tagsafe($string, $length = 80, $etc = '...', $break_words = false)
{
    if ($length == 0) {
        return '';
    }
    if (strlen($string) > $length) {
        $length -= strlen($etc);
        if (!$break_words) {
            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length + 1));
            $string = preg_replace('/<[^>]*$/', '', $string);
            $string = nw_close_tags($string);
        }

        return $string . $etc;
    } else {
        return $string;
    }
}

/**
 * Resize a Picture to some given dimensions (using the wideImage library)
 *
 * @param string  $src_path      Picture's source
 * @param         string         $              dst_path Picture's destination
 * @param integer $param_width   Maximum picture's width
 * @param integer $param_height  Maximum picture's height
 * @param boolean $keep_original Do we have to keep the original picture ?
 * @param string  $fit           Resize mode (see the wideImage library for more information)
 */
function nw_resizePicture($src_path, $dst_path, $param_width, $param_height, $keep_original = false, $fit = 'inside')
{
    $xnews = XnewsXnews::getInstance();
    require_once XNEWS_MODULE_PATH . '/class/wideimage/WideImage.inc.php';
    //
    $resize            = true;
    $pictureDimensions = getimagesize($src_path);
    if (is_array($pictureDimensions)) {
        $pictureWidth  = $pictureDimensions[0];
        $pictureHeight = $pictureDimensions[1];
        if ($pictureWidth < $param_width && $pictureHeight < $param_height) {
            $resize = false;
        }
    }

    $img = wiImage::load($src_path);
    if ($resize) {
        $result = $img->resize($param_width, $param_height, $fit);
        $result->saveToFile($dst_path);
    } else {
        @copy($src_path, $dst_path);
    }
    if (!$keep_original) {
        @unlink($src_path);
    }

    return true;
}

function nw_latestnews_mk_chkbox($options, $number)
{
    $chk = '';
    if ($options[$number] == 1) {
        $chk = ' checked';
    }
    $chkbox = "<input type='radio' name='options[$number]' value='1'" . $chk . '>&nbsp;' . _YES . '&nbsp;&nbsp;';
    $chk    = '';
    if ($options[$number] == 0) {
        $chk = ' checked';
    }
    $chkbox .= "<input type='radio' name='options[$number]' value='0'" . $chk . '>&nbsp;' . _NO . '</td></tr>';

    return $chkbox;
}

function nw_latestnews_mk_select($options, $number)
{
    $slc = '';
    if ($options[$number] == 1) {
        $slc = ' checked';
    }
    $select = "<input type='radio' name='options[$number]' value='1'" . $slc . '>&nbsp;' . _LEFT . '&nbsp;&nbsp;';
    $slc    = '';
    if ($options[$number] == 0) {
        $slc = ' checked';
    }
    $select .= "<input type='radio' name='options[$number]' value='0'" . $slc . '>&nbsp;' . _RIGHT . '</td></tr>';

    return $select;
}

function nw_remove_numbers($string)
{
    $vowels = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '0', ' ');
    $string = str_replace($vowels, '', $string);

    return $string;
}

function nw_prepareFolder($folder)
{
    if (!is_dir($folder)) {
        mkdir($folder, 0777);
        file_put_contents($folder . '/index.html', '<script>history.go(-1);</script>');
    }
}

//DNPROSSI SEO
/**
 * if XOOPS ML is present, let's sanitize the title with the current language
 */
function nw_remove_accents($chain)
{
    $xnews = XnewsXnews::getInstance();
    $myts  = MyTextSanitizer::getInstance();
    //
    if (method_exists($myts, 'formatForML')) {
        $chain = $myts->formatForML($chain);
    }

    /**
     * if xLanguage is present, let's prepare the title with the current language
     */
    $moduleHandler = xoops_getHandler('module');
    $xlanguage     = $moduleHandler->getByDirname('xlanguage');
    if (is_object($xlanguage) && $xlanguage->getVar('isactive') === true) {
        require_once XOOPS_ROOT_PATH . '/modules/xlanguage/include/functions.php';
        $chain = xlanguage_ml($chain);
    }

    $chain = rawurlencode($chain);
    //$chain = utf8_decode($chain);

    // Transform punctuation
    //                 Tab     Space      !        "        #        %        &        '        (        )        ,        /        :        ;        <        =        >        ?        @        [        \        ]        ^        {        |        }        ~       .
    $pattern = array(
        '/%09/',
        '/%20/',
        '/%21/',
        '/%22/',
        '/%23/',
        '/%25/',
        '/%26/',
        '/%27/',
        '/%28/',
        '/%29/',
        '/%2C/',
        '/%2F/',
        '/%3A/',
        '/%3B/',
        '/%3C/',
        '/%3D/',
        '/%3E/',
        '/%3F/',
        '/%40/',
        '/%5B/',
        '/%5C/',
        '/%5D/',
        '/%5E/',
        '/%7B/',
        '/%7C/',
        '/%7D/',
        '/%7E/',
        '/%39/',
        "/\./"
    );
    $rep_pat = array('-', '-', '', '', '', '-100', '', '-', '', '', '', '-', '', '', '', '-', '', '', '-at-', '', '-', '', '-', '', '-', '', '-', '-', '');
    $chain   = preg_replace($pattern, $rep_pat, $chain);

    return $chain;
}

function nw_seo_UrlGenerator($op, $id, $short_url = '')
{
    $xnews = XnewsXnews::getInstance();
    //
    if ($xnews->getConfig('seo_enable') != 0) {
        if (!empty($short_url)) {
            $short_url = $short_url;
        }

        switch ($op) {
            case _MA_NW_SEO_PDF:
                $short_url .= $xnews->getConfig('seo_endofurl_pdf');
                break;
            case _MA_NW_SEO_PRINT:
                $short_url .= $xnews->getConfig('seo_endofurl');
                break;
            case _MA_NW_SEO_ARTICLES:
                $short_url .= $xnews->getConfig('seo_endofurl');
                break;
            case _MA_NW_SEO_TOPICS:
                $short_url .= $xnews->getConfig('seo_endofurl');
                break;
        }
        if ($xnews->getConfig('seo_enable') == 1) {
            // generate SEO url using htaccess
            $seo_path = '';
            if ($xnews->getConfig('seo_path') != '') {
                // generate SEO url using seo path eg news, info, blog
                $seo_path = '/' . strtolower($xnews->getConfig('seo_path'));
                if ($xnews->getConfig('seo_level') == 0) {
                    // generate SEO url using root level htaccess
                    $seo_path .= '.';

                    return XOOPS_URL . '/' . XNEWS_MODULE_DIRNAME . $seo_path . "${op}.${id}/${short_url}";
                } else {
                    // generate SEO url using module level htaccess
                    $seo_path .= '.';

                    return XNEWS_MODULE_URL . $seo_path . "${op}.${id}/${short_url}";
                }
            } else {
                // generate SEO url with no seo path
                $seo_path = '/' . strtolower($xnews->getConfig('seo_path'));
                if ($xnews->getConfig('seo_level') == 0) {
                    // generate SEO url using root level htaccess
                    return XOOPS_URL . '/' . XNEWS_MODULE_DIRNAME . $seo_path . "${op}.${id}/${short_url}";
                } else {
                    // generate SEO url using module level htaccess
                    return XNEWS_MODULE_URL . $seo_path . "${op}.${id}/${short_url}";
                }
            }
        } elseif ($xnews->getConfig('seo_enable') == 2) {
            // generate SEO url using path-info
            $seo_path = '';
            if ($xnews->getConfig('seo_path') != '') {
                $seo_path = strtolower($xnews->getConfig('seo_path')) . '.';
            }

            return XNEWS_MODULE_URL . '/index.php/' . $seo_path . "${op}.${id}/${short_url}";
        } else {
            die('Unknown SEO method.');
        }
    } else {
        // generate classic url
        switch ($op) {
            case _MA_NW_SEO_TOPICS:
                return XNEWS_MODULE_URL . "/index.php?topic_id=${id}";
            case _MA_NW_SEO_ARTICLES:
                return XNEWS_MODULE_URL . "/article.php?storyid=${id}";
            case _MA_NW_SEO_PRINT:
                return XNEWS_MODULE_URL . "/print.php?storyid=${id}";
            case _MA_NW_SEO_PDF:
                return XNEWS_MODULE_URL . "/makepdf.php?storyid=${id}";
            default:
                die('Unknown SEO operation.');
        }
    }
}

function nw_callJavascriptFile($javascriptFile, $inLanguageFolder = false, $oldWay = false)
{
    global $xoopsConfig, $xoTheme;
    $xnews = XnewsXnews::getInstance();
    //
    $fileToCall = $javascriptFile;
    if ($inLanguageFolder) {
        if (file_exists(XNEWS_MODULE_PATH . 'language/' . $xoopsConfig['language'] . '/' . $javascriptFile)) {
            $fileToCall = XNEWS_MODULE_URL . 'language/' . $xoopsConfig['language'] . '/' . $javascriptFile;
        } else {    // Fallback
            $fileToCall = XNEWS_MODULE_URL . 'language/english/' . $javascriptFile;
        }
    } else {
        $fileToCall = XNEWS_MODULE_URL . '/assets/js/' . $javascriptFile;
    }
    if (!$oldWay && isset($xoTheme)) {
        $xoTheme->addScript($fileToCall);
    } else {
        echo '<script type="text/javascript" src="' . $fileToCall . "\"></script>\n";
    }
}

function nw_detect_utf8_lang_encoding($string)
{
    $pattern_array = array(
        'arabic'  => '/\%D8\%([A-F0-9]{2})\%D9\%([A-F0-9]{2})/i',
        'chinese' => '/\%E5\%([A-F0-9]{2})\%([A-F0-9]{2})\%E6\%([A-F0-9]{2})\%([A-F0-9]{2})/i'
    );

    //$pattern = '/[\%D8\%([A-Z0-9]{2})-\%DB\%([A-Z0-9]{2})]/i'; //arabic
    //$pattern = '/\%D8\%([A-F0-9]{2})\%D9\%([A-F0-9]{2})/i';
    //$pattern = '/\%E5\%([A-Z0-9]{2})\%([A-Z0-9]{2})\%E6\%([A-Z0-9]{2})\%([A-Z0-9]{2})/i'; //chinese

    foreach ($pattern_array as $key => $pattern) {
        preg_match($pattern, $string, $match);
        echo $key . ' => ' . $pattern;
        if (!empty($match)) {
            switch ($key) {
                case 'arabic':
                    return 'fa';
                case 'chinese':
                    return 'cn';
            }
        }
    }
}

//DNPROSSI Added
//@param string $text String to truncate.
//@param integer $length Length of returned string, including ellipsis.
//@param string $ending Ending to be appended to the trimmed string.
//@param boolean $exact If false, $text will not be cut mid-word
//@param boolean $considerHtml If true, HTML tags would be handled correctly
//@return string Trimmed string.
function nw_truncate($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true)
{
    $xnews = XnewsXnews::getInstance();
    //
    if ($considerHtml) {
        // if the plain text is shorter than the maximum length, return the whole text
        if (strlen(preg_replace('/<.*?' . '>/', '', $text)) <= $length) {
            return $text;
        }
        // splits all html-tags to scanable lines
        preg_match_all('/(<.+?' . '>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
        $total_length = strlen($ending);
        $open_tags    = array();
        $truncate     = '';
        foreach ($lines as $line_matchings) {
            // if there is any html-tag in this line, handle it and add it (uncounted) to the output
            if (!empty($line_matchings[1])) {
                // if it's an "empty element" with or without xhtml-conform closing slash
                if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                    // do nothing
                    // if tag is a closing tag
                } elseif (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                    // delete tag from $open_tags list
                    $pos = array_search($tag_matchings[1], $open_tags);
                    if ($pos !== false) {
                        unset($open_tags[$pos]);
                    }
                    // if tag is an opening tag
                } elseif (preg_match('/^<\s*([^\s>!]+).*?' . '>$/s', $line_matchings[1], $tag_matchings)) {
                    // add tag to the beginning of $open_tags list
                    array_unshift($open_tags, strtolower($tag_matchings[1]));
                }
                // add html-tag to $truncate'd text
                $truncate .= $line_matchings[1];
            }
            // calculate the length of the plain text part of the line; handle entities as one character
            $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
            if ($total_length + $content_length > $length) {
                // the number of characters which are left
                $left            = $length - $total_length;
                $entities_length = 0;
                // search for html entities
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                    // calculate the real length of all entities in the legal range
                    foreach ($entities[0] as $entity) {
                        if ($entity[1] + 1 - $entities_length <= $left) {
                            $left--;
                            $entities_length += strlen($entity[0]);
                        } else {
                            // no more characters left
                            break;
                        }
                    }
                }
                $truncate .= mb_substr($line_matchings[2], 0, $left + $entities_length, 'UTF-8');
                // maximum lenght is reached, so get off the loop
                break;
            } else {
                $truncate     .= $line_matchings[2];
                $total_length += $content_length;
            }
            // if the maximum length is reached, get off the loop
            if ($total_length >= $length) {
                break;
            }
        }
    } else {
        if (strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = mb_substr($text, 0, $length - strlen($ending), 'UTF-8');
        }
    }
    // if the words shouldn't be cut in the middle...
    if (!$exact) {
        // ...search the last occurance of a space...
        $spacepos = strrpos($truncate, ' ');
        if (isset($spacepos)) {
            // ...and cut the text in this position
            $truncate = mb_substr($truncate, 0, $spacepos, 'UTF-8');
        }
    }
    // add the defined ending to the text
    $truncate .= $ending;
    if ($considerHtml) {
        // close all unclosed html-tags
        foreach ($open_tags as $tag) {
            $truncate .= '</' . $tag . '>';
        }
    }

    return $truncate;
}

//DNPROSSI - Added 1.71
//Use Javascript to detect adobe pdf plugin used to view
//attached pdf in articles view
function nw_detect_adobe()
{
    $has_adobe = '';
    nw_callJavascriptFile('pdfobject.js');
    echo '
        <script type="text/javascript">
            var plugin = pipwerks.pdfUTILS.detect.pluginFound();
            if (plugin == "Adobe") {
                var has_adobe = 1;
            } else {
                var has_adobe = 0;
            }
            var today = new Date();
            var the_date = new Date("December 31, 2099");
            var the_cookie_date = the_date.toGMTString();
            var the_cookie = "xnews=" + has_adobe;
            var the_cookie = the_cookie + ";expires=" + the_cookie_date;
            document.cookie=the_cookie
        </script>
    ';

    if (isset($_COOKIE['xnews'])) {
        $has_adobe = $_COOKIE['xnews'];
        //Delete cookie
        setcookie('xnews', '', time() - 3600);
    }

    return $has_adobe;
}
