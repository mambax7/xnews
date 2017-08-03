<?php

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

$myts        = MyTextSanitizer::getInstance();
$topicscount = 0;

$storiesTableName = $GLOBALS['xoopsDB']->prefix('nw_stories');
if (!nw_FieldExists('picture', $storiesTableName)) {
    nw_AddField('`picture` VARCHAR( 50 ) NOT NULL', $storiesTableName);
}

/**
 * Newsletter's configuration
 *
 * You can create a newsletter's content from the admin part of the News module when you click on the tab named "Newsletter"
 * First, let be clear, this module'functionality will not send the newsletter but it will prepare its content for you.
 * To send the newsletter, you can use many specialized modules like evennews.
 * You first select a range of dates and if you want, a selection of topics to use for the search.
 * Once it's done, the script will use the file named /xoops/modules/language/yourlanguage/newsletter.php to create
 * the newsletter's content. When it's finished, the script generates a file in the upload folder.
 */
function Newsletter()
{
    $xnews               = XnewsXnews::getInstance();
    $nw_NewsStoryHandler = new nw_NewsStory();
    //
    xoops_cp_header();
    $adminObject = \Xmf\Module\Admin::getInstance();
    $adminObject->displayNavigation('newsletter.php');
    //
    xoops_load('XoopsFormLoader');
    $sform = new XoopsThemeForm(_AM_NW_NEWSLETTER, 'newsletterform', XNEWS_MODULE_URL . '/admin/index.php', 'post', true);
    //
    $dates_tray = new XoopsFormElementTray(_AM_NW_NEWSLETTER_BETWEEN);
    $date1      = new XoopsFormTextDateSelect('', 'date1', 15, time());
    $dates_tray->addElement($date1);
    $date2 = new XoopsFormTextDateSelect(_AM_NW_EXPORT_AND, 'date2', 15, time());
    $dates_tray->addElement($date2);
    $sform->addElement($dates_tray);
    //
    $topiclist  = new XoopsFormSelect(_AM_NW_PRUNE_TOPICS, 'export_topics', '', 5, true);
    $topics_arr = array();
    $xt         = new nw_NewsTopic();
    $allTopics  = $xt->getAllTopics(false); // The webmaster can see everything
    $topic_tree = new XoopsObjectTree($allTopics, 'topic_id', 'topic_pid');
    $topics_arr = $topic_tree->getAllChild(0);
    if (count($topics_arr)) {
        foreach ($topics_arr as $onetopic) {
            $topiclist->addOption($onetopic->topic_id(), $onetopic->topic_title());
        }
    }
    $topiclist->setDescription(_AM_NW_EXPORT_PRUNE_DSC);
    $sform->addElement($topiclist, false);
    //
    $sform->addElement(new XoopsFormHidden('op', 'launchnewsletter'), false);
    $sform->addElement(new XoopsFormRadioYN(_AM_NW_REMOVE_BR, 'removebr', 1), false);
    $sform->addElement(new XoopsFormRadioYN(_AM_NW_NEWSLETTER_HTML_TAGS, 'removehtml', 0), false);
    $sform->addElement(new XoopsFormTextArea(_AM_NW_NEWSLETTER_HEADER, 'header', '', 4, 70), false);
    $sform->addElement(new XoopsFormTextArea(_AM_NW_NEWSLETTER_FOOTER, 'footer', '', 4, 70), false);
    $button_tray = new XoopsFormElementTray('', '');
    $submit_btn  = new XoopsFormButton('', 'post', _SUBMIT, 'submit');
    $button_tray->addElement($submit_btn);
    $sform->addElement($button_tray);
    $sform->display();
}

/**
 * Launch the creation of the newsletter's content
 */
