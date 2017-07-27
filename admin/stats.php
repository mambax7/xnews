<?php
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
 * Statistics about stories, topics and authors
 *
 * You can reach the statistics from the admin part of the news module by clicking on the "Statistics" tabs
 * The number of visible elements in each table is equal to the module's option called "storycountadmin"
 * There are 3 kind of different statistics :
 * - Topics statistics
 *   For each topic you can see its number of articles, the number of time each topics was viewed, the number
 *   of attached files, the number of expired articles and the number of unique authors.
 * - Articles statistics
 *   This part is decomposed in 3 tables :
 *   a) Most readed articles
 *      This table resumes, for all the news in your database, the most readed articles.
 *      The table contains, for each news, its topic, its title, the author and the number of views.
 *   b) Less readed articles
 *      That's the opposite action of the previous table and its content is the same
 *   c) Best rated articles
 *      You will find here the best rated articles, the content is the same that the previous tables, the last column is just changing and contains the article's rating
 * - Authors statistics
 *   This part is also decomposed in 3 tables
 *   a) Most readed authors
 *        To create this table, the program compute the total number of reads per author and displays the most readed author and the number of views
 *   b) Best rated authors
 *      To created this table's content, the program compute the rating's average of each author and create a table
 *   c) Biggest contributors
 *      The goal of this table is to know who is creating the biggest number of articles.
 */

// admin navigation
xoops_cp_header();
$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation($currentFile);

$myts = MyTextSanitizer::getInstance();
xoops_loadLanguage('main', XNEWS_MODULE_DIRNAME);
$news   = new nw_NewsStory();
$stats  = array();
$stats  = $news->GetStats($xnews->getConfig('storycountadmin'));
$totals = array(0, 0, 0, 0, 0);

// First part of the stats, everything about topics
$storiesPerTopic = $stats['storiespertopic'];
$readsPerTopic   = $stats['readspertopic'];
$filesPerTopic   = $stats['filespertopic'];
$expiredPerTopic = $stats['expiredpertopic'];
$authorsPerTopic = $stats['authorspertopic'];
$class           = '';

echo "<div style='text-align: center;'><b>" . _AM_NW_STATS0 . "</b><br>\n";
echo "<table border='0' width='100%'><tr class='bg3'><td align='center'>" . _AM_NW_TOPIC . "</td><td align='center'>" . _MA_NW_ARTICLES . "</td><td>" . _MA_NW_VIEWS . "</td><td>" . _AM_NW_UPLOAD_ATTACHFILE . "</td><td>" . _AM_NW_EXPARTS . "</td><td>" . _AM_NW_STATS1 . "</td></tr>";
foreach ($storiesPerTopic as $topicid => $data) {
    $url   = XNEWS_MODULE_URL . '/index.php?topic_id=' . $topicid;
    $views = 0;
    if (array_key_exists($topicid, $readsPerTopic)) {
        $views = $readsPerTopic[$topicid];
    }
    $attachedFiles = 0;
    if (array_key_exists($topicid, $filesPerTopic)) {
        $attachedFiles = $filesPerTopic[$topicid];
    }
    $expired = 0;
    if (array_key_exists($topicid, $expiredPerTopic)) {
        $expired = $expiredPerTopic[$topicid];
    }
    $authors = 0;
    if (array_key_exists($topicid, $authorsPerTopic)) {
        $authors = $authorsPerTopic[$topicid];
    }
    $articles = $data['cpt'];

    $totals[0] += $articles;
    $totals[1] += $views;
    $totals[2] += $attachedFiles;
    $totals[3] += $expired;
    $class     = ($class == 'even') ? 'odd' : 'even';
    printf("<tr class='{$class}'><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='right'>%u</td><td align='right'>%u</td><td align='right'>%u</td><td align='right'>%u</td><td align='right'>%u</td></tr>\n", $url, $myts->displayTarea($data['topic_title']), $articles, $views,
           $attachedFiles, $expired, $authors);
}
$class = ($class == 'even') ? 'odd' : 'even';
printf("<tr class='{$class}'><td align='center'><b>%s</b></td><td align='right'><b>%u</b></td><td align='right'><b>%u</b></td><td align='right'><b>%u</b></td><td align='right'><b>%u</b></td><td>&nbsp;</td>\n", _AM_NW_STATS2, $totals[0], $totals[1], $totals[2], $totals[3]);
echo '</table></div><br><br><br>';

// Second part of the stats, everything about stories
// a) Most readed articles
$mostReadNews = $stats['mostreadnews'];
echo "<div style='text-align: center;'><b>" . _AM_NW_STATS3 . '</b><br><br>' . _AM_NW_STATS4 . "<br>\n";
echo "<table border='0' width='100%'><tr class='bg3'><td align='center'>" . _AM_NW_TOPIC . "</td><td align='center'>" . _AM_NW_TITLE . "</td><td>" . _AM_NW_POSTER . "</td><td>" . _MA_NW_VIEWS . "</td></tr>\n";
foreach ($mostReadNews as $storyid => $data) {
    $url1  = XNEWS_MODULE_URL . '/index.php?topic_id=' . $data['topicid'];
    $url2  = XNEWS_MODULE_URL . '/article.php?storyid=' . $storyid;
    $url3  = XOOPS_URL . '/userinfo.php?uid=' . $data['uid'];
    $class = ($class == 'even') ? 'odd' : 'even';
    printf("<tr class='{$class}'><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='left'><a href='%s' target='_blank'>%s</a></td><td><a href='%s' target='_blank'>%s</a></td><td align='right'>%u</td></tr>\n", $url1, $myts->displayTarea($data['topic_title']), $url2,
           $myts->displayTarea($data['title']), $url3, $myts->htmlSpecialChars($news->uname($data['uid'])), $data['counter']);
}
echo '</table>';

