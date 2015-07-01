<?php
// $Id: index.php 8207 2011-11-07 04:18:27Z beckmi $
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System  				                    //
// Copyright (c) 2000 XOOPS.org                         					//
// <http://www.xoops.org/>                             						//
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //
// 																			//
// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// 																			//
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //
// 																			//
// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
include_once "header.php";
include_once '../../../include/cp_header.php';
include_once XOOPS_ROOT_PATH.'/class/xoopstopic.php';
include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
include_once NW_MODULE_PATH . '/config.php';
include_once NW_MODULE_PATH . '/class/class.newsstory.php';
include_once NW_MODULE_PATH . '/class/class.newstopic.php';
include_once NW_MODULE_PATH . '/class/class.sfiles.php';
include_once NW_MODULE_PATH . '/class/blacklist.php';
include_once NW_MODULE_PATH . '/class/registryfile.php';
include_once XOOPS_ROOT_PATH.'/class/uploader.php';
include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
include_once NW_MODULE_PATH . '/admin/functions.php';
include_once NW_MODULE_PATH . '/include/functions.php';
include_once XOOPS_ROOT_PATH.'/class/tree.php';
$dateformat=nw_getmoduleoption('dateformat', NW_MODULE_DIR_NAME);
$myts =& MyTextSanitizer::getInstance();
$topicscount=0;

$storiesTableName = $xoopsDB->prefix('nw_stories');
if(!nw_FieldExists('picture', $storiesTableName)) {
	nw_AddField('`picture` VARCHAR( 50 ) NOT NULL', $storiesTableName);
}

/**
 * Show new submissions
 *
 * This list can be view in the module's admin when you click on the tab named "Post/Edit News"
 * Submissions are news that was submit by users but who are not approved, so you need to edit
 * them to approve them.
 * Actually you can see the the story's title, the topic, the posted date, the author and a
 * link to delete the story. If you click on the story's title, you will be able to edit the news.
 * The table contains the last x new submissions.
 * The system's block called "Waiting Contents" is listing the number of those news.
 */
function newSubmissions()
{
    global $dateformat;
    $start = isset($_GET['startnew']) ? intval($_GET['startnew']) : 0;
    $newsubcount = nw_NewsStory :: getAllStoriesCount(3,false);
    $storyarray = nw_NewsStory :: getAllSubmitted(nw_getmoduleoption('storycountadmin', NW_MODULE_DIR_NAME),true,nw_getmoduleoption('restrictindex', NW_MODULE_DIR_NAME),$start);
    if ( count($storyarray)> 0) {
    	$pagenav = new XoopsPageNav( $newsubcount, nw_getmoduleoption('storycountadmin', NW_MODULE_DIR_NAME), $start, 'startnew', 'op=newarticle');
		nw_collapsableBar('newsub', 'topnewsubicon');
		echo "<img onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='topnewsubicon' name='topnewsubicon' src='" . NW_MODULE_URL . "/images/close12.gif' alt='' /></a>&nbsp;"._AM_NW_NEWSUB."</h4>";
		echo "<div id='newsub'>";
		echo '<br />';
        echo "<div style='text-align: center;'><table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr class='bg3'><td align='center'>" . _AM_NW_TITLE . "</td><td align='center'>" . _AM_NW_TOPIC . "</td><td align='center'>" . _AM_NW_POSTED . "</td><td align='center'>" . _AM_NW_POSTER . "</td><td align='center'>" . _AM_NW_ACTION . "</td></tr>\n";
        $class='';
        foreach( $storyarray as $newstory ) {
            $class = ($class == 'even') ? 'odd' : 'even';
            echo "<tr class='".$class."'><td align='left'>\n";
            $title = $newstory->title();
            if (!isset($title) || ($title == '' )) {
                echo "<a href='".NW_MODULE_URL . "/admin/index.php?op=edit&amp;returnside=1&amp;storyid=" . $newstory -> storyid() . "'>" . _AD_NOSUBJECT . "</a>\n";
            } else {
                echo "&nbsp;<a href='".NW_MODULE_URL . "/submit.php?returnside=1&amp;op=edit&amp;storyid=" . $newstory -> storyid() . "'>" . $title . "</a>\n";
            }
            echo "</td><td>" . $newstory->topic_title() . "</td><td align='center' class='news'>" . formatTimestamp($newstory->created(),$dateformat) . "</td><td align='center'><a href='" . XOOPS_URL . "/userinfo.php?uid=" . $newstory->uid() . "'>" . $newstory->uname() . "</a></td><td align='right'><a href='".NW_MODULE_URL . "/admin/index.php?op=delete&amp;storyid=" . $newstory->storyid() . "'>" . _AM_NW_DELETE . "</a></td></tr>\n";
        }
        echo '</table></div>';
        echo "<div align='right'>".$pagenav->renderNav().'</div><br />';
        echo '<br /></div><br />';
    }
}

/**
 * Shows all automated stories
 *
 * Automated stories are stories that have a publication's date greater than "now"
 * This list can be view in the module's admin when you click on the tab named "Post/Edit News"
 * Actually you can see the story's ID, its title, the topic, the author, the
 * programmed date and time, the expiration's date  and two links. The first link is
 * used to edit the story while the second is used to remove the story.
 * The list only contains the last (x) automated news
 */
function autoStories()
{
    global $dateformat;
    $start = isset($_GET['startauto']) ? intval($_GET['startauto']) : 0;
    $storiescount = nw_NewsStory :: getAllStoriesCount(2,false);
    $storyarray = nw_NewsStory :: getAllAutoStory(nw_getmoduleoption('storycountadmin', NW_MODULE_DIR_NAME),true,$start);
    $class='';
    if(count($storyarray) > 0) {
    	$pagenav = new XoopsPageNav($storiescount, nw_getmoduleoption('storycountadmin', NW_MODULE_DIR_NAME), $start, 'startauto', 'op=newarticle');
		nw_collapsableBar('autostories', 'topautostories');
		echo "<img onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='topautostories' name='topautostories' src='" . NW_MODULE_URL . "/images/close12.gif' alt='' /></a>&nbsp;"._AM_NW_AUTOARTICLES."</h4>";
		echo "<div id='autostories'>";
		echo '<br />';
        echo "<div style='text-align: center;'>\n";
        echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr class='bg3'><td align='center'>" . _AM_NW_STORYID . "</td><td align='center'>" . _AM_NW_TITLE . "</td><td align='center'>" . _AM_NW_TOPIC . "</td><td align='center'>" . _AM_NW_POSTER . "</td><td align='center' class='news'>" . _AM_NW_PROGRAMMED . "</td><td align='center' class='news'>" . _AM_NW_EXPIRED . "</td><td align='center'>" . _AM_NW_ACTION . "</td></tr>";
        foreach($storyarray as $autostory) {
            $topic = $autostory -> topic();
            $expire = ( $autostory->expired() > 0 ) ? formatTimestamp($autostory->expired(),$dateformat) : '';
            $class = ($class == 'even') ? 'odd' : 'even';
            echo "<tr class='".$class."'>";
        	echo "<td align='center'><b>" . $autostory -> storyid() . "</b>
        		</td><td align='left'><a href='" . NW_MODULE_URL . "/article.php?storyid=" . $autostory->storyid() . "'>" . $autostory->title() . "</a>
        		</td><td align='center'>" . $topic->topic_title() . "
        		</td><td align='center'><a href='" . XOOPS_URL . "/userinfo.php?uid=" . $autostory->uid() . "'>" . $autostory->uname() . "</a></td><td align='center' class='news'>" . formatTimestamp($autostory->published(),$dateformat) . "</td><td align='center'>" . $expire . "</td><td align='center'><a href='".NW_MODULE_URL . "/submit.php?returnside=1&amp;op=edit&amp;storyid=" . $autostory->storyid() . "'>" . _AM_NW_EDIT . "</a>-<a href='".NW_MODULE_URL . "/admin/index.php?op=delete&amp;storyid=" . $autostory->storyid() . "'>" . _AM_NW_DELETE . "</a>";
            echo "</td></tr>\n";
        }
        echo '</table></div>';
        echo "<div align='right'>".$pagenav->renderNav().'</div><br />';
        echo '</div><br />';
    }
}

/**
 * Shows last x published stories
 *
 * This list can be view in the module's admin when you click on the tab named "Post/Edit News"
 * Actually you can see the the story's ID, its title, the topic, the author, the number of hits
 * and two links. The first link is used to edit the story while the second is used to remove the story.
 * The table only contains the last X published stories.
 * You can modify the number of visible stories with the module's option named
 * "Number of new articles to display in admin area".
 * As the number of displayed stories is limited, below this list you can find a text box
 * that you can use to enter a story's Id, then with the scrolling list you can select
 * if you want to edit or delete the story.
 */
function lastStories()
{
    global $dateformat;
	nw_collapsableBar('laststories', 'toplaststories');
	echo "<img onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='toplaststories' name='toplaststories' src='" . NW_MODULE_URL . "/images/close12.gif' alt='' /></a>&nbsp;".sprintf(_AM_NW_LAST10ARTS,nw_getmoduleoption('storycountadmin', NW_MODULE_DIR_NAME))."</h4>";
	echo "<div id='laststories'>";
	echo '<br />';
    echo "<div style='text-align: center;'>";
    $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
    $storyarray = nw_NewsStory :: getAllPublished(nw_getmoduleoption('storycountadmin', NW_MODULE_DIR_NAME), $start, false, 0, 1 );
    $storiescount = nw_NewsStory :: getAllStoriesCount(4,false);
    $pagenav = new XoopsPageNav( $storiescount, nw_getmoduleoption('storycountadmin', NW_MODULE_DIR_NAME), $start, 'start', 'op=newarticle');
    $class='';
    echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr class='bg3'><td align='center'>" . _AM_NW_STORYID . "</td><td align='center'>" . _AM_NW_TITLE . "</td><td align='center'>" . _AM_NW_TOPIC . "</td><td align='center'>" . _AM_NW_POSTER . "</td><td align='center' class='news'>" . _AM_NW_PUBLISHED . "</td><td align='center' class='news'>" . _AM_NW_HITS . "</td><td align='center'>" . _AM_NW_ACTION . "</td></tr>";
    foreach( $storyarray as $eachstory ) {
        $published = formatTimestamp($eachstory->published(),$dateformat );
        // $expired = ( $eachstory -> expired() > 0 ) ? formatTimestamp($eachstory->expired(),$dateformat) : '---';
        $topic = $eachstory -> topic();
        $class = ($class == 'even') ? 'odd' : 'even';
        echo "<tr class='".$class."'>";
        echo "<td align='center'><b>" . $eachstory -> storyid() . "</b>
        	</td><td align='left'><a href='" . NW_MODULE_URL . "/article.php?storyid=" . $eachstory -> storyid() . "'>" . $eachstory -> title() . "</a>
        	</td><td align='center'>" . $topic -> topic_title() . "
        	</td><td align='center'><a href='" . XOOPS_URL . "/userinfo.php?uid=" . $eachstory -> uid() . "'>" . $eachstory -> uname() . "</a></td><td align='center' class='news'>" . $published . "</td><td align='center'>" . $eachstory -> counter() . "</td><td align='center'><a href='".NW_MODULE_URL . "/submit.php?returnside=1&amp;op=edit&amp;storyid=" . $eachstory -> storyid() . "'>" . _AM_NW_EDIT . "</a>-<a href='".NW_MODULE_URL . "/admin/index.php?op=delete&amp;storyid=" . $eachstory -> storyid() . "'>" . _AM_NW_DELETE . "</a>";
        echo "</td></tr>\n";
    }
    echo '</table><br />';
	echo "<div align='right'>".$pagenav->renderNav().'</div><br />';

    echo "<form action='index.php' method='get'>" . _AM_NW_STORYID . " <input type='text' name='storyid' size='10' />
    	<select name='op'>
    		<option value='edit' selected='selected'>" . _AM_NW_EDIT . "</option>
    		<option value='delete'>" . _AM_NW_DELETE . "</option>
    	</select>
		<input type='hidden' name='returnside' value='1'>
    	<input type='submit' value='" . _AM_NW_GO . "' />
    	</form>
	</div>";
    echo '</div><br />';
}


/**
 * Display a list of the expired stories
 *
 * This list can be view in the module's admin when you click on the tab named "Post/Edit News"
 * Actually you can see the story's ID, the title, the topic, the author,
 * the creation and expiration's date and you have two links, one to delete
 * the story and the other to edit the story.
 * The table only contains the last X expired stories.
 * You can modify the number of visible stories with the module's option named
 * "Number of new articles to display in admin area".
 * As the number of displayed stories is limited, below this list you can find a text box
 * that you can use to enter a story's Id, then with the scrolling list you can select
 * if you want to edit or delete the story.
 */