function LaunchNewsletter()
{
    $xnews               = XnewsXnews::getInstance();
    $nw_NewsStoryHandler = new nw_NewsStory();
    //
    xoops_cp_header();
    $adminObject = \Xmf\Module\Admin::getInstance();
    $adminObject->displayNavigation('index.php?op=configurenewsletter');

    xoops_loadLanguage('newsletter', XNEWS_MODULE_DIRNAME);

    $newslettertemplate = '';
    echo '<br>';
    $story           = new nw_NewsStory();
    $exportedStories = array();
    $topiclist       = '';
    $removeBr        = $removehtml = false;
    $removeBr        = isset($_POST['removebr']) ? (int)$_POST['removebr'] : 0;
    $removehtml      = isset($_POST['removehtml']) ? (int)$_POST['removehtml'] : 0;
    $header          = isset($_POST['header']) ? $_POST['header'] : '';
    $footer          = isset($_POST['footer']) ? $_POST['footer'] : '';
    //
    $dateTimeObj = DateTime::createFromFormat(_SHORTDATESTRING, $_POST['date1']);
    $dateTimeObj->setTime(0, 0, 0);
    $timestamp1 = $dateTimeObj->getTimestamp();
    unset($dateTimeObj);
    //
    $dateTimeObj = DateTime::createFromFormat(_SHORTDATESTRING, $_POST['date2']);
    $dateTimeObj->setTime(0, 0, 0);
    $timestamp2 = $dateTimeObj->getTimestamp();
    unset($dateTimeObj);
    //
    if (isset($_POST['export_topics'])) {
        $topiclist = implode(',', $_POST['export_topics']);
    }
    $tbltopics       = array();
    $exportedStories = $story->NewsExport($timestamp1, $timestamp2, $topiclist, 0, $tbltopics);
    $newsfile        = XOOPS_ROOT_PATH . '/uploads/newsletter.txt';
    if (count($exportedStories)) {
        $fp = fopen($newsfile, 'w');
        if (!$fp) {
            redirect_header('index.php', 3, sprintf(_AM_NW_EXPORT_ERROR, $newsfile));
        }
        if (xoops_trim($header) != '') {
            fwrite($fp, $header);
        }
        foreach ($exportedStories as $exportedStory) {
            $content         = $newslettertemplate;
            $search_pattern  = array(
                '%title%',
                '%uname%',
                '%created%',
                '%published%',
                '%expired%',
                '%hometext%',
                '%bodytext%',
                '%description%',
                '%keywords%',
                '%reads%',
                '%topicid%',
                '%topic_title%',
                '%comments%',
                '%rating%',
                '%votes%',
                '%publisher%',
                '%publisher_id%',
                '%link%'
            );
            $replace_pattern = array(
                $exportedStory->title(),
                $exportedStory->uname(),
                formatTimestamp($exportedStory->created(), $xnews->getConfig('dateformat')),
                formatTimestamp($exportedStory->published(), $xnews->getConfig('dateformat')),
                formatTimestamp($exportedStory->expired(), $xnews->getConfig('dateformat')),
                $exportedStory->hometext(),
                $exportedStory->bodytext(),
                $exportedStory->description(),
                $exportedStory->keywords(),
                $exportedStory->counter(),
                $exportedStory->topicid(),
                $exportedStory->topic_title(),
                $exportedStory->comments(),
                $exportedStory->rating(),
                $exportedStory->votes(),
                $exportedStory->uname(),
                $exportedStory->uid(),
                XNEWS_MODULE_URL . '/article.php?storyid=' . $exportedStory->storyid()
            );
            $content         = str_replace($search_pattern, $replace_pattern, $content);
            if ($removeBr) {
                $content = str_replace('<br>', "\r\n", $content);
            }
            if ($removehtml) {
                $content = strip_tags($content);
            }
            fwrite($fp, $content);
        }
        if (xoops_trim($footer) != '') {
            fwrite($fp, $footer);
        }
        fclose($fp);
        $newsfile = XOOPS_URL . '/uploads/newsletter.txt';
        printf(_AM_NW_NEWSLETTER_READY, $newsfile, XNEWS_MODULE_URL . '/admin/index.php?op=deletefile&amp;type=newsletter');
    } else {
        printf(_AM_NW_NOTHING);
    }
}

// **********************************************************************************************************************************************
// **** Main
// **********************************************************************************************************************************************

$op = Request::getString('op', 'configurenewsletter');

switch ($op) {
    case 'configurenewsletter':
        Newsletter();
        xoops_cp_footer();
        break;

    case 'launchnewsletter':
        LaunchNewsletter();
        xoops_cp_footer();
        break;
}
