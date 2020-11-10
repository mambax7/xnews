<?php
// $Id: admin.php,v 1.18 2004/07/26 17:51:25 hthouzard Exp $
//%%%%%%    Admin Module Name  Articles     %%%%%
define('_AM_XNEWS_DBUPDATED', 'Database Updated Successfully!');
define('_AM_XNEWS_CONFIG', 'News Configuration');
define('_AM_XNEWS_AUTOARTICLES', 'Automated Articles');
define('_AM_XNEWS_STORYID', 'Story ID');
define('_AM_XNEWS_TITLE', 'Title');
define('_AM_XNEWS_TOPIC', 'Topic');
define('_AM_XNEWS_POSTER', 'Poster');
define('_AM_XNEWS_PROGRAMMED', 'Programmed Date/Time');
define('_AM_XNEWS_ACTION', 'Action');
define('_AM_XNEWS_EDIT', 'Edit');
define('_AM_XNEWS_DELETE', 'Delete');
define('_AM_XNEWS_LAST10ARTS', 'Last %d Articles');
define('_AM_XNEWS_PUBLISHED', 'Published'); // Published Date
define('_AM_XNEWS_GO', 'Go!');
define('_AM_XNEWS_EDITARTICLE', 'Edit Article');
define('_AM_XNEWS_POSTNEWARTICLE', 'Post New Article');
define('_AM_XNEWS_ARTPUBLISHED', 'Your article has been published!');
define('_AM_XNEWS_HELLO', 'Hello %s, ');
define('_AM_XNEWS_YOURARTPUB', 'Your article submitted to our site has been published.');
define('_AM_XNEWS_TITLEC', 'Title: ');
define('_AM_XNEWS_URLC', 'URL: ');
define('_AM_XNEWS_PUBLISHEDC', 'Published: ');
define('_AM_XNEWS_RUSUREDEL', 'Are you sure you want to delete this article and all its comments?');
define('_AM_XNEWS_YES', 'Yes');
define('_AM_XNEWS_NO', 'No');
define('_AM_XNEWS_INTROTEXT', 'Intro Text');
define('_AM_XNEWS_EXTEXT', 'Extended Text');
define('_AM_XNEWS_ALLOWEDHTML', 'Allowed HTML:');
define('_AM_XNEWS_DISAMILEY', 'Disable Smiley');
define('_AM_XNEWS_DISHTML', 'Disable HTML');
define('_AM_XNEWS_APPROVE', 'Approve');
define('_AM_XNEWS_MOVETOTOP', 'Move this story to top');
define('_AM_XNEWS_CHANGEDATETIME', 'Change the date/time of publication');
define('_AM_XNEWS_NOWSETTIME', 'It is now set at: %s'); // %s is datetime of publish
define('_AM_XNEWS_CURRENTTIME', 'Current time is: %s');  // %s is the current datetime
define('_AM_XNEWS_SETDATETIME', 'Set the date/time of publish');
define('_AM_XNEWS_MONTHC', 'Month:');
define('_AM_XNEWS_DAYC', 'Day:');
define('_AM_XNEWS_YEARC', 'Year:');
define('_AM_XNEWS_TIMEC', 'Time:');
define('_AM_XNEWS_PREVIEW', 'Preview');
define('_AM_XNEWS_SAVE', 'Save');
define('_AM_XNEWS_PUBINHOME', 'Publish in Home?');
define('_AM_XNEWS_ADD', 'Add');

//%%%%%%    Admin Module Name  Topics   %%%%%