function expStories()
{
    global $dateformat;
    $start = isset($_GET['startexp']) ? intval($_GET['startexp']) : 0;
	$expiredcount = nw_NewsStory :: getAllStoriesCount(1,false);
	$storyarray = nw_NewsStory :: getAllExpired(nw_getmoduleoption('storycountadmin', NW_MODULE_DIR_NAME), $start, 0, 1 );
	$pagenav = new XoopsPageNav( $expiredcount, nw_getmoduleoption('storycountadmin', NW_MODULE_DIR_NAME), $start, 'startexp', 'op=newarticle');

    if(count($storyarray) > 0) {
    	$class='';
		nw_collapsableBar('expstories', 'topexpstories');
		echo "<img onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='topexpstories' name='topexpstories' src='" . NW_MODULE_URL . "/images/close12.gif' alt='' /></a>&nbsp;"._AM_NW_EXPARTS."</h4>";
		echo "<div id='expstories'>";
		echo '<br />';
    	echo "<div style='text-align: center;'>";
    	echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr class='bg3'><td align='center'>" . _AM_NW_STORYID . "</td><td align='center'>" . _AM_NW_TITLE . "</td><td align='center'>" . _AM_NW_TOPIC . "</td><td align='center'>" . _AM_NW_POSTER . "</td><td align='center' class='news'>" . _AM_NW_CREATED . "</td><td align='center' class='news'>" . _AM_NW_EXPIRED . "</td><td align='center'>" . _AM_NW_ACTION . "</td></tr>";
    	foreach( $storyarray as $eachstory ) {
	        $created = formatTimestamp($eachstory->created(),$dateformat);
        	$expired = formatTimestamp($eachstory->expired(),$dateformat);
        	$topic = $eachstory -> topic();
        	// added exired value field to table
        	$class = ($class == 'even') ? 'odd' : 'even';
        	echo "<tr class='".$class."'>";
        	echo "<td align='center'><b>" . $eachstory -> storyid() . "</b>
	        	</td><td align='left'><a href='" . NW_MODULE_URL . "/article.php?returnside=1&amp;storyid=" . $eachstory -> storyid() . "'>" . $eachstory -> title() . "</a>
        		</td><td align='center'>" . $topic -> topic_title() . "
        		</td><td align='center'><a href='" . XOOPS_URL . "/userinfo.php?uid=" . $eachstory -> uid() . "'>" . $eachstory -> uname() . "</a></td><td align='center' class='news'>" . $created . "</td><td align='center' class='news'>" . $expired . "</td><td align='center'><a href='".NW_MODULE_URL . "/submit.php?returnside=1&amp;op=edit&amp;storyid=" . $eachstory -> storyid() . "'>" . _AM_NW_EDIT . "</a>-<a href='".NW_MODULE_URL . "/admin/index.php?op=delete&amp;storyid=" . $eachstory -> storyid() . "'>" . _AM_NW_DELETE . "</a>";
        	echo "</td></tr>\n";
    	}
    	echo '</table><br />';
    	echo "<div align='right'>".$pagenav->renderNav().'</div><br />';
    	echo "<form action='index.php' method='get'>
	    	" . _AM_NW_STORYID . " <input type='text' name='storyid' size='10' />
    		<select name='op'>
	    		<option value='edit' selected='selected'>" . _AM_NW_EDIT . "</option>
    			<option value='delete'>" . _AM_NW_DELETE . "</option>
    		</select>
			<input type='hidden' name='returnside' value='1'>
    		<input type='submit' value='" . _AM_NW_GO . "' />
    		</form>
		</div>";
    	echo '</div><br />';
    }
}

/**
 * Delete (purge/prune) old stories
 *
 * You can use this function in the module's admin when you click on the tab named "Prune News"
 * It's useful to remove old stories. It is, of course, recommended
 * to backup (or export) your news before to purge news.
 * You must first specify a date. This date will be used as a reference, everything
 * that was published before this date will be deleted.
 * The option "Only remove stories who have expired" will enable you to only remove
 * expired stories published before the given date.
 * Finally, you can select the topics inside wich you will remove news.
 * Once you have set all the parameters, the script will first show you a confirmation's
 * message with the number of news that will be removed.
 * Note, the topics are not deleted (even if there are no more news inside them).
 */
function PruneManager()
{
    include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
    xoops_cp_header();
    adminmenu(3, _AM_NW_PRUNENEWS);  
    echo '<h2>' . _AM_NW_PRUNENEWS . '</h2><br />';
	$sform = new XoopsThemeForm(_AM_NW_PRUNENEWS, 'pruneform', NW_MODULE_URL . '/admin/index.php', 'post');
	$sform->addElement(new XoopsFormTextDateSelect(_AM_NW_PRUNE_BEFORE, 'prune_date',15,time()), true);
	$onlyexpired=new xoopsFormCheckBox('', 'onlyexpired');
	$onlyexpired->addOption(1, _AM_NW_PRUNE_EXPIREDONLY);
	$sform->addElement($onlyexpired, false);
	$sform->addElement(new XoopsFormHidden('op', 'confirmbeforetoprune'), false);
	$topiclist=new XoopsFormSelect(_AM_NW_PRUNE_TOPICS, 'pruned_topics','',5,true);
	$topics_arr=array();
	$xt = new nw_NewsTopic();
	$allTopics = $xt->getAllTopics(false);				// The webmaster can see everything
	$topic_tree = new XoopsObjectTree($allTopics, 'topic_id', 'topic_pid');
	$topics_arr = $topic_tree->getAllChild(0);
	if(count($topics_arr)) {
		foreach ($topics_arr as $onetopic) {
			$topiclist->addOption($onetopic->topic_id(),$onetopic->topic_title());
		}
	}
	$topiclist->setDescription(_AM_NW_EXPORT_PRUNE_DSC);
	$sform->addElement($topiclist,false);
	$button_tray = new XoopsFormElementTray('' ,'');
	$submit_btn = new XoopsFormButton('', 'post', _SUBMIT, 'submit');
	$button_tray->addElement($submit_btn);
	$sform->addElement($button_tray);
	$sform->display();
}

// A confirmation is asked before to prune stories
function ConfirmBeforeToPrune()
{
	global $dateformat;
	$story = new nw_NewsStory();
	xoops_cp_header();
	$topiclist='';
	if(isset($_POST['pruned_topics'])) {
		$topiclist=implode(',',$_POST['pruned_topics']);
	}
	echo '<h2>' . _AM_NW_PRUNENEWS . '</h2>';
	$expired=0;
	if(isset($_POST['onlyexpired'])) {
		$expired = intval($_POST['onlyexpired']);
	}
	$date=$_POST['prune_date'];
	$timestamp=mktime(0,0,0,intval(substr($date,5,2)), intval(substr($date,8,2)), intval(substr($date,0,4)));
	$count=$story->GetCountStoriesPublishedBefore($timestamp, $expired, $topiclist);
	if($count) {
		$displaydate=formatTimestamp($timestamp,$dateformat);
		$msg=sprintf(_AM_NW_PRUNE_CONFIRM,$displaydate, $count);
		xoops_confirm(array( 'op' => 'prunenews', 'expired' => $expired, 'pruned_topics' => $topiclist, 'prune_date' => $timestamp, 'ok' => 1), 'index.php', $msg);
	} else {
		printf(_AM_NW_NOTHING_PRUNE);
	}
	unset($story);
}

// Effectively delete stories (published before a date), no more confirmation
function PruneNews()
{
	$story = new nw_NewsStory();
	$timestamp=intval($_POST['prune_date']);
	$expired= intval($_POST['expired']);
	$topiclist='';
	if(isset($_POST['pruned_topics'])) {
		$topiclist=$_POST['pruned_topics'];
	}

	if(intval($_POST['ok'])==1) {
		$story = new nw_NewsStory();
		xoops_cp_header();
		$count=$story->GetCountStoriesPublishedBefore($timestamp,$expired,$topiclist);
		$msg=sprintf(_AM_NW_PRUNE_DELETED,$count);
		$story->DeleteBeforeDate($timestamp,$expired,$topiclist);
		unset($story);
		nw_updateCache();
		redirect_header( 'index.php', 3, $msg);
	}
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
    include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
    xoops_cp_header();
    adminmenu(5, _AM_NW_NEWSLETTER);
    echo '<h2>' . _AM_NW_NEWSLETTER . '</h2><br />';
	$sform = new XoopsThemeForm(_AM_NW_NEWSLETTER, 'newsletterform', NW_MODULE_URL . '/admin/index.php', 'post');
	$dates_tray = new XoopsFormElementTray(_AM_NW_NEWSLETTER_BETWEEN);
	$date1 = new XoopsFormTextDateSelect('', 'date1',15,time());
	$date2 = new XoopsFormTextDateSelect(_AM_NW_EXPORT_AND, 'date2',15,time());
	$dates_tray->addElement($date1);
	$dates_tray->addElement($date2);
	$sform->addElement($dates_tray);

	$topiclist=new XoopsFormSelect(_AM_NW_PRUNE_TOPICS, 'export_topics','',5,true);
	$topics_arr=array();
	$xt = new nw_NewsTopic();
	$allTopics = $xt->getAllTopics(false);				// The webmaster can see everything
	$topic_tree = new XoopsObjectTree($allTopics, 'topic_id', 'topic_pid');
	$topics_arr = $topic_tree->getAllChild(0);
	if(count($topics_arr)) {
		foreach ($topics_arr as $onetopic) {
			$topiclist->addOption($onetopic->topic_id(),$onetopic->topic_title());
		}
	}
	$topiclist->setDescription(_AM_NW_EXPORT_PRUNE_DSC);
	$sform->addElement($topiclist,false);
	$sform->addElement(new XoopsFormHidden('op', 'launchnewsletter'), false);
	$sform->addElement(new XoopsFormRadioYN(_AM_NW_REMOVE_BR, 'removebr',1),false);
	$sform->addElement(new XoopsFormRadioYN(_AM_NW_NEWSLETTER_HTML_TAGS, 'removehtml',0),false);
	$sform->addElement(new XoopsFormTextArea(_AM_NW_NEWSLETTER_HEADER, 'header', '', 4, 70), false);
	$sform->addElement(new XoopsFormTextArea(_AM_NW_NEWSLETTER_FOOTER, 'footer', '', 4, 70), false);
	$button_tray = new XoopsFormElementTray('' ,'');
	$submit_btn = new XoopsFormButton('', 'post', _SUBMIT, 'submit');
	$button_tray->addElement($submit_btn);
	$sform->addElement($button_tray);
	$sform->display();
}


/**
 * Launch the creation of the newsletter's content
 */
function LaunchNewsletter()
{
	global $xoopsConfig, $dateformat;
	xoops_cp_header();
	adminmenu(5);
	$newslettertemplate = '';
	if (file_exists(NW_MODULE_PATH . '/language/'.$xoopsConfig['language'].'/newsletter.php')) {
		include_once NW_MODULE_PATH . '/language/'.$xoopsConfig['language'].'/newsletter.php';
	} else {
		include_once NW_MODULE_PATH . '/language/english/newsletter.php';
	}
	echo '<br />';
	$story = new nw_NewsStory();
	$exportedstories = array();
	$topiclist = '';
	$removebr = $removehtml = false;
	$removebr = isset($_POST['removebr']) ? intval($_POST['removebr']) : 0;
	$removehtml = isset($_POST['removehtml']) ? intval($_POST['removehtml']) : 0;
	$header = isset($_POST['header']) ? $_POST['header'] : '';
	$footer = isset($_POST['footer']) ? $_POST['footer'] : '';
	$date1 = $_POST['date1'];
	$date2 = $_POST['date2'];
	$timestamp1 = mktime(0,0,0,intval(substr($date1,5,2)), intval(substr($date1,8,2)), intval(substr($date1,0,4)));
	$timestamp2 = mktime(23,59,59,intval(substr($date2,5,2)), intval(substr($date2,8,2)), intval(substr($date2,0,4)));
	if(isset($_POST['export_topics'])) {
		$topiclist = implode(',',$_POST['export_topics']);
	}
	$tbltopics = array();
	$exportedstories = $story->NewsExport($timestamp1, $timestamp2, $topiclist, 0, $tbltopics);
    $newsfile = XOOPS_ROOT_PATH.'/uploads/newsletter.txt';
	if(count($exportedstories)) {
		$fp = fopen($newsfile,'w');
		if(!$fp) {
			redirect_header('index.php',4,sprintf(_AM_NW_EXPORT_ERROR,$newsfile));
		}
		if(xoops_trim($header) != '') {
			fwrite($fp, $header);
		}
		foreach($exportedstories as $onestory) {
			$content = $newslettertemplate;
			$search_pattern = array('%title%','%uname%','%created%','%published%','%expired%','%hometext%','%bodytext%','%description%','%keywords%','%reads%','%topicid%','%topic_title%','%comments%','%rating%','%votes%','%publisher%','%publisher_id%','%link%');
			$replace_pattern = array($onestory->title(),$onestory->uname(),formatTimestamp($onestory->created(),$dateformat),formatTimestamp($onestory->published(),$dateformat),formatTimestamp($onestory->expired(),$dateformat),$onestory->hometext(),$onestory->bodytext(),$onestory->description(),$onestory->keywords(),$onestory->counter(),$onestory->topicid(),$onestory->topic_title(),$onestory->comments(),$onestory->rating(),$onestory->votes(),$onestory->uname(),$onestory->uid(),NW_MODULE_URL . '/article.php?storyid='.$onestory->storyid());
			$content = str_replace($search_pattern, $replace_pattern, $content);
			if($removebr) {
				$content = str_replace('<br />',"\r\n",$content);
			}
			if($removehtml) {
				$content = strip_tags($content);
			}
			fwrite($fp,$content);
		}
		if(xoops_trim($footer) != '') {
			fwrite($fp, $footer);
		}
		fclose($fp);
		$newsfile=XOOPS_URL.'/uploads/newsletter.txt';
		printf(_AM_NW_NEWSLETTER_READY,$newsfile,NW_MODULE_URL . '/admin/index.php?op=deletefile&amp;type=newsletter');
	} else {
		printf(_AM_NW_NOTHING);
	}
}



