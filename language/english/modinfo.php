<?php
// $Id: modinfo.php,v 1.21 2004/09/01 17:48:07 hthouzard Exp $
// Module Info

// The name of this module
define('_MI_NW_NAME', 'x' . 'News');

// A brief description of this module
define('_MI_NW_DESC', 'Creates a Slashdot-like news section, where users can post news/comments.');

// Names of blocks for this module (Not all module has blocks)
define('_MI_NW_BNAME1', 'News Topics');
define('_MI_NW_BNAME3', 'Big Story');
define('_MI_NW_BNAME4', 'Top News');
define('_MI_NW_BNAME5', 'Recent News');
define('_MI_NW_BNAME6', 'Moderate News');
define('_MI_NW_BNAME7', 'Navigate thru topics');

// Sub menus in main menu block
define('_MI_NW_SMNAME1', 'Submit News');
define('_MI_NW_SMNAME2', 'Archive');

// Names of admin menu items
define('_MI_NW_ADMENU2', 'Topics Manager');
define('_MI_NW_ADMENU3', 'Post/Edit News');
define('_MI_NW_GROUPPERMS', 'Permissions');
// Added by Hervé for prune option
define('_MI_NW_PRUNENEWS', 'Prune news');
// Added by Hervé
define('_MI_NW_EXPORT', 'News Export');

// Title of config items
define('_MI_NW_STORYHOME', 'Select the number of news items to display on top page');
define('_MI_NW_NOTIFYSUBMIT', 'Select yes to send notification message to webmaster upon new submission');
define('_MI_NW_DISPLAYNAV', 'Select yes to display navigation box at the top of each news page');
define('_MI_NW_AUTOAPPROVE', 'Auto approve news stories without admin intervention?');
define('_MI_NW_ALLOWEDSUBMITGROUPS', 'Groups who can Submit News');
define('_MI_NW_ALLOWEDAPPROVEGROUPS', 'Groups who can Approve News');
define('_MI_NW_NEWSDISPLAY', 'News Display Layout');
define('_MI_NW_NAMEDISPLAY', "Author's name");
define('_MI_NW_COLUMNMODE', 'Columns');
define('_MI_NW_STORYCOUNTADMIN', 'Number of new articles to display in admin area (this option will be also used to limit the number of topics displayed in the admin area and it will be used in the statistics) : ');
define('_MI_NW_UPLOADFILESIZE', 'MAX Filesize Upload (KB) 1048576 = 1 Meg');
define('_MI_NW_UPLOADGROUPS', 'Authorized groups to upload');

// Description of each config items
define('_MI_NW_STORYHOMEDSC', '');
define('_MI_NW_NOTIFYSUBMITDSC', '');
define('_MI_NW_DISPLAYNAVDSC', '');
define('_MI_NW_AUTOAPPROVEDSC', '');
define('_MI_NW_ALLOWEDSUBMITGROUPSDESC', 'The selected groups will be able to submit news items');
define('_MI_NW_ALLOWEDAPPROVEGROUPSDESC', 'The selected groups will be able to approve news items');
define('_MI_NW_NEWSDISPLAYDESC', 'Classic shows all news ordered by date of publish. News by topic will group the news by topic with the latest story in full and the others with just the title');
define('_MI_NW_ADISPLAYNAMEDSC', "Select how to display the author's name");
define('_MI_NW_COLUMNMODE_DESC', 'You can choose the number of columns to display articles list');
define('_MI_NW_STORYCOUNTADMIN_DESC', '');
define('_MI_NW_UPLOADFILESIZE_DESC', '');
define('_MI_NW_UPLOADGROUPS_DESC', 'Select the groups who can upload to the server');

// Name of config item values
define('_MI_NW_NEWSCLASSIC', 'Classic');
define('_MI_NW_NEWSBYTOPIC', 'By Topic');
define('_MI_NW_DISPLAYNAME1', 'Username');
define('_MI_NW_DISPLAYNAME2', 'Real Name');
define('_MI_NW_DISPLAYNAME3', 'Do not display author');
define('_MI_NW_UPLOAD_GROUP1', 'Submitters and Approvers');
define('_MI_NW_UPLOAD_GROUP2', 'Approvers Only');
define('_MI_NW_UPLOAD_GROUP3', 'Upload Disabled');

// Text for notifications
define('_MI_NW_GLOBAL_NOTIFY', 'Global');
define('_MI_NW_GLOBAL_NOTIFYDSC', 'Global news notification options.');

define('_MI_NW_STORY_NOTIFY', 'Story');
define('_MI_NW_STORY_NOTIFYDSC', 'Notification options that apply to the current story.');