define('_AM_XNEWS_ADDMTOPIC', 'Add a MAIN Topic');
define('_AM_XNEWS_TOPICNAME', 'Topic Name');
// Warning, changed from 40 to 255 characters.
define('_AM_XNEWS_MAX40CHAR', '(max: 255 characters)');
define('_AM_XNEWS_TOPICIMG', 'Topic Image');
define('_AM_XNEWS_IMGNAEXLOC', 'image name + extension located in %s');
define('_AM_XNEWS_FEXAMPLE', 'for example: games.gif');
define('_AM_XNEWS_ADDSUBTOPIC', 'Add a SUB-Topic');
define('_AM_XNEWS_IN', 'in');
define('_AM_XNEWS_MODIFYTOPIC', 'Modify Topic');
define('_AM_XNEWS_MODIFY', 'Modify');
define('_AM_XNEWS_PARENTTOPIC', 'Parent Topic');
define('_AM_XNEWS_SAVECHANGE', 'Save Changes');
define('_AM_XNEWS_DEL', 'Delete');
define('_AM_XNEWS_CANCEL', 'Cancel');
define('_AM_XNEWS_WAYSYWTDTTAL', 'WARNING: Are you sure you want to delete this Topic and ALL its Stories and Comments?');

// Added in Beta6
define('_AM_XNEWS_TOPICSMNGR', 'Topics Manager');
define('_AM_XNEWS_PEARTICLES', 'Post/Edit Articles');
define('_AM_XNEWS_NEWSUB', 'New Submissions');
define('_AM_XNEWS_POSTED', 'Posted');
define('_AM_XNEWS_GENERALCONF', 'General Configuration');

// Added in RC2
define('_AM_XNEWS_TOPICDISPLAY', 'Display Topic Image?');
define('_AM_XNEWS_TOPICALIGN', 'Position');
define('_AM_XNEWS_RIGHT', 'Right');
define('_AM_XNEWS_LEFT', 'Left');

define('_AM_XNEWS_EXPARTS', 'Expired Articles');
define('_AM_XNEWS_EXPIRED', 'Expired');
define('_AM_XNEWS_CHANGEEXPDATETIME', 'Change the date/time of expiration');
define('_AM_XNEWS_SETEXPDATETIME', 'Set the date/time of expiration');
define('_AM_XNEWS_NOWSETEXPTIME', 'It is now set at: %s');

// Added in RC3
define('_AM_XNEWS_ERRORTOPICNAME', 'You must enter a topic name!');
define('_AM_XNEWS_EMPTYNODELETE', 'Nothing to delete!');

// Added 240304 (Mithrandir)
define('_AM_XNEWS_GROUPPERM', 'Permissions');
define('_AM_XNEWS_SELFILE', 'Select file to upload');

// Added by Hervé
define('_AM_XNEWS_UPLOAD_DBERROR_SAVE', 'Error while attaching file to the story');
define('_AM_XNEWS_UPLOAD_ERROR', 'Error while uploading the file');
define('_AM_XNEWS_UPLOAD_ATTACHFILE', 'Attached file(s)');
define('_AM_XNEWS_APPROVEFORM', 'Approve Permissions');
define('_AM_XNEWS_SUBMITFORM', 'Submit Permissions');
define('_AM_XNEWS_VIEWFORM', 'View Permissions');
define('_AM_XNEWS_APPROVEFORM_DESC', 'Select, who can approve news');
define('_AM_XNEWS_SUBMITFORM_DESC', 'Select, who can submit news');
define('_AM_XNEWS_VIEWFORM_DESC', 'Select, who can view which topics');
define('_AM_XNEWS_DELETE_SELFILES', 'Delete selected files');
define('_AM_XNEWS_TOPIC_PICTURE', 'Upload picture');
define('_AM_XNEWS_UPLOAD_WARNING', '<B>Warning, do not forget to apply write permissions to the following folder : %s</B>');

define('_AM_XNEWS_UPGRADECOMPLETE', 'Upgrade Complete');
define('_AM_XNEWS_UPDATEMODULE', 'Update module templates and blocks');
define('_AM_XNEWS_UPGRADEFAILED', 'Upgrade Failed');
define('_AM_XNEWS_UPGRADE', 'Upgrade');
define('_AM_XNEWS_ADD_TOPIC', 'Add a topic');
define('_AM_XNEWS_ADD_TOPIC_ERROR', 'Error, topic already exists!');
define('_AM_XNEWS_ADD_TOPIC_ERROR1', 'ERROR: Cannot select this topic for parent topic!');
define('_AM_XNEWS_SUB_MENU', 'Publish this topic as a sub menu');
define('_AM_XNEWS_SUB_MENU_YESNO', 'Sub-menu?');
define('_AM_XNEWS_HITS', 'Hits');
define('_AM_XNEWS_CREATED', 'Created');