/**
* News export
*
* You can use this function in the module's admin when you click on the tab named "News Export"
* First select a range of date, possibly a range of topics and if you want, check the option "Include Topics Definitions"
* to also export the topics.
* News, and topics, will be exported to the XML format.
*/
function NewsExport()
{
    include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
    xoops_cp_header();
    adminmenu(4, _AM_NW_EXPORT_NEWS);
    echo '<h2>' . _AM_NW_EXPORT_NEWS . '</h2><br />';
	$sform = new XoopsThemeForm(_AM_NW_EXPORT_NEWS, 'exportform', NW_MODULE_URL . '/admin/index.php', 'post');
	$dates_tray = new XoopsFormElementTray(_AM_NW_EXPORT_BETWEEN);
	$date1 = new XoopsFormTextDateSelect('', 'date1',15,time());
	$date2 = new XoopsFormTextDateSelect(_AM_NW_EXPORT_AND, 'date2',15,time());
	$dates_tray->addElement($date1);
	$dates_tray->addElement($date2);
	$sform->addElement($dates_tray);

	$topiclist=new XoopsFormSelect(_AM_NW_PRUNE_TOPICS, 'export_topics','',5,true);
	$topics_arr=array();
	$xt = new nw_NewsTopic();
	$allTopics = $xt->getAllTopics(false);				// The webmaster can see everything
	$topic_tree = new XoopsObjectTree($allTopics, 'topic_id', 'topic_pid');
	$topics_arr = $topic_tree->getAllChild(0);
	if(count($topics_arr)) {
		foreach ($topics_arr as $onetopic) {
			$topiclist->addOption($onetopic->topic_id(),$onetopic->topic_title());
		}
	}
	$topiclist->setDescription(_AM_NW_EXPORT_PRUNE_DSC);
	$sform->addElement($topiclist,false);
	$sform->addElement(new XoopsFormRadioYN(_AM_NW_EXPORT_INCTOPICS, 'includetopics',0),false);
	$sform->addElement(new XoopsFormHidden('op', 'launchexport'), false);
	$button_tray = new XoopsFormElementTray('' ,'');
	$submit_btn = new XoopsFormButton('', 'post', _SUBMIT, 'submit');
	$button_tray->addElement($submit_btn);
	$sform->addElement($button_tray);
	$sform->display();
}


function nw_utf8_encode($text)
{
	return xoops_utf8_encode($text);
}

// Launch stories export (to the xml's format)
function LaunchExport()
{
	xoops_cp_header();
	adminmenu(4);
	echo '<br />';
	$story = new nw_NewsStory();
	$topic= new nw_NewsTopic();
	$exportedstories=array();
	$date1=$_POST['date1'];
	$date2=$_POST['date2'];
	$timestamp1=mktime(0,0,0,intval(substr($date1,5,2)), intval(substr($date1,8,2)), intval(substr($date1,0,4)));
	$timestamp2=mktime(23,59,59,intval(substr($date2,5,2)), intval(substr($date2,8,2)), intval(substr($date2,0,4)));
	$topiclist='';
	if(isset($_POST['export_topics'])) {
		$topiclist=implode(',',$_POST['export_topics']);
	}
	$topicsexport=intval($_POST['includetopics']);
	$tbltopics=array();
	$exportedstories=$story->NewsExport($timestamp1, $timestamp2, $topiclist, $topicsexport, $tbltopics);
	if(count($exportedstories)) {
		$xmlfile=XOOPS_ROOT_PATH.'/uploads/nw_stories.xml';
		$fp=fopen($xmlfile,'w');
		if(!$fp) {
			redirect_header('index.php',4,sprintf(_AM_NW_EXPORT_ERROR,$xmlfile));
		}

		fwrite($fp,nw_utf8_encode("<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n"));
		fwrite($fp,nw_utf8_encode("<nw_stories>\n"));
		if($topicsexport) {
			foreach($tbltopics as $onetopic) {
				$topic->nw_NewsTopic($onetopic);
				$content = "<nw_topic>\n";
				$content .= sprintf("\t<topic_id>%u</topic_id>\n",$topic->topic_id());
				$content .= sprintf("\t<topic_pid>%u</topic_pid>\n",$topic->topic_pid());
				$content .= sprintf("\t<topic_imgurl>%s</topic_imgurl>\n",$topic->topic_imgurl());
				$content .= sprintf("\t<topic_title>%s</topic_title>\n",$topic->topic_title('F'));
				$content .= sprintf("\t<menu>%d</menu>\n",$topic->menu());
				$content .= sprintf("\t<topic_frontpage>%d</topic_frontpage>\n",$topic->topic_frontpage());
				$content .= sprintf("\t<topic_rssurl>%s</topic_rssurl>\n",$topic->topic_rssurl('E'));
				$content .= sprintf("\t<topic_description>%s</topic_description>\n",$topic->topic_description());
				$content .= sprintf("</nw_topic>\n");
				$content = nw_utf8_encode($content);
				fwrite($fp,$content);
			}
		}

		foreach($exportedstories as $onestory) {
			$content = "<xoops_story>\n";
    		$content .= sprintf("\t<storyid>%u</storyid>\n",$onestory->storyid());
    		$content .= sprintf("\t<uid>%u</uid>\n",$onestory->uid());
    		$content .= sprintf("\t<uname>%s</uname>\n",$onestory->uname());
    		$content .= sprintf("\t<title>%s</title>\n",$onestory->title());
    		$content .= sprintf("\t<created>%u</created>\n",$onestory->created());
    		$content .= sprintf("\t<published>%u</published>\n",$onestory->published());
    		$content .= sprintf("\t<expired>%u</expired>\n",$onestory->expired());
    		$content .= sprintf("\t<hostname>%s</hostname>\n",$onestory->hostname());
    		$content .= sprintf("\t<nohtml>%d</nohtml>\n",$onestory->nohtml());
    		$content .= sprintf("\t<nosmiley>%d</nosmiley>\n",$onestory->nosmiley());
    		$content .= sprintf("\t<dobr>%d</dobr>\n",$onestory->dobr());
    		$content .= sprintf("\t<hometext>%s</hometext>\n",$onestory->hometext());
    		$content .= sprintf("\t<bodytext>%s</bodytext>\n",$onestory->bodytext());
    		$content .= sprintf("\t<description>%s</description>\n",$onestory->description());
    		$content .= sprintf("\t<keywords>%s</keywords>\n",$onestory->keywords());
    		$content .= sprintf("\t<counter>%u</counter>\n",$onestory->counter());
    		$content .= sprintf("\t<topicid>%u</topicid>\n",$onestory->topicid());
    		$content .= sprintf("\t<ihome>%d</ihome>\n",$onestory->ihome());
    		$content .= sprintf("\t<notifypub>%d</notifypub>\n",$onestory->notifypub());
    		$content .= sprintf("\t<story_type>%s</story_type>\n",$onestory->type());
    		$content .= sprintf("\t<topicdisplay>%d</topicdisplay>\n",$onestory->topicdisplay());
    		$content .= sprintf("\t<topicalign>%s</topicalign>\n",$onestory->topicalign());
    		$content .= sprintf("\t<comments>%u</comments>\n",$onestory->comments());
    		$content .= sprintf("\t<rating>%f</rating>\n",$onestory->rating());
	    	$content .= sprintf("\t<votes>%u</votes>\n",$onestory->votes());
    		$content .= sprintf("</xoops_story>\n");
    		$content = nw_utf8_encode($content);
    		fwrite($fp,$content);
		}
		fwrite($fp,nw_utf8_encode("</nw_stories>\n"));
		fclose($fp);
		$xmlfile=XOOPS_URL.'/uploads/nw_stories.xml';
		printf(_AM_NW_EXPORT_READY,$xmlfile,NW_MODULE_URL . '/admin/index.php?op=deletefile&amp;type=xml');
	} else {
		printf(_AM_NW_EXPORT_NOTHING);
	}
}