define('_MI_NW_GLOBAL_NEWCATEGORY_NOTIFY', 'New Topic');
define('_MI_NW_GLOBAL_NEWCATEGORY_NOTIFYCAP', 'Notify me when a new topic is created.');
define('_MI_NW_GLOBAL_NEWCATEGORY_NOTIFYDSC', 'Receive notification when a new topic is created.');
define('_MI_NW_GLOBAL_NEWCATEGORY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New news topic');

define('_MI_NW_GLOBAL_STORYSUBMIT_NOTIFY', 'New Story Submitted');
define('_MI_NW_GLOBAL_STORYSUBMIT_NOTIFYCAP', 'Notify me when any new story is submitted (awaiting approval).');
define('_MI_NW_GLOBAL_STORYSUBMIT_NOTIFYDSC', 'Receive notification when any new story is submitted (awaiting approval).');
define('_MI_NW_GLOBAL_STORYSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New news story submitted');

define('_MI_NW_GLOBAL_NEWSTORY_NOTIFY', 'New Story');
define('_MI_NW_GLOBAL_NEWSTORY_NOTIFYCAP', 'Notify me when any new story is posted.');
define('_MI_NW_GLOBAL_NEWSTORY_NOTIFYDSC', 'Receive notification when any new story is posted.');
define('_MI_NW_GLOBAL_NEWSTORY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New news story');

define('_MI_NW_STORY_APPROVE_NOTIFY', 'Story Approved');
define('_MI_NW_STORY_APPROVE_NOTIFYCAP', 'Notify me when this story is approved.');
define('_MI_NW_STORY_APPROVE_NOTIFYDSC', 'Receive notification when this story is approved.');
define('_MI_NW_STORY_APPROVE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Story approved');

define('_MI_NW_RESTRICTINDEX', 'Restrict Topics on Index Page?');
define('_MI_NW_RESTRICTINDEXDSC', 'If set to yes, users will only see news items listed in the index from the topics, they have access to as set in News Permissions');

define('_MI_NW_NEWSBYTHISAUTHOR', 'News by the same author');
define('_MI_NW_NEWSBYTHISAUTHORDSC', "If you set this option to yes, then a link 'Articles by this author' will be visible");

define('_MI_NW_PREVNEX_LINK', 'Show Previous and Next link ?');
define('_MI_NW_PREVNEX_LINK_DESC', "When this option is set to 'Yes', two new links are visibles at the bottom of each article. Those links are used to go to the previous and next article according to the publish date");
define('_MI_NW_SUMMARY_SHOW', 'Show summary table ?');
define('_MI_NW_SUMMARY_SHOW_DESC', 'When you use this option, a summary containing links to all the recent published articles is visible at the bottom of each article');
define('_MI_NW_AUTHOR_EDIT', 'Enable authors to edit their post ?');
define('_MI_NW_AUTHOR_EDIT_DESC', 'With this option, authors can edit their posts.');
define('_MI_NW_RATE_NEWS', 'Enable users to rate news ?');
define('_MI_NW_TOPICS_RSS', 'Enable RSS feeds per topics ?');
define('_MI_NW_TOPICS_RSS_DESC', "If you set this option to 'Yes' then the topics content will be available as RSS feeds");
define('_MI_NW_DATEFORMAT', "Date's format");
define('_MI_NW_DATEFORMAT_DESC', "Please refer to the Php documentation (http://fr.php.net/manual/en/function.date.php) for more information on how to select the format. Note, if you don't type anything then the default date's format will be used");
define('_MI_NW_META_DATA', 'Enable meta datas (keywords and description) to be entered ?');
define('_MI_NW_META_DATA_DESC', "If you set this option to 'yes' then the approvers will be able to enter the following meta datas : keywords and description");
define('_MI_NW_BNAME8', 'Random news');
define('_MI_NW_NEWSLETTER', 'Newsletter');
define('_MI_NW_STATS', 'Statistics');
define('_MI_NW_FORM_OPTIONS', 'Form Option');
define('_MI_NW_FORM_COMPACT', 'Compact');
define('_MI_NW_FORM_DHTML', 'DHTML');
define('_MI_NW_FORM_SPAW', 'Spaw Editor');
define('_MI_NW_FORM_HTMLAREA', 'HtmlArea Editor');
define('_MI_NW_FORM_FCK', 'FCK Editor');
define('_MI_NW_FORM_KOIVI', 'Koivi Editor');
define('_MI_NW_FORM_OPTIONS_DESC', "Select the editor to use. If you have a 'simple' install (e.g you use only xoops core editor class, provided in the standard xoops core package), then you can just select DHTML and Compact");
define('_MI_NW_KEYWORDS_HIGH', 'Use keywords highlighting ?');
define('_MI_NW_KEYWORDS_HIGH_DESC', 'If you use this option then the keywords typed in the search will be highlited in the articles');
define('_MI_NW_HIGH_COLOR', 'Color used to highlight keywords ?');
define('_MI_NW_HIGH_COLOR_DES', 'Only use this option if you have choosed Yes for the previous option');
define('_MI_NW_INFOTIPS', 'Tooltips length');
define('_MI_NW_INFOTIPS_DES', 'If you use this option, links related to news will contains the first (n) characters of the article. If you set this value to 0 then the infotips will be empty');
define('_MI_NW_SITE_NAVBAR', "Use Mozilla and Opera Site Navigation's Bar ?");
define('_MI_NW_SITE_NAVBAR_DESC', "If you set this option to Yes then the visitors of your website will be able to use the Site Navigation's Bar to navigate thru your articles.");
define('_MI_NW_TABS_SKIN', 'Select the skin to use in tabs');
define('_MI_NW_TABS_SKIN_DESC', 'This skin will be used by all blocks which uses tabs');
define('_MI_NW_SKIN_1', 'Bar Style');
define('_MI_NW_SKIN_2', 'Beveled');
define('_MI_NW_SKIN_3', 'Classic');
define('_MI_NW_SKIN_4', 'Folders');
define('_MI_NW_SKIN_5', 'MacOs');
define('_MI_NW_SKIN_6', 'Plain');
define('_MI_NW_SKIN_7', 'Rounded');
define('_MI_NW_SKIN_8', 'ZDnet style');