// b) Less readed articles
$lessReadNews = $stats['lessreadnews'];
echo '<br><br>' . _AM_NW_STATS5;
echo "<table border='0' width='100%'><tr class='bg3'><td align='center'>" . _AM_NW_TOPIC . "</td><td align='center'>" . _AM_NW_TITLE . "</td><td>" . _AM_NW_POSTER . "</td><td>" . _MA_NW_VIEWS . "</td></tr>\n";
foreach ($lessReadNews as $storyid => $data) {
    $url1  = XNEWS_MODULE_URL . '/index.php?topic_id=' . $data['topicid'];
    $url2  = XNEWS_MODULE_URL . '/article.php?storyid=' . $storyid;
    $url3  = XOOPS_URL . '/userinfo.php?uid=' . $data['uid'];
    $class = ($class == 'even') ? 'odd' : 'even';
    printf("<tr class='{$class}'><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='left'><a href='%s' target='_blank'>%s</a></td><td><a href='%s' target='_blank'>%s</a></td><td align='right'>%u</td></tr>\n", $url1, $myts->displayTarea($data['topic_title']), $url2,
           $myts->displayTarea($data['title']), $url3, $myts->htmlSpecialChars($news->uname($data['uid'])), $data['counter']);
}
echo '</table>';

// c) Best rated articles (this is an average)
$bestRatedNews = $stats['besratednw'];
echo '<br><br>' . _AM_NW_STATS6;
echo "<table border='0' width='100%'><tr class='bg3'><td align='center'>" . _AM_NW_TOPIC . "</td><td align='center'>" . _AM_NW_TITLE . "</td><td>" . _AM_NW_POSTER . "</td><td>" . _MA_NW_RATING . "</td></tr>\n";
foreach ($bestRatedNews as $storyid => $data) {
    $url1  = XNEWS_MODULE_URL . '/index.php?topic_id=' . $data['topicid'];
    $url2  = XNEWS_MODULE_URL . '/article.php?storyid=' . $storyid;
    $url3  = XOOPS_URL . '/userinfo.php?uid=' . $data['uid'];
    $class = ($class == 'even') ? 'odd' : 'even';
    printf("<tr class='{$class}'><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='left'><a href='%s' target='_blank'>%s</a></td><td><a href='%s' target='_blank'>%s</a></td><td align='right'>%s</td></tr>\n", $url1, $myts->displayTarea($data['topic_title']), $url2,
           $myts->displayTarea($data['title']), $url3, $myts->htmlSpecialChars($news->uname($data['uid'])), number_format($data['rating'], 2));
}
echo '</table></div><br><br><br>';

// Last part of the stats, everything about authors
// a) Most readed authors
$mostReadedAuthors = $stats['mostreadedauthors'];
echo "<div style='text-align: center;'><b>" . _AM_NW_STATS10 . '</b><br><br>' . _AM_NW_STATS7 . "<br>\n";
echo "<table border='0' width='100%'><tr class='bg3'><td>" . _AM_NW_POSTER . '</td><td>' . _MA_NW_VIEWS . "</td></tr>\n";
foreach ($mostReadedAuthors as $uid => $reads) {
    $url   = XOOPS_URL . '/userinfo.php?uid=' . $uid;
    $class = ($class == 'even') ? 'odd' : 'even';
    printf("<tr class='{$class}'><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='right'>%u</td></tr>\n", $url, $myts->htmlSpecialChars($news->uname($uid)), $reads);
}
echo '</table>';

// b) Best rated authors
$bestRatedAuthors = $stats['bestratedauthors'];
echo '<br><br>' . _AM_NW_STATS8;
echo "<table border='0' width='100%'><tr class='bg3'><td>" . _AM_NW_POSTER . "</td><td>" . _MA_NW_RATING . "</td></tr>\n";
foreach ($bestRatedAuthors as $uid => $rating) {
    $url   = XOOPS_URL . '/userinfo.php?uid=' . $uid;
    $class = ($class == 'even') ? 'odd' : 'even';
    printf("<tr class='{$class}'><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='right'>%u</td></tr>\n", $url, $myts->htmlSpecialChars($news->uname($uid)), $rating);
}
echo '</table>';

// c) Biggest contributors
$biggestContributors = $stats['biggestcontributors'];
echo '<br><br>' . _AM_NW_STATS9;
echo "<table border='0' width='100%'><tr class='bg3'><td>" . _AM_NW_POSTER . "</td><td>" . _AM_NW_STATS11 . "</td></tr>\n";
foreach ($biggestContributors as $uid => $count) {
    $url   = XOOPS_URL . '/userinfo.php?uid=' . $uid;
    $class = ($class == 'even') ? 'odd' : 'even';
    printf("<tr class='{$class}'><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='right'>%u</td></tr>\n", $url, $myts->htmlSpecialChars($news->uname($uid)), $count);
}
echo '</table></div><br>';

xoops_cp_footer();