/*
* Topics manager
*
* It's from here that you can list, add, modify an delete topics
* At first, you can see a list of all the topics in your databases. This list contains the topic's ID, its name,
* its parent topic, if it should be visible in the Xoops main menu and an action (Edit or Delete topic)
* Below this list you find the form used to create and edit the topics.
* use this form to :
* - Type the topic's title
* - Enter its description
* - Select its parent topic
* - Choose a color
* - Select if it must appear in the Xoops main menu
* - Choose if you want to see in the front page. If it's not the case, visitors will have to use the navigation box to see it
* - And finally you ca select an image to represent the topic
* The text box called "URL of RSS feed" is, for this moment, not used.
*/
function topicsmanager()
{
    global $xoopsDB, $xoopsConfig, $xoopsModule, $myts;
    include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
    xoops_cp_header();
    adminmenu(0, _AM_NW_TOPICSMNGR);
    $uploadfolder=sprintf(_AM_NW_UPLOAD_WARNING, NW_TOPICS_FILES_URL);
    $uploadirectory='/uploads/' . $xoopsModule -> dirname().'/images/topics';
    $start = isset($_GET['start']) ? intval($_GET['start']) : 0;

	$xt = new XoopsTree($xoopsDB->prefix('nw_topics'), 'topic_id', 'topic_pid');
	$topics_arr = $xt->getChildTreeArray(0,'topic_title');
	$totaltopics = count($topics_arr);
	$class='';

    echo '<h2>' . _AM_NW_TOPICSMNGR . '</h2>';
	nw_collapsableBar('topicsmanager', 'toptopicsmanager');
	echo "<img onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='toptopicsmanager' name='toptopicsmanager' src='" . NW_MODULE_URL . "/images/close12.gif' alt='' /></a>&nbsp;"._AM_NW_TOPICS . ' (' . $totaltopics . ')'."</h4>";
	echo "<div id='topicsmanager'>";
	echo '<br />';
    echo "<div style='text-align: center;'>";
    echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr class='bg3'><td align='center'>" . _AM_NW_TOPIC . "</td><td align='left'>" . _AM_NW_TOPICNAME . "</td><td align='center'>" . _AM_NW_PARENTTOPIC . "</td><td align='center'>" . _AM_NW_SUB_MENU_YESNO . "</td><td align='center'>" . _AM_NW_ACTION . "</td></tr>";
	if(is_array($topics_arr) && $totaltopics) {
		$cpt=1;
		$tmpcpt=$start;
		$ok=true;
		$output='';
		while($ok) {
			if($tmpcpt < $totaltopics) {
				$linkedit = NW_MODULE_URL . '/admin/index.php?op=topicsmanager&amp;topic_id=' . $topics_arr[$tmpcpt]['topic_id'];
				$linkdelete = NW_MODULE_URL . '/admin/index.php?op=delTopic&amp;topic_id=' . $topics_arr[$tmpcpt]['topic_id'];
				$action=sprintf("<a href='%s'>%s</a> - <a href='%s'>%s</a>",$linkedit,_AM_NW_EDIT , $linkdelete, _AM_NW_DELETE);
				$parent='&nbsp;';
				if($topics_arr[$tmpcpt]['topic_pid']>0)	{
					$xttmp = new XoopsTopic($xoopsDB->prefix('nw_topics'),$topics_arr[$tmpcpt]['topic_pid']);
					$parent = $xttmp->topic_title();
					unset($xttmp);
				}
				if($topics_arr[$tmpcpt]['topic_pid']!=0) {
					$topics_arr[$tmpcpt]['prefix'] = str_replace('.','-',$topics_arr[$tmpcpt]['prefix']) . '&nbsp;';
				} else {
					$topics_arr[$tmpcpt]['prefix'] = str_replace('.','',$topics_arr[$tmpcpt]['prefix']);
				}
				$submenu=$topics_arr[$tmpcpt]['menu'] ? _YES : _NO;
				$class = ($class == 'even') ? 'odd' : 'even';
				$output  = $output . "<tr class='".$class."'><td>" . $topics_arr[$tmpcpt]['topic_id'] . "</td><td align='left'>" . $topics_arr[$tmpcpt]['prefix'] . $myts->displayTarea($topics_arr[$tmpcpt]['topic_title']) . "</td><td align='left'>" . $parent . "</td><td>" . $submenu . "</td><td>" . $action . "</td></tr>";
			} else {
				$ok=false;
			}
			if($cpt>=nw_getmoduleoption('storycountadmin', NW_MODULE_DIR_NAME)) {
				$ok=false;
			}
			$tmpcpt++;
			$cpt++;
		}
		echo $output;
	}
	$pagenav = new XoopsPageNav( $totaltopics, nw_getmoduleoption('storycountadmin', NW_MODULE_DIR_NAME), $start, 'start', 'op=topicsmanager');
	echo "</table><div align='right'>".$pagenav->renderNav().'</div><br />';
	echo "</div></div><br />\n";

	$topic_id = isset($_GET['topic_id']) ? intval($_GET['topic_id']) : 0;
	if($topic_id>0) {
		$xtmod = new nw_NewsTopic($topic_id);
		$topic_title=$xtmod->topic_title('E');
		$topic_description=$xtmod->topic_description('E');
		$topic_rssfeed=$xtmod->topic_rssurl('E');
		$op='modTopicS';
		if(xoops_trim($xtmod->topic_imgurl())!='') {
			$topicimage=$xtmod->topic_imgurl();
		} else {
			$topicimage='blank.png';
		}
		$btnlabel=_AM_NW_MODIFY;
		$parent=$xtmod->topic_pid();
		$formlabel=_AM_NW_MODIFYTOPIC;
		$submenu=$xtmod->menu();
		$topic_frontpage=$xtmod->topic_frontpage();
		$topic_color=$xtmod->topic_color();
		unset($xtmod);
	} else {
		$topic_title='';
		$topic_frontpage=1;
		$topic_description='';
		$op='addTopic';
		$topicimage='xoops.gif';
		$btnlabel=_AM_NW_ADD;
		$parent=-1;
		$submenu=0;
		$topic_rssfeed='';
		$formlabel=_AM_NW_ADD_TOPIC;
		$topic_color='000000';
	}

	$sform = new XoopsThemeForm($formlabel, 'topicform', NW_MODULE_URL . '/admin/index.php', 'post');
	$sform->setExtra('enctype="multipart/form-data"');
	$sform->addElement(new XoopsFormText(_AM_NW_TOPICNAME, 'topic_title', 50, 255, $topic_title), true);
	$editor=nw_getWysiwygForm(_AM_NW_TOPIC_DESCR,'topic_description', $topic_description, 15, 60, '100%', '350px', 'hometext_hidden');
	if($editor) {
		$sform->addElement($editor,false);
	}

	$sform->addElement(new XoopsFormHidden('op', $op), false);
	$sform->addElement(new XoopsFormHidden('topic_id', $topic_id), false);

	include_once NW_MODULE_PATH . '/class/class.newstopic.php';
	$xt = new nw_NewsTopic();
	$sform->addElement(new XoopsFormLabel(_AM_NW_PARENTTOPIC, $xt->MakeMyTopicSelBox(1, $parent,'topic_pid','',false)));
	// Topic's color
	// Code stolen to Zoullou, thank you Zoullou ;-)
	$select_color = "\n<select name='topic_color'  onchange='xoopsGetElementById(\"NewsColorSelect\").style.backgroundColor = \"#\" + this.options[this.selectedIndex].value;'>\n<option value='000000'>"._AM_NW_COLOR."</option>\n";
	$color_values = array('000000','000033','000066','000099','0000CC','0000FF','003300','003333','003366','0033CC','0033FF','006600','006633',
							'006666','006699','0066CC','0066FF','009900','009933','009966','009999','0099CC','0099FF','00CC00','00CC33','00CC66','00CC99',
							'00CCCC','00CCFF','00FF00','00FF33','00FF66','00FF99','00FFCC','00FFFF','330000','330033','330066','330099','3300CC','3300FF',
							'333300','333333','333366','333399','3333CC','3333FF','336600','336633','336666','336699','3366CC','3366FF','339900','339933',
							'339966','339999','3399CC','3399FF','33CC00','33CC33','33CC66','33CC99','33CCCC','33CCFF','33FF00','33FF33','33FF66','33FF99',
							'33FFCC','33FFFF','660000','660033','660066','660099','6600CC','6600FF','663300','663333','663366','663399','6633CC','6633FF',
							'666600','666633','666666','666699','6666CC','6666FF','669900','669933','669966','669999','6699CC','6699FF','66CC00','66CC33',
							'66CC66','66CC99','66CCCC','66CCFF','66FF00','66FF33','66FF66','66FF99','66FFCC','66FFFF','990000','990033','990066','990099',
							'9900CC','9900FF','993300','993333','993366','993399','9933CC','9933FF','996600','996633','996666','996699','9966CC','9966FF',
							'999900','999933','999966','999999','9999CC','9999FF','99CC00','99CC33','99CC66','99CC99','99CCCC','99CCFF','99FF00','99FF33',
							'99FF66','99FF99','99FFCC','99FFFF','CC0000','CC0033','CC0066','CC0099','CC00CC','CC00FF','CC3300','CC3333','CC3366','CC3399',
							'CC33CC','CC33FF','CC6600','CC6633','CC6666','CC6699','CC66CC','CC66FF','CC9900','CC9933','CC9966','CC9999','CC99CC','CC99FF',
							'CCCC00','CCCC33','CCCC66','CCCC99','CCCCCC','CCCCFF','CCFF00','CCFF33','CCFF66','CCFF99','CCFFCC','CCFFFF','FF0000','FF0033',
							'FF0066','FF0099','FF00CC','FF00FF','FF3300','FF3333','FF3366','FF3399','FF33CC','FF33FF','FF6600','FF6633','FF6666','FF6699',
							'FF66CC','FF66FF','FF9900','FF9933','FF9966','FF9999','FF99CC','FF99FF','FFCC00','FFCC33','FFCC66','FFCC99','FFCCCC','FFCCFF',
							'FFFF00','FFFF33','FFFF66','FFFF99','FFFFCC','FFFFFF');

	foreach($color_values as $color_value) {
		if($topic_color == $color_value) {
			$selected = " selected='selected'";
		} else {
			$selected = '';
		}
		$select_color .= "<option".$selected." value='".$color_value."' style='background-color:#".$color_value.";color:#".$color_value.";'>#".$color_value."</option>\n";
	}

	$select_color .= "</select>&nbsp;\n<span id='NewsColorSelect'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
	$sform->addElement( new XoopsFormLabel( _AM_NW_TOPIC_COLOR, $select_color) );
	// Sub menu ?
	$sform->addElement(new XoopsFormRadioYN(_AM_NW_SUB_MENU, 'submenu', $submenu, _YES, _NO));
	$sform->addElement(new XoopsFormRadioYN(_AM_NW_PUBLISH_FRONTPAGE, 'topic_frontpage', $topic_frontpage, _YES, _NO));
	// Unused for this moment... sorry
	//$sform->addElement(new XoopsFormText(_AM_NW_RSS_URL, 'topic_rssfeed', 50, 255, $topic_rssfeed), false);
	// ********** Picture
	$imgtray = new XoopsFormElementTray(_AM_NW_TOPICIMG,'<br />');

	$imgpath=sprintf(_AM_NW_IMGNAEXLOC, 'uploads/' . $xoopsModule -> dirname() . '/images/topics/' );
	$imageselect= new XoopsFormSelect($imgpath, 'topic_imgurl',$topicimage);
    $topics_array = XoopsLists :: getImgListAsArray( NW_TOPICS_FILES_PATH );
    foreach( $topics_array as $image ) {
        $imageselect->addOption("$image", $image);
    }
	$imageselect->setExtra( "onchange='showImgSelected(\"image3\", \"topic_imgurl\", \"" . $uploadirectory . "\", \"\", \"" . XOOPS_URL . "\")'" );
    $imgtray->addElement($imageselect,false);
    $imgtray -> addElement( new XoopsFormLabel( '', "<br /><img src='" . XOOPS_URL . "/" . $uploadirectory . "/" . $topicimage . "' name='image3' id='image3' alt='' />" ) );

    $uploadfolder=sprintf(_AM_NW_UPLOAD_WARNING, NW_TOPICS_FILES_URL);
    $fileseltray= new XoopsFormElementTray('','<br />');
    $fileseltray->addElement(new XoopsFormFile(_AM_NW_TOPIC_PICTURE , 'attachedfile', nw_getmoduleoption('maxuploadsize', NW_MODULE_DIR_NAME)), false);
    $fileseltray->addElement(new XoopsFormLabel($uploadfolder ), false);
    $imgtray->addElement($fileseltray);
    $sform->addElement($imgtray);

	// Permissions
    $member_handler = & xoops_gethandler('member');
    $group_list = &$member_handler->getGroupList();
    $gperm_handler = &xoops_gethandler('groupperm');
    $full_list = array_keys($group_list);

	$groups_ids = array();
    if($topic_id > 0) {		// Edit mode
    	$groups_ids = $gperm_handler->getGroupIds('nw_approve', $topic_id, $xoopsModule->getVar('mid'));
    	$groups_ids = array_values($groups_ids);
    	$groups_news_can_approve_checkbox = new XoopsFormCheckBox(_AM_NW_APPROVEFORM, 'groups_news_can_approve[]', $groups_ids);
    } else {	// Creation mode
    	$groups_news_can_approve_checkbox = new XoopsFormCheckBox(_AM_NW_APPROVEFORM, 'groups_news_can_approve[]', $full_list);
    }
    $groups_news_can_approve_checkbox->addOptionArray($group_list);
    $sform->addElement($groups_news_can_approve_checkbox);

	$groups_ids = array();
    if($topic_id > 0) {		// Edit mode
    	$groups_ids = $gperm_handler->getGroupIds('nw_submit', $topic_id, $xoopsModule->getVar('mid'));
    	$groups_ids = array_values($groups_ids);
    	$groups_news_can_submit_checkbox = new XoopsFormCheckBox(_AM_NW_SUBMITFORM, 'groups_news_can_submit[]', $groups_ids);
    } else {	// Creation mode
    	$groups_news_can_submit_checkbox = new XoopsFormCheckBox(_AM_NW_SUBMITFORM, 'groups_news_can_submit[]', $full_list);
    }
    $groups_news_can_submit_checkbox->addOptionArray($group_list);
    $sform->addElement($groups_news_can_submit_checkbox);

	$groups_ids = array();
    if($topic_id > 0) {		// Edit mode
    	$groups_ids = $gperm_handler->getGroupIds('nw_view', $topic_id, $xoopsModule->getVar('mid'));
    	$groups_ids = array_values($groups_ids);
    	$groups_news_can_view_checkbox = new XoopsFormCheckBox(_AM_NW_VIEWFORM, 'groups_news_can_view[]', $groups_ids);
    } else {	// Creation mode
    	$groups_news_can_view_checkbox = new XoopsFormCheckBox(_AM_NW_VIEWFORM, 'groups_news_can_view[]', $full_list);
    }
    $groups_news_can_view_checkbox->addOptionArray($group_list);
    $sform->addElement($groups_news_can_view_checkbox);

	// Submit buttons
	$button_tray = new XoopsFormElementTray('' ,'');
	$submit_btn = new XoopsFormButton('', 'post', $btnlabel, 'submit');
	$button_tray->addElement($submit_btn);
	$sform->addElement($button_tray);
	$sform->display();
	echo "<script type='text/javascript'>\n";
	echo 'xoopsGetElementById("NewsColorSelect").style.backgroundColor = "#' . $topic_color .'";';
	echo "</script>\n";
}