// Added in version 1.50
define('_MI_NW_BNAME9', 'Archives');
define('_MI_NW_FORM_TINYEDITOR', 'TinyEditor');
define('_MI_NW_FOOTNOTES', 'Shows links in printable versions of your articles ?');
define('_MI_NW_DUBLINCORE', 'Ativate Dublin Core Metadata ?');
define('_MI_NW_DUBLINCORE_DSC', "For more information, <a href='http://dublincore.org/'>visit this link</a>");
define('_MI_NW_BOOKMARK_ME', "Display a 'Bookmark this article at these sites' block ?");
define('_MI_NW_BOOKMARK_ME_DSC', "This block will be visible on the article's page");
define('_MI_NW_FF_MICROFORMAT', 'Activate Firefox 2 Micro Summaries ?');
define('_MI_NW_FF_MICROFORMAT_DSC', "For more information, see <a href='http://wiki.mozilla.org/Microsummaries' target='_blank'>this page</a>");
define('_MI_NW_WHOS_WHO', "Who's Who");
define('_MI_NW_METAGEN', 'Metagen');
define('_MI_NW_TOPICS_DIRECTORY', 'Topics Directory');
define('_MI_NW_ADVERTISEMENT', 'Advertisement');
define('_MI_NW_ADV_DESCR', 'Enter a text or a javascript code to display in your articles');
define('_MI_NW_MIME_TYPES', 'Enter authorized Mime Types for upload (separated them on a new line)');
define('_MI_NW_ENHANCED_PAGENAV', 'Use enhanced page navigator ?');
define('_MI_NW_ENHANCED_PAGENAV_DSC', 'With this option you can separate your pages with something like this : [pagebreak:Page Title], the links to the pages are replaced by a dropdown list and you can use [summary] to create an automatic summary of pages');

// Added in version 1.54
define('_MI_NW_CATEGORY_NOTIFY', 'Category');
define('_MI_NW_CATEGORY_NOTIFYDSC', 'Notification options that apply to the current category');

define('_MI_NW_CATEGORY_STORYPOSTED_NOTIFY', 'New Story Submitted');
define('_MI_NW_CATEGORY_STORYPOSTED_NOTIFYCAP', 'Notify me when any new story is posted to this category.');
define('_MI_NW_CATEGORY_STORYPOSTED_NOTIFYDSC', 'Receive notification when any new story is posted to this category.');
define('_MI_NW_CATEGORY_STORYPOSTED_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New news story');

// Added in version 1.63
define('_MI_NW_TAGS', 'Use the tags system ?');
define('_MI_NW_TAGS_DSC', 'This is based on the XOOPS module Tag from phppp');
define('_MI_NW_BNAME10', 'Tags Cloud');
define('_MI_NW_BNAME11', 'Top Tags');
define('_MI_NW_INTRO_TEXT', 'Introduction text to show on the submit page');
define('_MI_NW_IMAGE_MAX_WIDTH', 'Maximum image width when it is resized');
define('_MI_NW_IMAGE_MAX_HEIGHT', 'Maximum image height when it is resized');

// Added in version 1.67
define('_MI_NW_CLONER', 'Clone Manager');

define('_MI_NW_LATESTNEWS_BLOCK', 'Latest News Block');

