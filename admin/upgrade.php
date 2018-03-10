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

require_once __DIR__ . '/admin_header.php';
require_once __DIR__ . '/../../../include/cp_header.php';
xoops_cp_header();
require_once XNEWS_MODULE_PATH . '/include/functions.php';

if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
    $errors = 0;

    //DNPROSSI - Upgrade if clone version is different from original's version

    //DNPROSSI - Import data from old news database files
    if (nw_TableExists($xoopsDB->prefix('stories'))) {
        $sql    = sprintf('INSERT INTO ' . $xoopsDB->prefix('nw_stories') . ' SELECT * FROM ' . $xoopsDB->prefix('stories') . ';');
        $result = $xoopsDB->queryF($sql);

        $sql    = sprintf('INSERT INTO ' . $xoopsDB->prefix('nw_stories_files') . ' SELECT * FROM ' . $xoopsDB->prefix('stories_files') . ';');
        $result = $xoopsDB->queryF($sql);

        $sql    = sprintf('INSERT INTO ' . $xoopsDB->prefix('nw_topics') . ' SELECT * FROM ' . $xoopsDB->prefix('topics') . ';');
        $result = $xoopsDB->queryF($sql);

        $sql    = sprintf('INSERT INTO ' . $xoopsDB->prefix('nw_stories_votedata') . ' SELECT * FROM ' . $xoopsDB->prefix('stories_votedata') . ';');
        $result = $xoopsDB->queryF($sql);
    }

    // 1) Create, if it does not exists, the nw_stories_files table
    if (!nw_TableExists($xoopsDB->prefix('nw_stories_files'))) {
        $sql = 'CREATE TABLE ' . $xoopsDB->prefix('nw_stories_files') . " (
              fileid INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
              filerealname VARCHAR(255) NOT NULL DEFAULT '',
              storyid INT(8) UNSIGNED NOT NULL DEFAULT '0',
              date INT(10) NOT NULL DEFAULT '0',
              mimetype VARCHAR(64) NOT NULL DEFAULT '',
              downloadname VARCHAR(255) NOT NULL DEFAULT '',
              counter INT(8) UNSIGNED NOT NULL DEFAULT '0',
              PRIMARY KEY  (fileid),
              KEY storyid (storyid)
            ) ENGINE=MyISAM;";
        if (!$xoopsDB->queryF($sql)) {
            echo '<br>' . _AM_XNEWS_UPGRADEFAILED . ' ' . _AM_XNEWS_UPGRADEFAILED1;
            $errors++;
        }
    }

    // 2) Change the topic title's length, in the nw_topics table
    $sql    = sprintf('ALTER TABLE ' . $xoopsDB->prefix('nw_topics') . ' CHANGE topic_title topic_title VARCHAR( 255 ) NOT NULL;');
    $result = $xoopsDB->queryF($sql);
    if (!$result) {
        echo '<br>' . _AM_XNEWS_UPGRADEFAILED . ' ' . _AM_XNEWS_UPGRADEFAILED2;
        $errors++;
    }

    // 2.1) Add the new fields to the nw_topic table
    if (!nw_FieldExists('menu', $xoopsDB->prefix('nw_topics'))) {
        nw_AddField("menu TINYINT( 1 ) DEFAULT '0' NOT NULL", $xoopsDB->prefix('nw_topics'));
    }
    if (!nw_FieldExists('topic_frontpage', $xoopsDB->prefix('nw_topics'))) {
        nw_AddField("topic_frontpage TINYINT( 1 ) DEFAULT '1' NOT NULL", $xoopsDB->prefix('nw_topics'));
    }
    if (!nw_FieldExists('topic_rssurl', $xoopsDB->prefix('nw_topics'))) {
        nw_AddField('topic_rssurl VARCHAR( 255 ) NOT NULL', $xoopsDB->prefix('nw_topics'));
    }
    if (!nw_FieldExists('topic_description', $xoopsDB->prefix('nw_topics'))) {
        nw_AddField('topic_description TEXT NOT NULL', $xoopsDB->prefix('nw_topics'));
    }
    if (!nw_FieldExists('topic_color', $xoopsDB->prefix('nw_topics'))) {
        nw_AddField("topic_color varchar(6) NOT NULL default '000000'", $xoopsDB->prefix('nw_topics'));
    }
    if (!nw_FieldExists('topic_weight', $xoopsDB->prefix('nw_topics'))) {
        nw_AddField("topic_weight int( 11 ) NOT NULL default '0'", $xoopsDB->prefix('nw_topics'));
    }

    // 3) If it does not exists, create the table nw_stories_votedata
    if (!nw_TableExists($xoopsDB->prefix('nw_stories_votedata'))) {
        $sql = 'CREATE TABLE ' . $xoopsDB->prefix('nw_stories_votedata') . " (
              ratingid INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
              storyid INT(8) UNSIGNED NOT NULL DEFAULT '0',
              ratinguser INT(11) NOT NULL DEFAULT '0',
              rating TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
              ratinghostname VARCHAR(60) NOT NULL DEFAULT '',
              ratingtimestamp INT(10) NOT NULL DEFAULT '0',
              PRIMARY KEY  (ratingid),
              KEY ratinguser (ratinguser),
              KEY ratinghostname (ratinghostname),
              KEY storyid (storyid)
            ) ENGINE=MyISAM;";
        if (!$xoopsDB->queryF($sql)) {
            echo '<br>' . _AM_XNEWS_UPGRADEFAILED . ' ' . _AM_XNEWS_UPGRADEFAILED3;
            $errors++;
        }
    }

    // 4) Create the four new fields for the votes in the nw_stories table
    if (!nw_FieldExists('rating', $xoopsDB->prefix('nw_stories'))) {
        nw_AddField("rating DOUBLE( 6, 4 ) DEFAULT '0.0000' NOT NULL", $xoopsDB->prefix('nw_stories'));
    }
    if (!nw_FieldExists('votes', $xoopsDB->prefix('nw_stories'))) {
        nw_AddField("votes INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL", $xoopsDB->prefix('nw_stories'));
    }
    if (!nw_FieldExists('keywords', $xoopsDB->prefix('nw_stories'))) {
        nw_AddField('keywords VARCHAR(255) NOT NULL', $xoopsDB->prefix('nw_stories'));
    }
    if (!nw_FieldExists('description', $xoopsDB->prefix('nw_stories'))) {
        nw_AddField('description VARCHAR(255) NOT NULL', $xoopsDB->prefix('nw_stories'));
    }
    if (!nw_FieldExists('dobr', $xoopsDB->prefix('nw_stories'))) {
        nw_AddField("dobr TINYINT( 1 ) NOT NULL DEFAULT '1'", $xoopsDB->prefix('nw_stories'));
    }
    if (!nw_FieldExists('tags', $xoopsDB->prefix('nw_stories'))) {
        nw_AddField("tags VARCHAR( 255 ) DEFAULT ''", $xoopsDB->prefix('nw_stories'));
    }
    if (!nw_FieldExists('imagerows', $xoopsDB->prefix('nw_stories'))) {
        nw_AddField("imagerows SMALLINT(4) unsigned NOT NULL default '1'", $xoopsDB->prefix('nw_stories'));
    }
    if (!nw_FieldExists('pdfrows', $xoopsDB->prefix('nw_stories'))) {
        nw_AddField("pdfrows SMALLINT(4) unsigned NOT NULL default '1'", $xoopsDB->prefix('nw_stories'));
    }

    // 5) Add some indexes to the topics table
    $sql    = sprintf('ALTER TABLE ' . $xoopsDB->prefix('nw_topics') . ' ADD INDEX ( `topic_title` );');
    $result = $xoopsDB->queryF($sql);
    $sql    = sprintf('ALTER TABLE ' . $xoopsDB->prefix('nw_topics') . ' ADD INDEX ( `menu` );');
    $result = $xoopsDB->queryF($sql);

    $moduledirname = XNEWS_MODULE_DIRNAME;

    // At the end, if there was errors, show them or redirect user to the module's upgrade page
    if ($errors) {
        echo '<H1>' . _AM_XNEWS_UPGRADEFAILED . '</H1>';
        echo '<br>' . _AM_XNEWS_UPGRADEFAILED0;
    } else {
        echo _AM_XNEWS_UPGRADECOMPLETE . " - <a href='" . XOOPS_URL . '/modules/system/admin.php?fct=modulesadmin&op=update&module=' . $moduledirname . "'>" . _AM_XNEWS_UPDATEMODULE . '</a>';
    }
} else {
    printf("<h2>%s</h2>\n", _AM_XNEWS_UPGR_ACCESS_ERROR);
}
xoops_cp_footer();