// Save a topic after it has been modified
function modTopicS()
{
    global $xoopsDB, $xoopsModule, $xoopsModuleConfig;

    $xt = new nw_NewsTopic(intval($_POST['topic_id']));
    if (intval($_POST['topic_pid']) == intval($_POST['topic_id'])) {
        redirect_header( 'index.php?op=topicsmanager', 2, _AM_NW_ADD_TOPIC_ERROR1 );
    }
    $xt->setTopicPid(intval($_POST['topic_pid']));
    if (empty($_POST['topic_title'])) {
        redirect_header( 'index.php?op=topicsmanager', 2, _AM_NW_ERRORTOPICNAME );
    }
    if(isset($_SESSION['items_count'])) {
    	$_SESSION['items_count'] = -1;
    }
    $xt -> setTopicTitle($_POST['topic_title']);
    if (isset($_POST['topic_imgurl']) && $_POST['topic_imgurl']!= '') {
        $xt -> setTopicImgurl($_POST['topic_imgurl']);
    }
   	$xt->setMenu(intval($_POST['submenu']));
   	$xt->setTopicFrontpage(intval($_POST['topic_frontpage']));
   	if(isset($_POST['topic_description'])) {
   	$xt->setTopicDescription($_POST['topic_description']);
   	} else {
   		$xt->setTopicDescription('');
   	}
   	//$xt->Settopic_rssurl($_POST['topic_rssfeed']);
   	$xt->setTopic_color($_POST['topic_color']);

	if(isset($_POST['xoops_upload_file'])) {
		$fldname = $_FILES[$_POST['xoops_upload_file'][0]];
		$fldname = (get_magic_quotes_gpc()) ? stripslashes($fldname['name']) : $fldname['name'];
		if(xoops_trim($fldname!='')) {
			$sfiles = new nw_sFiles();
			$dstpath = NW_TOPICS_FILES_PATH;
			$destname=$sfiles->createUploadName($dstpath ,$fldname, true);
			$permittedtypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
			$uploader = new XoopsMediaUploader($dstpath, $permittedtypes, $xoopsModuleConfig['maxuploadsize']);
			$uploader->setTargetFileName($destname);
			if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
				if ($uploader->upload()) {
					$xt->setTopicImgurl(basename($destname));
				} else {
					echo _AM_NW_UPLOAD_ERROR . ' ' . $uploader->getErrors();
				}
			} else {
				echo $uploader->getErrors();
			}
		}
   	}
    $xt->store();

	// Permissions
	$gperm_handler = &xoops_gethandler('groupperm');
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('gperm_itemid', $xt->topic_id(), '='));
	$criteria->add(new Criteria('gperm_modid', $xoopsModule->getVar('mid'),'='));
	$criteria->add(new Criteria('gperm_name', 'nw_approve', '='));
	$gperm_handler->deleteAll($criteria);

	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('gperm_itemid', $xt->topic_id(), '='));
	$criteria->add(new Criteria('gperm_modid', $xoopsModule->getVar('mid'),'='));
	$criteria->add(new Criteria('gperm_name', 'nw_submit', '='));
	$gperm_handler->deleteAll($criteria);

	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('gperm_itemid', $xt->topic_id(), '='));
	$criteria->add(new Criteria('gperm_modid', $xoopsModule->getVar('mid'),'='));
	$criteria->add(new Criteria('gperm_name', 'nw_view', '='));
	$gperm_handler->deleteAll($criteria);

	if(isset($_POST['groups_news_can_approve'])) {
		foreach($_POST['groups_news_can_approve'] as $onegroup_id) {
			$gperm_handler->addRight('nw_approve', $xt->topic_id(), $onegroup_id, $xoopsModule->getVar('mid'));
		}
	}

	if(isset($_POST['groups_news_can_submit'])) {
		foreach($_POST['groups_news_can_submit'] as $onegroup_id) {
			$gperm_handler->addRight('nw_submit', $xt->topic_id(), $onegroup_id, $xoopsModule->getVar('mid'));
		}
	}

	if(isset($_POST['groups_news_can_view'])) {
		foreach($_POST['groups_news_can_view'] as $onegroup_id) {
			$gperm_handler->addRight('nw_view', $xt->topic_id(), $onegroup_id, $xoopsModule->getVar('mid'));
		}
	}

    nw_updateCache();
    redirect_header( 'index.php?op=topicsmanager', 1, _AM_NW_DBUPDATED );
    exit();
}

// Delete a topic and its subtopics and its stories and the related stories
function delTopic()
{
    global $xoopsDB, $xoopsModule;
    if (!isset($_POST['ok'])) {
        xoops_cp_header();
        echo '<h2>' . _AM_NW_TOPICSMNGR . '</h2>';
        $xt = new XoopsTopic( $xoopsDB->prefix('nw_topics'), intval($_GET['topic_id']));
        xoops_confirm(array( 'op' => 'delTopic', 'topic_id' => intval($_GET['topic_id']), 'ok' => 1), 'index.php', _AM_NW_WAYSYWTDTTAL . '<br />' . $xt->topic_title('S'));
    } else {
    	xoops_cp_header();
        $xt = new XoopsTopic($xoopsDB->prefix('nw_topics'), intval($_POST['topic_id']));
	    if(isset($_SESSION['items_count'])) {
    		$_SESSION['items_count'] = -1;
    	}
        // get all subtopics under the specified topic
        $topic_arr = $xt->getAllChildTopics();
        array_push( $topic_arr, $xt );
        foreach( $topic_arr as $eachtopic ) {
            // get all stories in each topic
            $story_arr = nw_NewsStory :: getByTopic( $eachtopic -> topic_id() );
            foreach( $story_arr as $eachstory ) {
                if (false != $eachstory->delete()) {
                    xoops_comment_delete( $xoopsModule -> getVar( 'mid' ), $eachstory -> storyid() );
                    xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'story', $eachstory->storyid());
                }
            }
            // all stories for each topic is deleted, now delete the topic data
            $eachtopic -> delete();
            // Delete also the notifications and permissions
            xoops_notification_deletebyitem( $xoopsModule -> getVar( 'mid' ), 'category', $eachtopic -> topic_id );
			xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'nw_approve', $eachtopic -> topic_id);
			xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'nw_submit', $eachtopic -> topic_id);
			xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'nw_view', $eachtopic -> topic_id);
        }
        nw_updateCache();
        redirect_header( 'index.php?op=topicsmanager', 1, _AM_NW_DBUPDATED );
        exit();
    }
}

// Add a new topic
function addTopic()
{
	global $xoopsDB, $xoopsModule, $xoopsModuleConfig;
    $topicpid = isset($_POST['topic_pid']) ? intval($_POST['topic_pid']) : 0;
    $xt = new nw_NewsTopic();
    if (!$xt->topicExists($topicpid, $_POST['topic_title'])) {
        $xt->setTopicPid($topicpid);
        if (empty($_POST['topic_title']) || xoops_trim($_POST['topic_title'])=='') {
            redirect_header( 'index.php?op=topicsmanager', 2, _AM_NW_ERRORTOPICNAME );
        }
        $xt->setTopicTitle($_POST['topic_title']);
        //$xt->Settopic_rssurl($_POST['topic_rssfeed']);
        $xt->setTopic_color($_POST['topic_color']);
        if (isset($_POST['topic_imgurl'] ) && $_POST['topic_imgurl'] != '') {
            $xt->setTopicImgurl($_POST['topic_imgurl'] );
        }
		$xt->setMenu(intval($_POST['submenu']));
		$xt->setTopicFrontpage(intval($_POST['topic_frontpage']));
	    if(isset($_SESSION['items_count'])) {
    		$_SESSION['items_count'] = -1;
    	}
		if(isset($_POST['xoops_upload_file'])) {
			$fldname = $_FILES[$_POST['xoops_upload_file'][0]];
			$fldname = (get_magic_quotes_gpc()) ? stripslashes($fldname['name']) : $fldname['name'];
			if(xoops_trim($fldname!='')) {
				$sfiles = new nw_sFiles();
				$dstpath = NW_TOPICS_FILES_PATH;
				$destname=$sfiles->createUploadName($dstpath ,$fldname, true);
				$permittedtypes=array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
				$uploader = new XoopsMediaUploader($dstpath, $permittedtypes, $xoopsModuleConfig['maxuploadsize']);
				$uploader->setTargetFileName($destname);
				if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
					if ($uploader->upload()) {
						$xt->setTopicImgurl(basename($destname));
					} else {
						echo _AM_NW_UPLOAD_ERROR . ' ' . $uploader->getErrors();
					}
				} else {
					echo $uploader->getErrors();
				}
			}
		}
		if(isset($_POST['topic_description'])) {
		$xt->setTopicDescription($_POST['topic_description']);
		} else {
			$xt->setTopicDescription('');
		}
		$xt->store();
		// Permissions
		$gperm_handler = &xoops_gethandler('groupperm');
		if(isset($_POST['groups_news_can_approve'])) {
			foreach($_POST['groups_news_can_approve'] as $onegroup_id) {
				$gperm_handler->addRight('nw_approve', $xt->topic_id(), $onegroup_id, $xoopsModule->getVar('mid'));
			}
		}

		if(isset($_POST['groups_news_can_submit'])) {
			foreach($_POST['groups_news_can_submit'] as $onegroup_id) {
				$gperm_handler->addRight('nw_submit', $xt->topic_id(), $onegroup_id, $xoopsModule->getVar('mid'));
			}
		}

		if(isset($_POST['groups_news_can_view'])) {
			foreach($_POST['groups_news_can_view'] as $onegroup_id) {
				$gperm_handler->addRight('nw_view', $xt->topic_id(), $onegroup_id, $xoopsModule->getVar('mid'));
			}
		}
		nw_updateCache();

        $notification_handler = & xoops_gethandler('notification');
        $tags = array();
        $tags['TOPIC_NAME'] = $_POST['topic_title'];
        $notification_handler->triggerEvent( 'global', 0, 'new_category', $tags);
        redirect_header('index.php?op=topicsmanager', 1, _AM_NW_DBUPDATED);
    } else {
        redirect_header('index.php?op=topicsmanager', 2, _AM_NW_ADD_TOPIC_ERROR);
    }
    exit();
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
 *		To create this table, the program compute the total number of reads per author and displays the most readed author and the number of views
 *   b) Best rated authors
 *      To created this table's content, the program compute the rating's average of each author and create a table
 *   c) Biggest contributors
 *      The goal of this table is to know who is creating the biggest number of articles.
 */