define('_AM_XNEWS_TOPIC_DESCR', "Topic's description");
define('_AM_XNEWS_USERS_LIST', 'Users List');
define('_AM_XNEWS_PUBLISH_FRONTPAGE', 'Publish in front page ?');
define('_AM_XNEWS_UPGRADEFAILED1', 'Impossible to create the table stories_files');
define('_AM_XNEWS_UPGRADEFAILED2', "Impossible to change the topic title's length");
define('_AM_XNEWS_UPGRADEFAILED21', 'Impossible to add the new fields to the topics table');
define('_AM_XNEWS_UPGRADEFAILED3', 'Impossible to create the table stories_votedata');
define('_AM_XNEWS_UPGRADEFAILED4', "Impossible to create the two fields 'rating' and 'votes' for the 'story' table");
define('_AM_XNEWS_UPGRADEFAILED0', "Please note the messages and try to correct the problems with phpMyadmin and the sql definition's file available in the 'sql' folder of the news module");
define('_AM_XNEWS_UPGR_ACCESS_ERROR', 'Error, to use the upgrade script, you must be an admin on this module');
define('_AM_XNEWS_PRUNE_BEFORE', 'Prune stories that were published before');
define('_AM_XNEWS_PRUNE_EXPIREDONLY', 'Only remove stories who have expired');
define('_AM_XNEWS_PRUNE_CONFIRM', "Warning, you are going to permanently remove stories that were published before %s (this action can't be undone). It represents %s stories.<br>Are you sure ?");
define('_AM_XNEWS_PRUNE_TOPICS', 'Limit to the following topics');
define('_AM_XNEWS_PRUNENEWS', 'Prune news');
define('_AM_XNEWS_EXPORT_NEWS', 'News Export (in XML)');
define('_AM_XNEWS_EXPORT_NOTHING', "Sorry but there's nothing to export, please verify your criterias");
define('_AM_XNEWS_PRUNE_DELETED', '%d news was deleted');
define('_AM_XNEWS_PERM_WARNING', '<h2>Warning, you have 3 forms so you have 3 submit buttons</h2>');
define('_AM_XNEWS_EXPORT_BETWEEN', 'Export news published between');
define('_AM_XNEWS_EXPORT_AND', ' and ');
define('_AM_XNEWS_EXPORT_PRUNE_DSC', "If you don't check anything then all the topics will be used<br> else only the selected topics will be used");
define('_AM_XNEWS_EXPORT_INCTOPICS', 'Include Topics Definitions ?');
define('_AM_XNEWS_EXPORT_ERROR', 'Error while trying to create the file %s. Operation stopped.');
define('_AM_XNEWS_EXPORT_READY', "Your xml export file is ready for download. <br><a href='%s'>Click on this link to download it</a>.<br>Don't forget <a href='%s'>to remove it</a> once you have finished.");
define('_AM_XNEWS_RSS_URL', 'URL of RSS feed');
define('_AM_XNEWS_NEWSLETTER', 'Newsletter');
define('_AM_XNEWS_NEWSLETTER_BETWEEN', 'Select News published between');
define('_AM_XNEWS_NEWSLETTER_READY', "Your newsletter file is ready for download. <br><a href='%s'>Click on this link to download it</a>.<br>Don't forget to <a href='%s'>remove it</a> once you have finished.");
define('_AM_XNEWS_DELETED_OK', 'File deleted successfully');
define('_AM_XNEWS_DELETED_PB', 'There was a problem while deleting the file');
define('_AM_XNEWS_STATS0', 'Topics statistics');
define('_AM_XNEWS_STATS', 'Statistics');
define('_AM_XNEWS_STATS1', 'Unique Authors');
define('_AM_XNEWS_STATS2', 'Totals');
define('_AM_XNEWS_STATS3', 'Articles statistics');
define('_AM_XNEWS_STATS4', 'Most read articles');
define('_AM_XNEWS_STATS5', 'Less read articles');
define('_AM_XNEWS_STATS6', 'Best rated articles');
define('_AM_XNEWS_STATS7', 'Most read authors');
define('_AM_XNEWS_STATS8', 'Best rated authors');
define('_AM_XNEWS_STATS9', 'Biggest contributors');
define('_AM_XNEWS_STATS10', 'Authors statistics');
define('_AM_XNEWS_STATS11', 'Articles count');
define('_AM_XNEWS_HELP', 'Help');
define('_AM_XNEWS_MODULEADMIN', 'Module Admin');
define('_AM_XNEWS_GENERALSET', 'Preferences');
define('_AM_XNEWS_GOTOMOD', 'Go to module');
define('_AM_XNEWS_NOTHING', "Sorry but there's nothing to download, verify your criterias !");
define('_AM_XNEWS_NOTHING_PRUNE', "Sorry but there's no news to prune, verify your criterias !");
define('_AM_XNEWS_TOPIC_COLOR', "Topics's color");
define('_AM_XNEWS_COLOR', 'Color');
define('_AM_XNEWS_REMOVE_BR', 'Convert the html &lt;br&gt; tag to a new line ?');
// Added in 1.3 RC2
define('_AM_XNEWS_PLEASE_UPGRADE', "<a href='upgrade.php'><font color='#FF0000'>Please upgrade the module !</font></a>");

