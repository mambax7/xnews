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

// admin navigation
xoops_cp_header();
$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation($currentFile);
//

/**
 * @param $text
 * @return string
 */
function nw_utf8_encode($text)
{
    return xoops_utf8_encode($text);
}

$op = Request::getString('op', 'default');

switch ($op) {
    default:
    case 'export':
        require_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        $sform      = new \XoopsThemeForm(_AM_XNEWS_EXPORT_NEWS, 'exportform', $currentFile, 'post', true);
        $dates_tray = new \XoopsFormElementTray(_AM_XNEWS_EXPORT_BETWEEN);
        $date1      = new \XoopsFormTextDateSelect('', 'date1', 15, time());
        $date2      = new \XoopsFormTextDateSelect(_AM_XNEWS_EXPORT_AND, 'date2', 15, time());
        $dates_tray->addElement($date1);
        $dates_tray->addElement($date2);
        $sform->addElement($dates_tray);

        $topiclist  = new \XoopsFormSelect(_AM_XNEWS_PRUNE_TOPICS, 'export_topics', '', 5, true);
        $topics_arr = [];
        $xt         = new Xnews\NewsTopic();
        $allTopics  = $xt->getAllTopics(false);                // The webmaster can see everything
        $topic_tree = new \XoopsObjectTree($allTopics, 'topic_id', 'topic_pid');
        $topics_arr = $topic_tree->getAllChild(0);
        if (count($topics_arr)) {
            foreach ($topics_arr as $onetopic) {
                $topiclist->addOption($onetopic->topic_id(), $onetopic->topic_title());
            }
        }
        $topiclist->setDescription(_AM_XNEWS_EXPORT_PRUNE_DSC);
        $sform->addElement($topiclist, false);
        $sform->addElement(new \XoopsFormRadioYN(_AM_XNEWS_EXPORT_INCTOPICS, 'includetopics', 0), false);
        $sform->addElement(new \XoopsFormHidden('op', 'launchexport'), false);
        $button_tray = new \XoopsFormElementTray('', '');
        $submit_btn  = new \XoopsFormButton('', 'post', _SUBMIT, 'submit');
        $button_tray->addElement($submit_btn);
        $sform->addElement($button_tray);
        $sform->display();
        xoops_cp_footer();
        break;

    case 'launchexport':
        $story           = new Xnews\NewsStory();
        $topic           = new Xnews\NewsTopic();
        $exportedstories = [];
        $date1           = $_POST['date1'];
        $date2           = $_POST['date2'];
        $timestamp1      = mktime(0, 0, 0, (int)substr($date1, 5, 2), (int)substr($date1, 8, 2), (int)substr($date1, 0, 4));
        $timestamp2      = mktime(23, 59, 59, (int)substr($date2, 5, 2), (int)substr($date2, 8, 2), (int)substr($date2, 0, 4));
        $topiclist       = '';
        if (isset($_POST['export_topics'])) {
            $topiclist = implode(',', $_POST['export_topics']);
        }
        $topicsexport    = \Xmf\Request::getInt('includetopics', 0, 'POST');
        $tbltopics       = [];
        $exportedstories = $story->NewsExport($timestamp1, $timestamp2, $topiclist, $topicsexport, $tbltopics);
        if (count($exportedstories)) {
            $xmlfile = XOOPS_ROOT_PATH . '/uploads/nw_stories.xml';
            $fp      = fopen($xmlfile, 'w');
            if (!$fp) {
                redirect_header('index.php', 4, sprintf(_AM_XNEWS_EXPORT_ERROR, $xmlfile));
            }
            fwrite($fp, nw_utf8_encode('<?xml version="1.0" encoding="UTF-8" ?' . ">\n"));
            fwrite($fp, nw_utf8_encode("<nw_stories>\n"));
            if ($topicsexport) {
                foreach ($tbltopics as $onetopic) {
                    $topic->__construct($onetopic);
                    $content = "<nw_topic>\n";
                    $content .= sprintf("\t<topic_id>%u</topic_id>\n", $topic->topic_id());
                    $content .= sprintf("\t<topic_pid>%u</topic_pid>\n", $topic->topic_pid());
                    $content .= sprintf("\t<topic_imgurl>%s</topic_imgurl>\n", $topic->topic_imgurl());
                    $content .= sprintf("\t<topic_title>%s</topic_title>\n", $topic->topic_title('F'));
                    $content .= sprintf("\t<menu>%d</menu>\n", $topic->menu());
                    $content .= sprintf("\t<topic_frontpage>%d</topic_frontpage>\n", $topic->topic_frontpage());
                    $content .= sprintf("\t<topic_rssurl>%s</topic_rssurl>\n", $topic->topic_rssurl('E'));
                    $content .= sprintf("\t<topic_description>%s</topic_description>\n", $topic->topic_description());
                    $content .= sprintf("</nw_topic>\n");
                    $content = nw_utf8_encode($content);
                    fwrite($fp, $content);
                }
            }
            foreach ($exportedstories as $onestory) {
                $content = "<xoops_story>\n";
                $content .= sprintf("\t<storyid>%u</storyid>\n", $onestory->storyid());
                $content .= sprintf("\t<uid>%u</uid>\n", $onestory->uid());
                $content .= sprintf("\t<uname>%s</uname>\n", $onestory->uname());
                $content .= sprintf("\t<title>%s</title>\n", $onestory->title());
                $content .= sprintf("\t<created>%u</created>\n", $onestory->created());
                $content .= sprintf("\t<published>%u</published>\n", $onestory->published());
                $content .= sprintf("\t<expired>%u</expired>\n", $onestory->expired());
                $content .= sprintf("\t<hostname>%s</hostname>\n", $onestory->hostname());
                $content .= sprintf("\t<nohtml>%d</nohtml>\n", $onestory->nohtml());
                $content .= sprintf("\t<nosmiley>%d</nosmiley>\n", $onestory->nosmiley());
                $content .= sprintf("\t<dobr>%d</dobr>\n", $onestory->dobr());
                $content .= sprintf("\t<hometext>%s</hometext>\n", $onestory->hometext());
                $content .= sprintf("\t<bodytext>%s</bodytext>\n", $onestory->bodytext());
                $content .= sprintf("\t<description>%s</description>\n", $onestory->description());
                $content .= sprintf("\t<keywords>%s</keywords>\n", $onestory->keywords());
                $content .= sprintf("\t<counter>%u</counter>\n", $onestory->counter());
                $content .= sprintf("\t<topicid>%u</topicid>\n", $onestory->topicid());
                $content .= sprintf("\t<ihome>%d</ihome>\n", $onestory->ihome());
                $content .= sprintf("\t<notifypub>%d</notifypub>\n", $onestory->notifypub());
                $content .= sprintf("\t<story_type>%s</story_type>\n", $onestory->type());
                $content .= sprintf("\t<topicdisplay>%d</topicdisplay>\n", $onestory->topicdisplay());
                $content .= sprintf("\t<topicalign>%s</topicalign>\n", $onestory->topicalign());
                $content .= sprintf("\t<comments>%u</comments>\n", $onestory->comments());
                $content .= sprintf("\t<rating>%f</rating>\n", $onestory->rating());
                $content .= sprintf("\t<votes>%u</votes>\n", $onestory->votes());
                $content .= sprintf("\t<imagesrows>%u</imagerows>\n", $onestory->imagerows());
                $content .= sprintf("\t<pdfrows>%u</pdfrows>\n", $onestory->pdfrows());
                $content .= sprintf("\t<tags>%s</tags>\n", $onestory->tags());
                $content .= sprintf("</xoops_story>\n");
                $content = nw_utf8_encode($content);
                fwrite($fp, $content);
            }
            fwrite($fp, nw_utf8_encode("</nw_stories>\n"));
            fclose($fp);
            $xmlfile = XOOPS_URL . '/uploads/nw_stories.xml';
            printf(_AM_XNEWS_EXPORT_READY, $xmlfile, NW_MODULE_URL . '/admin/index.php?op=deletefile&amp;type=xml');
        } else {
            printf(_AM_XNEWS_EXPORT_NOTHING);
        }
        xoops_cp_footer();
        break;
}