function Stats()
{
    global $xoopsModule, $xoopsConfig;
    xoops_cp_header();
    $myts =& MyTextSanitizer::getInstance();
	if (file_exists(NW_MODULE_PATH . '/language/'.$xoopsConfig['language'].'/main.php')) {
		include_once NW_MODULE_PATH . '/language/'.$xoopsConfig['language'].'/main.php';
	} else {
		include_once NW_MODULE_PATH . '/language/english/main.php';
	}
    adminmenu(6, _AM_NW_STATS);
    $news = new nw_NewsStory();
    $stats = array();
    $stats=$news->GetStats(nw_getmoduleoption('storycountadmin', NW_MODULE_DIR_NAME));
	$totals=array(0,0,0,0,0);
    printf("<h2>%s</h2>\n",_AM_NW_STATS);

    // First part of the stats, everything about topics
	$storiespertopic=$stats['storiespertopic'];
	$readspertopic=$stats['readspertopic'];
	$filespertopic=$stats['filespertopic'];
	$expiredpertopic=$stats['expiredpertopic'];
	$authorspertopic=$stats['authorspertopic'];
	$class='';

	echo "<div style='text-align: center;'><b>" . _AM_NW_STATS0 . "</b><br />\n";
	echo "<table border='0' width='100%'><tr class='bg3'><td align='center'>"._AM_NW_TOPIC."</td><td align='center'>" . _MA_NW_ARTICLES . "</td><td>" . _MA_NW_VIEWS . "</td><td>" . _AM_NW_UPLOAD_ATTACHFILE . "</td><td>" . _AM_NW_EXPARTS ."</td><td>" ._AM_NW_STATS1 ."</td></tr>";
	foreach ( $storiespertopic as $topicid => $data ) {
		$url=NW_MODULE_URL . '/index.php?topic_id=' . $topicid;
		$views=0;
		if(array_key_exists($topicid,$readspertopic)) {
			$views=$readspertopic[$topicid];
		}
		$attachedfiles=0;
		if(array_key_exists($topicid,$filespertopic)) {
			$attachedfiles=$filespertopic[$topicid];
		}
		$expired=0;
		if(array_key_exists($topicid,$expiredpertopic)) {
			$expired=$expiredpertopic[$topicid];
		}
		$authors=0;
		if(array_key_exists($topicid,$authorspertopic)) {
			$authors=$authorspertopic[$topicid];
		}
		$articles=$data['cpt'];

        $totals[0]+=$articles;
        $totals[1]+=$views;
        $totals[2]+=$attachedfiles;
        $totals[3]+=$expired;
        $class = ($class == 'even') ? 'odd' : 'even';
		printf("<tr class='".$class."'><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='right'>%u</td><td align='right'>%u</td><td align='right'>%u</td><td align='right'>%u</td><td align='right'>%u</td></tr>\n",$url,$myts->displayTarea($data['topic_title']),$articles,$views,$attachedfiles,$expired,$authors);
	}
	$class = ($class == 'even') ? 'odd' : 'even';
	printf("<tr class='".$class."'><td align='center'><b>%s</b></td><td align='right'><b>%u</b></td><td align='right'><b>%u</b></td><td align='right'><b>%u</b></td><td align='right'><b>%u</b></td><td>&nbsp;</td>\n",_AM_NW_STATS2,$totals[0],$totals[1],$totals[2],$totals[3]);
	echo '</table></div><br /><br /><br />';

	// Second part of the stats, everything about stories
	// a) Most readed articles
	$mostreadnews=$stats['mostreadnews'];
	echo "<div style='text-align: center;'><b>" . _AM_NW_STATS3 . '</b><br /><br />' . _AM_NW_STATS4 . "<br />\n";
	echo "<table border='0' width='100%'><tr class='bg3'><td align='center'>"._AM_NW_TOPIC."</td><td align='center'>" . _AM_NW_TITLE . "</td><td>" . _AM_NW_POSTER . "</td><td>" . _MA_NW_VIEWS . "</td></tr>\n";
	foreach ( $mostreadnews as $storyid => $data ) {
		$url1=NW_MODULE_URL . '/index.php?topic_id=' . $data['topicid'];
		$url2=NW_MODULE_URL . '/article.php?storyid=' . $storyid;
		$url3=XOOPS_URL . '/userinfo.php?uid=' . $data['uid'];
		$class = ($class == 'even') ? 'odd' : 'even';
		printf("<tr class='".$class."'><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='left'><a href='%s' target='_blank'>%s</a></td><td><a href='%s' target='_blank'>%s</a></td><td align='right'>%u</td></tr>\n",$url1,$myts->displayTarea($data['topic_title']),$url2,$myts->displayTarea($data['title']),$url3,$myts->htmlSpecialChars($news->uname($data['uid'])),$data['counter']);
	}
	echo '</table>';

	// b) Less readed articles
	$lessreadnews=$stats['lessreadnews'];
	echo '<br /><br />'._AM_NW_STATS5;
	echo "<table border='0' width='100%'><tr class='bg3'><td align='center'>"._AM_NW_TOPIC."</td><td align='center'>" . _AM_NW_TITLE . "</td><td>" . _AM_NW_POSTER . "</td><td>" . _MA_NW_VIEWS . "</td></tr>\n";
	foreach ( $lessreadnews as $storyid => $data ) {
		$url1=NW_MODULE_URL . '/index.php?topic_id=' . $data['topicid'];
		$url2=NW_MODULE_URL . '/article.php?storyid=' . $storyid;
		$url3=XOOPS_URL . '/userinfo.php?uid=' . $data['uid'];
		$class = ($class == 'even') ? 'odd' : 'even';
		printf("<tr class='".$class."'><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='left'><a href='%s' target='_blank'>%s</a></td><td><a href='%s' target='_blank'>%s</a></td><td align='right'>%u</td></tr>\n",$url1,$myts->displayTarea($data['topic_title']),$url2,$myts->displayTarea($data['title']),$url3,$myts->htmlSpecialChars($news->uname($data['uid'])),$data['counter']);
	}
	echo '</table>';

	// c) Best rated articles (this is an average)
	$bestratednews=$stats['besratednw'];
	echo '<br /><br />'._AM_NW_STATS6;
	echo "<table border='0' width='100%'><tr class='bg3'><td align='center'>"._AM_NW_TOPIC."</td><td align='center'>" . _AM_NW_TITLE . "</td><td>" . _AM_NW_POSTER . "</td><td>" . _MA_NW_RATING . "</td></tr>\n";
	foreach ( $bestratednews as $storyid => $data ) {
		$url1=NW_MODULE_URL . '/index.php?topic_id=' . $data['topicid'];
		$url2=NW_MODULE_URL . '/article.php?storyid=' . $storyid;
		$url3=XOOPS_URL . '/userinfo.php?uid=' . $data['uid'];
		$class = ($class == 'even') ? 'odd' : 'even';
		printf("<tr class='".$class."'><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='left'><a href='%s' target='_blank'>%s</a></td><td><a href='%s' target='_blank'>%s</a></td><td align='right'>%s</td></tr>\n",$url1,$myts->displayTarea($data['topic_title']),$url2,$myts->displayTarea($data['title']),$url3,$myts->htmlSpecialChars($news->uname($data['uid'])),number_format($data['rating'], 2));
	}
	echo '</table></div><br /><br /><br />';


	// Last part of the stats, everything about authors
	// a) Most readed authors
	$mostreadedauthors=$stats['mostreadedauthors'];
	echo "<div style='text-align: center;'><b>" . _AM_NW_STATS10 . '</b><br /><br />' . _AM_NW_STATS7 . "<br />\n";
	echo "<table border='0' width='100%'><tr class='bg3'><td>"._AM_NW_POSTER.'</td><td>' . _MA_NW_VIEWS . "</td></tr>\n";
	foreach ( $mostreadedauthors as $uid => $reads) {
		$url=XOOPS_URL . '/userinfo.php?uid=' . $uid;
		$class = ($class == 'even') ? 'odd' : 'even';
		printf("<tr class='".$class."'><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='right'>%u</td></tr>\n",$url,$myts->htmlSpecialChars($news->uname($uid)),$reads);
	}
	echo '</table>';

    // b) Best rated authors
	$bestratedauthors=$stats['bestratedauthors'];
	echo '<br /><br />'._AM_NW_STATS8;
	echo "<table border='0' width='100%'><tr class='bg3'><td>"._AM_NW_POSTER."</td><td>" . _MA_NW_RATING . "</td></tr>\n";
	foreach ( $bestratedauthors as $uid => $rating) {
		$url=XOOPS_URL . '/userinfo.php?uid=' . $uid;
		$class = ($class == 'even') ? 'odd' : 'even';
		printf("<tr class='".$class."'><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='right'>%u</td></tr>\n",$url,$myts->htmlSpecialChars($news->uname($uid)),$rating);
	}
	echo '</table>';

	// c) Biggest contributors
	$biggestcontributors=$stats['biggestcontributors'];
	echo '<br /><br />'._AM_NW_STATS9;
	echo "<table border='0' width='100%'><tr class='bg3'><td>"._AM_NW_POSTER."</td><td>" . _AM_NW_STATS11 . "</td></tr>\n";
	foreach ( $biggestcontributors as $uid => $count) {
		$url=XOOPS_URL . '/userinfo.php?uid=' . $uid;
		$class = ($class == 'even') ? 'odd' : 'even';
		printf("<tr class='".$class."'><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='right'>%u</td></tr>\n",$url,$myts->htmlSpecialChars($news->uname($uid)),$count);
	}
	echo '</table></div><br />';
}


/**
 * Metagen
 *
 * Metagen is a system that can help you to have your page best indexed by search engines.
 * Except if you type meta keywords and meta descriptions yourself, the module will automatically create them.
 * From here you can also manage some other options like the maximum number of meta keywords to create and
 * the keywords apparition's order.
 */
function Metagen()
{
	include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
    global $xoopsModule, $xoopsConfig, $xoopsModuleConfig, $cfg;
    xoops_cp_header();
    $myts =& MyTextSanitizer::getInstance();
	if (file_exists(NW_MODULE_PATH . '/language/'.$xoopsConfig['language'].'/main.php')) {
		include_once NW_MODULE_PATH . '/language/'.$xoopsConfig['language'].'/main.php';
	} else {
		include_once NW_MODULE_PATH . '/language/english/main.php';
	}
    adminmenu(7, _AM_NW_METAGEN);
    echo "<h2>"._AM_NW_METAGEN."</h2>";
	echo _AM_NW_METAGEN_DESC."<br /><br />";

	// Metagen Options
	$registry = new nw_registryfile('nw_metagen_options.txt');
	$content = '';
	$content = $registry->getfile();
	if(xoops_trim($content) != '') {
		list($keywordscount, $keywordsorder) = explode(',',$content);
	} else {
		$keywordscount = $cfg['meta_keywords_count'];
		$keywordsorder = $cfg['meta_keywords_order'];
	}
	$sform = new XoopsThemeForm(_OPTIONS, 'metagenoptions', NW_MODULE_URL . '/admin/index.php', 'post');
	$sform->addElement(new XoopsFormHidden('op', 'metagenoptions'), false);
	$sform->addElement(new XoopsFormText(_AM_NW_META_KEYWORDS_CNT, 'keywordscount', 4, 6, $keywordscount), true);
	$keywordsorder=new XoopsFormRadio(_AM_NW_META_KEYWORDS_ORDER, 'keywordsorder', $keywordsorder);
	$keywordsorder->addOption(0,_AM_NW_META_KEYWORDS_INTEXT);
	$keywordsorder->addOption(1,_AM_NW_META_KEYWORDS_FREQ1);
	$keywordsorder->addOption(2,_AM_NW_META_KEYWORDS_FREQ2);
	$sform->addElement($keywordsorder, false);
	$button_tray = new XoopsFormElementTray('' ,'');
	$submit_btn = new XoopsFormButton('', 'post', _AM_NW_MODIFY, 'submit');
	$button_tray->addElement($submit_btn);
	$sform->addElement($button_tray);
	$sform->display();

	// Blacklist
	$sform = new XoopsThemeForm(_AM_NW_BLACKLIST, 'metagenblacklist', NW_MODULE_URL . '/admin/index.php', 'post');
	$sform->addElement(new XoopsFormHidden('op', 'metagenblacklist'), false);

	// Remove words
	$remove_tray = new XoopsFormElementTray(_AM_NW_BLACKLIST);
	$remove_tray->setDescription(_AM_NW_BLACKLIST_DESC);
	$blacklist=new XoopsFormSelect('', 'blacklist','',5,true);
	$words = array();

	$metablack = new nw_blacklist();
	$words = $metablack->getAllKeywords();
	if(is_array($words) && count($words)>0) {
		foreach ($words as $key => $value) {
			$blacklist->addOption($key,$value);
		}
	}

	$blacklist->setDescription(_AM_NW_BLACKLIST_DESC);
	$remove_tray->addElement($blacklist,false);
	$remove_btn = new XoopsFormButton('', 'go', _AM_NW_DELETE, 'submit');
	$remove_tray->addElement($remove_btn,false);
	$sform->addElement($remove_tray);

	// Add some words
	$add_tray = new XoopsFormElementTray(_AM_NW_BLACKLIST_ADD);
	$add_tray->setDescription(_AM_NW_BLACKLIST_ADD_DSC);
	$add_field = new XoopsFormTextArea('', 'keywords', '', 5, 70);
	$add_tray->addElement($add_field,false);
	$add_btn = new XoopsFormButton('', 'go', _AM_NW_ADD, 'submit');
	$add_tray->addElement($add_btn,false);
	$sform->addElement($add_tray);
	$sform->display();
}

/**
 * Save metagen's blacklist words
 */
function MetagenBlackList()
{
	$blacklist = new nw_blacklist();
	$words = $blacklist->getAllKeywords();

	if(isset($_POST['go']) && $_POST['go'] == _AM_NW_DELETE) {
		foreach($_POST['blacklist'] as $black_id) {
			$blacklist->delete($black_id);
		}
		$blacklist->store();
	} else {
		if(isset($_POST['go']) && $_POST['go']==_AM_NW_ADD) {
			$p_keywords = $_POST['keywords'];
			$keywords = explode("\n",$p_keywords);
			foreach($keywords as $keyword) {
				if(xoops_trim($keyword)!='') {
					$blacklist->addkeywords(xoops_trim($keyword));
				}
			}
			$blacklist->store();
		}
	}
	redirect_header( 'index.php?op=metagen', 0, _AM_NW_DBUPDATED);
}


/**
 * Save Metagen Options
 */
function MetagenSaveOptions()
{
	$registry = new nw_registryfile('nw_metagen_options.txt');
	$registry->savefile(intval($_POST['keywordscount']).','.intval($_POST['keywordsorder']));
	redirect_header('index.php?op=metagen', 0, _AM_NW_DBUPDATED);
}


/**
 * Cloner - DNPROSSI
 */