// Added in version 1.68 BETA
define('_MI_NW_TOPICDISPLAY', 'Display Topics ');
define('_MI_NW_TOPICDISPLAYDESC', 'This will enable/disable Topics title in title headers');

define('_MI_NW_SEOENABLE', 'SEO enable ');
define('_MI_NW_SEOENABLEDESC',
       'This will enable/disable SEO activity.<br> If <b>htaccess</b> is selected you will get: <br> http://your.site.com/<b>xnews</b>/topics.1/your-topic-title.html <br> If path-info is selected you will get: <br> http://your.site.com/modules/xnews/index.php/topics.1/your-topic-title.html');

// Added in version 1.68 RC1
define('_MI_NW_EXTEND_META_DATA', 'Extend meta-data input');
define('_MI_NW_EXTEND_META_DATA_DESC', 'This will toggle between text and textarea for user edit meta-data input<br> for both description and keywords.');

define('_MI_NW_NONE', 'None');
define('_MI_NW_TOPONLY', 'Top only');
define('_MI_NW_BOTTOMONLY', 'Bottom only');
define('_MI_NW_BOTH', 'Both');
define('_MI_NW_DISPLAYLINKICNS', 'Display Link Icons');
define('_MI_NW_DISPLAYLINKICNSDESC', 'Display print, friend and pdf icons none-top-bottom-both');

define('_MI_NW_SEOPATH', 'SEO path ');
define('_MI_NW_SEOPATHDESC',
       'This will add a title to SEO url for both <b>htaccess</b> and <b>path-info</b> modes. <br> If left empty you will get: <br> http://your.site.com/topics.1/your-topic-title.html <br> If you type <b>news</b> you will get: <br> http://your.site.com/<b>news</b>/topics.1/your-topic-title.html <br><br> <b>a-z chars and minus char accepted. eg. news-of-day</b>');
define('_MI_NW_SEOLEVEL', 'SEO level ');
define('_MI_NW_SEOLEVELDESC',
       'Here you can chose whether to have htaccess in xoops root dir or in module one. <br> This will change the appearance of the url. <br> In root level: <br> http://your.site.com/news/topics.1/your-topic-title.html <br> In module level: <br> http://your.site.com/modules/xnews/news.topics.1/your-topic-title.html<br><br> Only works in htaccess mode and htaccess files will have to be installed accordingly.');
define('_MI_NW_MODULE_LEVEL', 'Module level');
define('_MI_NW_ROOT_LEVEL', 'Root level');

//ADDED wishcraft 1.68
define('_MI_NW_SEOENDOFURL', 'End of URL');
define('_MI_NW_SEOENDOFURL_DESC', 'File Extension to HTML Files');
define('_MI_NW_SEOENDOFURLRSS', 'End of URL');
define('_MI_NW_SEOENDOFURLRSS_DESC', 'File Extension to RSS Pages');
define('_MI_NW_SEOENDOFURLPDF', 'End of URL');
define('_MI_NW_SEOENDOFURLPDF_DESC', 'File Extension to Adobe Acrobat (PDF) Files');

//ADDED in version 1.71
define('_MI_NW_PDF_DISPLAY', 'Display attached PDF');
define('_MI_NW_PDF_DISPLAY_DESC', 'If enabled attached pdf files will be displayed in the article page.');
define('_MI_NW_PDF_DETECT', 'Actvate PDF plugin detection');
define('_MI_NW_PDF_DETECT_DESC', 'If enabled this will affect client side browser not to show <br>  PDF iframes if PDF browser plugin is not installed. <br> Works together with Display attached PDF.');
define('_MI_NW_IMAGES_DISPLAY', 'Display attached images');
define('_MI_NW_IMAGES_DISPLAY_DESC', 'If enabled attached images will be displayed in the article page.');
define('_MI_NW_THUMB_MAX_WIDTH', 'Maximum attached image thumb width when it is resized');
define('_MI_NW_THUMB_MAX_HEIGHT', 'Maximum attached image thumb height when it is resized');

//2.00
//Help
define('_MI_NW_DIRNAME', basename(dirname(dirname(__DIR__))));
define('_MI_NW_HELP_HEADER', __DIR__ . '/help/helpheader.tpl');
define('_MI_NW_BACK_2_ADMIN', 'Back to Administration of ');
define('_MI_NW_OVERVIEW', 'Overview');

//define('_MI_NW_HELP_DIR', __DIR__);

//help multi-page
define('_MI_NW_DISCLAIMER', 'Disclaimer');
define('_MI_NW_LICENSE', 'License');
define('_MI_NW_SUPPORT', 'Support');

define('_MI_NW_HOME', 'Home');
define('_MI_NW_ABOUT', 'About');