// Added in verisn 1.50
define('_AM_XNEWS_NEWSLETTER_HEADER', 'Header');
define('_AM_XNEWS_NEWSLETTER_FOOTER', 'Footer');
define('_AM_XNEWS_NEWSLETTER_HTML_TAGS', 'Remove html tags ?');
define('_AM_XNEWS_VERIFY_TABLES', 'Maintain tables');
define('_AM_XNEWS_METAGEN', 'Metagen');
define('_AM_XNEWS_METAGEN_DESC', 'Metagen is a system that can help you to have your page best indexed by search engines.<br>Except if you type meta keywords and meta descriptions yourself, the module will automatically create them.');
define('_AM_XNEWS_BLACKLIST', 'Blacklist');
define('_AM_XNEWS_BLACKLIST_DESC', 'The words in this list will not be used to create meta keywords');
define('_AM_XNEWS_BLACKLIST_ADD', 'Add');
define('_AM_XNEWS_BLACKLIST_ADD_DSC', 'Enter words to add in the list<br>(one word by line)');
define('_AM_XNEWS_META_KEYWORDS_CNT', 'Maximum count of meta keywords to auto-generate');
define('_AM_XNEWS_META_KEYWORDS_ORDER', 'Keywords order');
define('_AM_XNEWS_META_KEYWORDS_INTEXT', 'Create them in the order they appear in the text');
define('_AM_XNEWS_META_KEYWORDS_FREQ1', "Words frequency's order");
define('_AM_XNEWS_META_KEYWORDS_FREQ2', 'Reverse order of words frequency');

// Added in version 1.67 Beta
define('_AM_XNEWS_SUBPREFIX', 'Sub-prefix');

define('_AM_XNEWS_CLONER', 'Clone Manager');
define('_AM_XNEWS_CLONER_CLONES', 'Clones');
define('_AM_XNEWS_CLONER_ADD', 'Add Clone');
define('_AM_XNEWS_CLONER_ID', 'ID');
define('_AM_XNEWS_CLONER_NAME', 'Name');
define('_AM_XNEWS_CLONER_DIRFOL', 'Directory/Folder');
define('_AM_XNEWS_CLONER_VERSION', 'Version');

define('_AM_XNEWS_CLONER_NEWNAME', 'New module name');
define(
    '_AM_XNEWS_CLONER_NEWNAMEDESC',
    "This will also affect the creation of the new module folder. <br> Case sensitivity and spaces are ignored and will be auto corrected. <br> eg. new name = <b>Library</b> new dir  = <b>library</b>, <br> new name <b>My Library</b> new dir = <b>mylibrary</b>. <br><br> Start module is: <font color='#008400'><b> %s </b></font><br>"
);
define('_AM_XNEWS_CLONER_NEWNAMELABEL', 'New Module:');