function NewsCloner()
{ 
	global $xoopsDB, $xoopsConfig, $xoopsModule, $myts;
	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
    xoops_cp_header();
    adminmenu(8, _AM_NW_CLONER);
    
    $clone_modulename = '';
   
    $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
    
	$result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('news_clonerdata'));
	$ix = 0;
	$iy = 0;
	while ( $clone = $xoopsDB->fetchArray($result) ) {
		//DNPROSSI - Control if clone dir exists
		if ( is_dir(XOOPS_ROOT_PATH . "/modules/" . $clone['clone_dir']) ) {
			$clone_arr[$ix] = $clone;
			$ix++;
		} else {
		    $nonclone_arr[$iy] = $clone;
		    $iy++;
		}
	}
	// If cloned dir does not exists because deleted remove from dtb
	if ( isset($nonclone_arr) ) {	
		for ($iy = 0; $iy < count($nonclone_arr); $iy++) { 
			$result = $xoopsDB->queryF("DELETE FROM " . $xoopsDB->prefix('news_clonerdata') . " WHERE clone_dir = '" . $nonclone_arr[$iy]['clone_dir'] . "' ;"); 
		}
	}
			
	$totalclones = count($clone_arr);
	$class='';

    echo '<h2>' . _AM_NW_CLONER . '</h2>';
	nw_collapsableBar('NewsCloner', 'topNewsCloner');
	echo "<img onclick=\"toggle('toptable'); toggleIcon('toptableicon');\" id='topNewsCloner' name='topNewsCloner' src='" . NW_MODULE_URL . "/images/close12.gif' alt='' /></a>&nbsp;" . _AM_NW_CLONER_CLONES . ' (' . $totalclones . ')'."</h4>";
	echo "<div id='NewsCloner'>";
	echo '<br />';
    echo "<div style='text-align: center;'>";
    echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr class='bg3'><td align='center'>" . _AM_NW_CLONER_NAME . "</td><td align='center'>" . _AM_NW_CLONER_DIRFOL . "</td><td align='center'>" . _AM_NW_SUBPREFIX . "</td><td align='center'>" . _AM_NW_CLONER_VERSION . "</td><td align='center'>" . _AM_NW_ACTION . "</td><td align='center'>" . _AM_NW_CLONER_ACTION_INSTALL . "</td></tr>";
	if(is_array($clone_arr) && $totalclones) {
		$cpt=1;
		$tmpcpt=$start;
		$ok=true;
		$output='';
		while($ok) {
			if($tmpcpt < $totalclones) {				
				//DNPROSSI - Upgrade if clone version is different from original news version
				//DNPROSSI - Install if cloned 
				if ( $clone_arr[$tmpcpt]['clone_dir'] != $clone_arr[0]['clone_dir'] ) {
					if ( $clone_arr[$tmpcpt]['clone_version'] != $clone_arr[0]['clone_version'] ) 
					{						
						$linkupgrade = NW_MODULE_URL . '/admin/index.php?op=cloneupgrade&amp;clone_id=' . $clone_arr[$tmpcpt]['clone_id'];
						$action = sprintf("<a href='%s'>%s</a>", $linkupgrade, _AM_NW_UPGRADE);	
						if ( $clone_arr[$tmpcpt]['clone_installed'] == 1 ) 
						{
							$linkupgrade = XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&op=uninstall&module=' . $clone_arr[$tmpcpt]['clone_dir'];
							$installaction = sprintf("<a href='%s'>%s</a>", $linkupgrade, _AM_NW_CLONER_UNINSTALL);					
						} else {
							$linkupgrade = XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&op=install&module=' . $clone_arr[$tmpcpt]['clone_dir'];
							$linkdelete = NW_MODULE_URL . '/admin/index.php?op=clonedelete&amp;clone_id=' . $clone_arr[$tmpcpt]['clone_id'];
							$installaction = sprintf("<a href='%s'>%s</a> - <a href='%s'>%s</a>", $linkupgrade, _AM_NW_CLONER_INSTALL, $linkdelete, _AM_NW_DELETE);
						}
					} else {
						$linkforce = NW_MODULE_URL . '/admin/index.php?op=cloneupgrade&amp;clone_id=' . $clone_arr[$tmpcpt]['clone_id'];
						$action=sprintf( _AM_NW_CLONER_CLONEUPGRADED . " - <a href='%s'>%s</a>", $linkforce, _AM_NW_CLONER_UPGRADEFORCE);
						if ( $clone_arr[$tmpcpt]['clone_installed'] == 1 ) 
						{
							$linkupgrade = XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&op=uninstall&module=' . $clone_arr[$tmpcpt]['clone_dir'];
							$installaction = sprintf("<a href='%s'>%s</a>", $linkupgrade, _AM_NW_CLONER_UNINSTALL);
						} else {
							$linkupgrade = XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&op=install&module=' . $clone_arr[$tmpcpt]['clone_dir'];
							$linkdelete = NW_MODULE_URL . '/admin/index.php?op=clonedelete&amp;clone_id=' . $clone_arr[$tmpcpt]['clone_id'];
							$installaction = sprintf("<a href='%s'>%s</a> - <a href='%s'>%s</a>", $linkupgrade, _AM_NW_CLONER_INSTALL, $linkdelete, _AM_NW_DELETE);
						}	
					}		
				} else {
				    $linkupgrade = XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&op=update&module=' . $clone_arr[$tmpcpt]['clone_dir'];
				    $action = sprintf("<a href='%s'>%s</a>", $linkupgrade, _AM_NW_CLONER_UPDATE);
				    $installaction = '';
				}
				$class = ($class == 'even') ? 'odd' : 'even';
				$output  = $output . "<tr class='" . $class . "'><td align='center'>" . $clone_arr[$tmpcpt]['clone_name'] . "</td><td align='center'>" . $clone_arr[$tmpcpt]['clone_dir'] . "</td><td align='center'>" . $clone_arr[$tmpcpt]['clone_subprefix'] . "</td><td align='center'>" . round($clone_arr[$tmpcpt]['clone_version']  / 100, 2) . "</td><td>" . $action . "</td><td>" . $installaction . "</td></tr>";		
			} else {
				$ok=false;
			}
			if($cpt>=nw_getmoduleoption('storycountadmin', NW_MODULE_DIR_NAME)) {
				$ok=false;
			}
			$tmpcpt++;
			$cpt++;
		}
		echo $output;
	}
	$pagenav = new XoopsPageNav( $totalclones, nw_getmoduleoption('storycountadmin', NW_MODULE_DIR_NAME), $start, 'start', 'op=clonemanager');
	echo "</table><div align='right'>".$pagenav->renderNav().'</div><br />';
	echo "</div></div><br />\n";

	$clone_id = isset($_GET['clone_id']) ? intval($_GET['clone_id']) : 0;
	if($clone_id>0) {
		$xtmod = new nw_NewsTopic($clone_id);
		$clone_name=$xtmod->clone_name('E');
		$clone_dir=$xtmod->clone_dir('E');
		$clone_version=$xtmod->clone_version('E');
		$op='modClone';
		$btnlabel=_AM_NW_MODIFY;
		$parent=$xtmod->topic_pid();
		$formlabel=_AM_NW_MODIFYTOPIC;
		$oldnewsimport=$xtmod->menu();
		$topic_frontpage=$xtmod->topic_frontpage();
		$topic_color=$xtmod->topic_color();
		unset($xtmod);
	} else {
		$clone_name='';
		$clone_frontpage=1;
		$clone_dir='';
		$op='addTopic';
		$btnlabel=_AM_NW_ADD;
		$parent=-1;
		$oldnewsimport=0;
		$clone_version='';
		$formlabel=_AM_NW_ADD_TOPIC;
	}
          
    //Draw Form
    $sform = new XoopsThemeForm(_AM_NW_CLONER_ADD, "clonerform", NW_MODULE_URL . "/admin/index.php", "post");
    
    $filedir_tray = new XoopsFormElementTray(_AM_NW_CLONER_NEWNAME, "");
    $label = sprintf(_AM_NW_CLONER_NEWNAMEDESC, $xoopsModule->name());
    $filedir_tray->addElement(new XoopsFormLabel($label), false);
    $filedir_tray->addElement(new XoopsFormText(_AM_NW_CLONER_NEWNAMELABEL, "clone_modulename", 50, 255, $clone_modulename), true);
	$sform->addElement($filedir_tray);
	
	$sform->addElement(new XoopsFormHidden("op", "clonerapply"), false);
	
	$button_tray = new XoopsFormElementTray("" ,"");
	$submit_btn = new XoopsFormButton("", "post", _SUBMIT, "submit");
	$button_tray->addElement($submit_btn);
	$sform->addElement($button_tray);
	$sform->display();
	
	
	//recalc original subprefix
	$sub = nw_remove_numbers(NW_SUBPREFIX);
	$result2 = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix($sub.'_stories'));
	$count = $xoopsDB->getRowsNum($result2);
	
	$tmpmodule_handler =& xoops_gethandler('module');

}

/**
 * Cloner Apply - DNPROSSI
 */
function NewsClonerApply()
{ 
	include_once "cloner.php";
	global $xoopsDB, $xoopsConfig, $xoopsModule;
	if ( !empty($_POST['clone_modulename']) ) {
		$module_version = $xoopsModule->version();
		$old_dirname = $xoopsModule->dirname();
		$old_modulename = $xoopsModule->name();
		$old_subprefix = NW_SUBPREFIX;
        
        $new_modulename = $_POST['clone_modulename'];
        
	    $new_dirname = strtolower(str_replace(' ', '', $new_modulename));
	 	$new_modulename = ucwords(strtolower($new_modulename));
	 		 	
	 	//Select last id for new sub-prefix.
	 	$result = $xoopsDB->query("SHOW TABLE STATUS LIKE '" . $xoopsDB->prefix("news_clonerdata") . "'");
        $row =  $xoopsDB->fetchArray($result);
        $Auto_increment = $row['Auto_increment'];
	 	
	 	$new_subprefix = 'nw' . strval($Auto_increment);
	 	//trigger_error($result. ' ---- ' .$count. ' ---- ' .$new_subprefix , E_USER_WARNING);
             
		$patterns = array(	
			$old_dirname => $new_dirname,
			'$modversion["original"] = 1;' => '$modversion["original"] = 0;',
			'$modversion["name"] = "' . 'x' . 'News' . '"' =>  '$modversion["name"] = "' . $new_modulename . '"',
			$old_subprefix => strtolower($new_subprefix),
			strtoupper($old_subprefix) => strtoupper($new_subprefix)	
		);
               
        $patKeys = array_keys($patterns);
		$patValues = array_values($patterns);
        
        $newPath = str_replace($patKeys[0], $patValues[0], NW_MODULE_PATH);
        $oldlogo = $newPath . "/" . $old_dirname . "_logo.png"; 
        $newlogo = $newPath . "/" . $new_dirname . "_logo.png";  
        
        if ( !is_dir($newPath) ) { //&& !$old_subprefix == $new_subprefix ) {
        	nw_cloneFileFolder(NW_MODULE_PATH, $patterns);    	
        	//rename logo.png
	        @rename( $oldlogo, $newlogo );
			//trigger_error($oldlogo. ' ---- ' .$newlogo , E_USER_WARNING);
			
			$path_array[0] = $newPath . '/templates/';
			$path_array[1] = $newPath . '/templates/blocks/';
			
			// check all files in dir, and process them
		    nw_clonefilename($path_array, $old_subprefix, $new_subprefix);
		    
		    //Add cloned module to cloner dtb
			$result = $xoopsDB->query("INSERT INTO " . $xoopsDB->prefix('news_clonerdata') . 
						" (clone_name, clone_dir, clone_version, clone_subprefix, clone_installed)" .
						" VALUES ('" . $new_modulename . "', '" . $new_dirname . "', '" . $module_version . "', '" . $new_subprefix . "', 0);");	
			
			$label = sprintf(_AM_NW_CLONER_CREATED, $new_modulename);
			redirect_header('index.php?op=cloner', 5, $label);
		} else {
			$label = sprintf(_AM_NW_CLONER_DIREXISTS, $new_dirname);
			redirect_header('index.php?op=cloner', 5, $label);
		}
    }
}

/**
 * Clone Upgrade - DNPROSSI
 */
function CloneUpgrade()
{
	include_once "cloner.php";
	global $xoopsDB, $xoopsConfig, $xoopsModule;
	
	if ( !isset($_GET['clone_id']) ) { 
		//trigger_error("Not set", E_USER_WARNING); 
		redirect_header('index.php?op=cloner', 5, _AM_NW_CLONER_NOMODULEID);
	} else {
		$cloneid = $_GET['clone_id'];
	
		$result = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix('news_clonerdata') . " WHERE clone_id = " . $cloneid );
		$ix = 0;
		while ( $clone = $xoopsDB->fetchArray($result) ) {
			$clone_arr[$ix] = $clone;
			$ix++;
		}	
		
		$org_modulename = $xoopsModule->name();
		$org_dirname = $xoopsModule->dirname();
		$org_version = $xoopsModule->version();
		$org_subprefix = NW_SUBPREFIX;
	
		$upg_modulename = $clone_arr[0]['clone_name'];
		$upg_dirname = $clone_arr[0]['clone_dir'];
		$upg_version = $clone_arr[0]['clone_version'];
		$upg_subprefix = $clone_arr[0]['clone_subprefix'];
	
		$patterns = array(	
			$org_dirname => $upg_dirname,
			'$modversion["original"] = 1;' => '$modversion["original"] = 0;',
			'$modversion["name"] = "' . 'x' . 'News' . '"' =>  '$modversion["name"] = "' . $upg_modulename . '"',
			$org_subprefix => strtolower($upg_subprefix),
			strtoupper($org_subprefix) => strtoupper($upg_subprefix),	
		);
	 
		$patKeys = array_keys($patterns);
		$patValues = array_values($patterns);
        
        $newPath = str_replace($patKeys[0], $patValues[0], NW_MODULE_PATH);
        $oldlogo = $newPath . "/" . $org_dirname . "_logo.png"; 
        $newlogo = $newPath . "/" . $upg_dirname . "_logo.png";  
        
        nw_cloneFileFolder(NW_MODULE_PATH, $patterns);    	
        //rename logo.png
	    @rename( $oldlogo, $newlogo );
			
		$path_array[0] = $newPath . '/templates/';
		$path_array[1] = $newPath . '/templates/blocks/';
		
		nw_deleteclonefile($path_array, $upg_subprefix); 
			
		// check all files in dir, and process them
		nw_clonefilename($path_array, $org_subprefix, $upg_subprefix);
		    	
		//Update module dtb
		$xoopsDB->queryF("UPDATE " . $xoopsDB->prefix('news_clonerdata') . " SET clone_version  = " . $org_version . " WHERE clone_id = " . $cloneid);
			
		$label = sprintf(_AM_NW_CLONER_UPRADED, $upg_modulename);
		redirect_header('index.php?op=cloner', 5, $label);
	}
}

/**
 * Delete Clone - DNPROSSI - 1.68 RC1
 */
function CloneDelete()
{
	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
    xoops_cp_header();
	global $xoopsDB, $xoopsConfig, $xoopsModule;
	
	if ( !isset($_GET['clone_id']) ) { 
		
		redirect_header('index.php?op=cloner', 5, _AM_NW_CLONER_CLONEID);
	} else {
		$cloneid = $_GET['clone_id'];
	
		$result = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix('news_clonerdata') . " WHERE clone_id = " . $cloneid );
		$ix = 0;
		while ( $clone = $xoopsDB->fetchArray($result) ) {
			$clone_arr[$ix] = $clone;
			$ix++;
		}	
		
		$module_dirname = $clone_arr[0]['clone_dir'];
	
		echo "<div id='NewsCloner' style='text-align: center;'>";
		echo "<h2>" . _AM_NW_CLONER_CLONEDELETION . "</h2>";
		echo "</div>";
		//echo "<div style='text-align: center;'>";
		$message = sprintf(_AM_NW_CLONER_SUREDELETE, $module_dirname);
		xoops_confirm(array('op' => 'clonedeleteapply', 'clone_id' => $cloneid, 'ok' => 1, 'module_name' => $module_dirname), 'index.php', $message);
	}
}

/**
 * Apply Delete Clone - DNPROSSI - 1.68 RC1
 */
function CloneDeleteApply()
{
	include_once "cloner.php";
	global $xoopsDB, $xoopsConfig, $xoopsModule;
	//trigger_error("Not set", E_USER_WARNING); 
	if ( !isset($_POST['clone_id']) ) { 
		redirect_header('index.php?op=cloner', 5, _AM_NW_CLONER_CLONEID);
	} else {

		$del_dirname = $_POST['module_name'];
        
		$delPath1 = XOOPS_ROOT_PATH . "/modules/" . $del_dirname;
		$delPath2 = XOOPS_ROOT_PATH . "/uploads/" . $del_dirname;

		if ( file_exists($delPath2) && is_dir($delPath2) ) 
		{
			if ( nw_removewholeclone($delPath1) == TRUE && nw_removewholeclone($delPath2) == TRUE ) 
			{ 			
				$label = sprintf(_AM_NW_CLONER_CLONEDELETED, $del_dirname);
				redirect_header('index.php?op=cloner', 5, $label);
			} else {
				$label = sprintf(_AM_NW_CLONER_CLONEDELETEERR, $del_dirname);
				redirect_header('index.php?op=cloner', 5, $label);
			}
		}			
		elseif ( nw_removewholeclone($delPath1) == TRUE ) 
		{				
			$label = sprintf(_AM_NW_CLONER_CLONEDELETED, $del_dirname);
			redirect_header('index.php?op=cloner', 5, $label);
		} else {
			$label = sprintf(_AM_NW_CLONER_CLONEDELETEERR, $del_dirname);
		    redirect_header('index.php?op=cloner', 5, $label);
		}
	}	
}

// **********************************************************************************************************************************************
// **** Main
// **********************************************************************************************************************************************
$op = 'default';
if(isset($_POST['op'])) {
 $op=$_POST['op'];
} elseif(isset($_GET['op'])) {
	$op=$_GET['op'];
}

switch ($op) {
	case 'deletefile':
		xoops_cp_header();
		if($_GET['type']=='newsletter')	{
			$newsfile=XOOPS_ROOT_PATH.'/uploads/newsletter.txt';
			if(unlink($newsfile)) {
				redirect_header('index.php', 2, _AM_NW_DELETED_OK);
			} else {
				redirect_header('index.php', 2, _AM_NW_DELETED_PB);
			}
		} else {
			if($_GET['type']=='xml') {
				$xmlfile=XOOPS_ROOT_PATH.'/uploads/nw_stories.xml';
				if(unlink($xmlfile)) {
					redirect_header( 'index.php', 2, _AM_NW_DELETED_OK );
				} else {
					redirect_header( 'index.php', 2, _AM_NW_DELETED_PB );
				}
			}
		}
		break;

    case 'newarticle':
        xoops_cp_header();
        adminmenu(1, _AM_NW_CONFIG);
        echo '<h2>' . _AM_NW_CONFIG . '</h2>';
        include_once XOOPS_ROOT_PATH . '/class/module.textsanitizer.php';
        newSubmissions();
        autoStories();
        lastStories();
        expStories();
        echo '<br />';
        echo '<h4>' . _AM_NW_POSTNEWARTICLE . '</h4>';
        $type = 'admin';
        $title = '';
        $topicdisplay = 0;
        $topicalign = 'R';
        $ihome = 0;
        $hometext = '';
        $bodytext = '';
        $notifypub = 1;
        $nohtml = 0;
        $approve = 0;
        $nosmiley = 0;
        $dobr = 1;
	    $autodate = '';
	    $expired = '';
	    $topicid = 0;
	    $returnside=1;
	    $published = 0;
	    $description='';
	    $keywords='';
        if (file_exists(NW_MODULE_PATH . '/language/'.$xoopsConfig['language'].'/main.php')) {
            include_once NW_MODULE_PATH . '/language/'.$xoopsConfig['language'].'/main.php';
        } else {
            include_once NW_MODULE_PATH . '/language/english/main.php';
        }

		if($xoopsModuleConfig['autoapprove'] == 1) {
			$approve=1;
		}
        $approveprivilege = 1;
        include_once NW_MODULE_PATH . '/include/storyform.original.php';
        break;

    case 'delete':
       	$storyid=0;
       	if(isset($_GET['storyid'])) {
			$storyid=intval($_GET['storyid']);
       	} elseif(isset($_POST['storyid'])) {
   			$storyid=intval($_POST['storyid']);
       	}

        if (!empty($_POST['ok'])) {
            if (empty($storyid)) {
                redirect_header( 'index.php?op=newarticle', 2, _AM_NW_EMPTYNODELETE );
                exit();
            }
            $story = new nw_NewsStory($storyid);
            $story->delete();
			$sfiles = new nw_sFiles();
			$filesarr=Array();
			$filesarr=$sfiles->getAllbyStory($storyid);
			if(count($filesarr)>0) {
				foreach ($filesarr as $onefile) {
					$onefile->delete();
				}
			}
            xoops_comment_delete($xoopsModule->getVar('mid'),$storyid);
            xoops_notification_deletebyitem($xoopsModule->getVar('mid'), 'story', $storyid);
            nw_updateCache();
            redirect_header( 'index.php?op=newarticle', 1, _AM_NW_DBUPDATED );
            exit();
        } else {
        	$story = new nw_NewsStory($storyid);
            xoops_cp_header();
            echo '<h4>' . _AM_NW_CONFIG . '</h4>';
            xoops_confirm(array('op' => 'delete', 'storyid' => $storyid, 'ok' => 1), 'index.php', _AM_NW_RUSUREDEL .'<br />' . $story->title());
        }
        break;

    case 'topicsmanager':
        topicsmanager();
        break;

    case 'addTopic':
        addTopic();
        break;

    case 'delTopic':
        delTopic();
        break;

    case 'modTopicS':
        modTopicS();
        break;

    case 'edit':
		if (file_exists(NW_MODULE_PATH . '/language/'.$xoopsConfig['language'].'/main.php')) {
			include_once NW_MODULE_PATH . '/language/'.$xoopsConfig['language'].'/main.php';
		} else {
			include_once NW_MODULE_PATH . '/language/english/main.php';
		}
		include_once NW_MODULE_PATH . '/submit.php';
		break;

    case 'prune':
    	PruneManager();
    	break;

    case 'confirmbeforetoprune':
    	ConfirmBeforeToPrune();
    	break;

    case 'prunenews';
    	PruneNews();
    	break;

    case 'export':
    	NewsExport();
    	break;

    case 'launchexport':
    	LaunchExport();
    	break;

    case 'configurenewsletter':
    	Newsletter();
    	break;

    case 'launchnewsletter':
    	LaunchNewsletter();
    	break;

    case 'stats':
    	Stats();
    	break;
    	
	case 'cloner':
    	NewsCloner();
    	break;
    	
	case 'clonerapply':
    	NewsClonerApply();
    	break;
    	
    case 'cloneupgrade':
    	CloneUpgrade();
    	break;

    case 'clonedelete':
		CloneDelete();
		break;
		
	case 'clonedeleteapply':
		CloneDeleteApply();
		break;

	case 'metagen':
		Metagen();
		break;

	case 'metagenoptions':
		MetagenSaveOptions();
		break;

	case 'metagenblacklist':
		MetagenBlackList();
		break;

    case 'verifydb':
    	xoops_cp_header();
    	adminmenu();
		$tbllist = $xoopsDB->prefix('nw_stories').','.$xoopsDB->prefix('nw_topics').','.$xoopsDB->prefix('nw_stories_files').','.$xoopsDB->prefix('nw_stories_votedata');
		$xoopsDB->queryF("OPTIMIZE TABLE ".$tbllist);
		$xoopsDB->queryF("CHECK TABLE ".$tbllist);
		$xoopsDB->queryF("ANALYZE TABLE ".$tbllist);
		redirect_header( 'index.php', 3, _AM_NW_DBUPDATED);
		exit;
    	break;

    case 'default':
    default:
        xoops_cp_header();
        adminmenu(-1);
        if(!nw_TableExists($xoopsDB->prefix('nw_stories_votedata')) || !nw_TableExists($xoopsDB->prefix('nw_stories_files')) ) {
        	echo "<div align='center'>"._AM_NW_PLEASE_UPGRADE.'</div><br/><br />';
        }
        echo '<h4>' . _AM_NW_CONFIG . '</h4>';
        echo"<table width='100%' border='0' cellspacing='1' class='outer'><tr><td width='59%' class=\"odd\" id=\"xo-newsicons\" >";
        echo "<b><a href='index.php?op=topicsmanager'><img  src='" . NW_MODULE_URL . "/images/topics32.png' alt='' /><br/>" . _AM_NW_TOPICSMNGR . "</a></b>";
        echo "<b><a href='index.php?op=newarticle'><img  src='" . NW_MODULE_URL . "/images/newarticle32.png' alt='' /><br/>" . _AM_NW_PEARTICLES . "</a></b>\n";
        echo "<b><a href='groupperms.php'><img  src='" . NW_MODULE_URL . "/images/permissions32.png' alt='' /><br/>" . _AM_NW_GROUPPERM . "</a></b>\n";
        echo "<b><a href='index.php?op=prune'><img  src='" . NW_MODULE_URL . "/images/prune32.png' alt='' /><br/>" . _AM_NW_PRUNENEWS . "</a></b>\n";
        echo "<b><a href='index.php?op=export'><img  src='" . NW_MODULE_URL . "/images/export32.png' alt='' /><br/>" . _AM_NW_EXPORT_NEWS . "</a></b>\n";
        echo "<b><a href='index.php?op=configurenewsletter'><img  src='" . NW_MODULE_URL . "/images/newsletter32.png' alt='' /><br/>" . _AM_NW_NEWSLETTER . "</a></b>\n";
        echo "<b><a href='index.php?op=stats'><img  src='" . NW_MODULE_URL . "/images/stats32.png' alt='' /><br/>" . _AM_NW_STATS . "</a></b>\n";
        echo "<b><a href='index.php?op=metagen'><img  src='" . NW_MODULE_URL . "/images/metagen32.png' alt='' /><br/>" . _AM_NW_METAGEN . "</a></b>\n";         
        echo "<b><a href='" . XOOPS_URL . "/modules/" . "x" . "news" . "/admin/index.php?op=cloner'><img  src='" . NW_MODULE_URL . "/images/cloner32.png' alt='' /><br/>" . _AM_NW_CLONER . "</a></b>\n";
        echo "<b><a href='" . XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule -> getVar( 'mid' ) . "'><img  src='" . NW_MODULE_URL . "/images/prefs32.png' alt='' /><br/>" . _AM_NW_GENERALSET . "</a></b>";
        echo "<br /><br />\n";
        echo"</td><td width='50%' class=\"even\" id=\"xo-newsicons\" >";
        echo _AM_NW_DESCRIPTION . "<br />";
        echo "</td></tr></table>";
        break;
}

xoops_cp_footer();
?>