define('_AM_XNEWS_CLONER_DIREXISTS', "Directory/Folder '%s' already exists!!");
define('_AM_XNEWS_CLONER_CREATED', "Module '%s' was correctly cloned!!");
define('_AM_XNEWS_CLONER_UPRADED', "Module '%s' has been correctly upgraded!!");
define('_AM_XNEWS_CLONER_NOMODULEID', 'Module ID was not set!');

define('_AM_XNEWS_CLONER_UPDATE', 'Update');
define('_AM_XNEWS_CLONER_INSTALL', 'Install');
define('_AM_XNEWS_CLONER_UNINSTALL', 'Uninstall');
define('_AM_XNEWS_CLONER_ACTION_INSTALL', 'Install/Uninstall');

define('_AM_XNEWS_CLONER_IMPORTNEWS', 'Import original News module data');
define('_AM_XNEWS_CLONER_IMPORTNEWSDESC1', 'Original News module exists! Import data now?');
define(
    '_AM_XNEWS_CLONER_IMPORTNEWSDESC2',
    'The import button only appears if x' . 'News module stories table is empty. <br>
                                         If you added story item before importing from <br>
                                         original News module you will have to uninstall/reinstall x' . 'News. <br>
                                         If you already imported original News Module data, leave as is.'
);
define('_AM_XNEWS_CLONER_IMPORTNEWSSUB', 'Import');
define('_AM_XNEWS_CLONER_NEWSIMPORTED', 'Original News module data correctly imported.');

// Added in version 1.68 Beta
define(
    '_AM_XNEWS_DESCRIPTION',
    '<H3>x' . 'News is a clonable news module</H3>
                              where users can post news/comments. The module can be cloned to enable one only method for many different tasks. Other than usual news it can be used for info, links and more all with their own blocks, topics and settings.'
);

// Added in version 1.68 RC1
define('_AM_XNEWS_CLONER_CLONEDELETED', "'%s' clone has been deleted succesfully.");
define('_AM_XNEWS_CLONER_CLONEDELETEDERR', "'%s' clone could not be deleted - check permissions.");
define('_AM_XNEWS_CLONER_CLONEUPGRADED', 'Upgraded');
define('_AM_XNEWS_CLONER_UPGRADEFORCE', 'Force upgrade');
define('_AM_XNEWS_CLONER_CLONEDELETION', 'Deleting Clone');
define('_AM_XNEWS_CLONER_SUREDELETE', "Are you sure you want to delete <font color='#000000'>'%s'</font> clone?<br>");
define('_AM_XNEWS_CLONER_CLONEID', 'Clone ID was not set!');

// Added in version 1.68 RC2
define('_AM_XNEWS_INDEX', 'Index');

// Added in version 1.68 RC3
define('_AM_XNEWS_DOLINEBREAK', 'Enable Line Break');
define('_AM_XNEWS_TOPICS', 'Topics');

// Added in version 1.71
define('_AM_XNEWS_IMAGE_ROWS', 'Image display rows');
define('_AM_XNEWS_PDF_ROWS', 'PDF display rows');

//2.00
//define('_AM_XNEWS_UPGRADEFAILED0', "Update failed - couldn't rename field '%s'");
//define('_AM_XNEWS_UPGRADEFAILED1', "Update failed - couldn't add new fields");
//define('_AM_XNEWS_UPGRADEFAILED2', "Update failed - couldn't rename table '%s'");
define('_AM_XNEWS_ERROR_COLUMN', 'Could not create column in database : %s');
define('_AM_XNEWS_ERROR_BAD_XOOPS', 'This module requires XOOPS %s+ (%s installed)');
define('_AM_XNEWS_ERROR_BAD_PHP', 'This module requires PHP version %s+ (%s installed)');
define('_AM_XNEWS_ERROR_TAG_REMOVAL', 'Could not remove tags from Tag Module');
