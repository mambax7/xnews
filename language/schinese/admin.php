<?php
// $Id: admin.php,v 1.18 2004/07/26 17:51:25 hthouzard Exp $
//%%%%%%	Admin Module Name  Articles 	%%%%%
define('_AM_XNEWS_DBUPDATED', '数据库更新完成！');
define('_AM_XNEWS_CONFIG', '新闻区设置');
define('_AM_XNEWS_AUTOARTICLES', '自动发布');
define('_AM_XNEWS_STORYID', '新闻ID');
define('_AM_XNEWS_TITLE', '新闻主题');
define('_AM_XNEWS_TOPIC', '新闻分类');
define('_AM_XNEWS_POSTER', '发布者');
define('_AM_XNEWS_PROGRAMMED', '日期/时间安排');
define('_AM_XNEWS_ACTION', '操作');
define('_AM_XNEWS_EDIT', '编辑');
define('_AM_XNEWS_DELETE', '删除');
define('_AM_XNEWS_LAST10ARTS', '最新%d篇新闻');
define('_AM_XNEWS_PUBLISHED', '发布时间'); // Published Date
define('_AM_XNEWS_GO', '确定');
define('_AM_XNEWS_EDITARTICLE', '编辑新闻');
define('_AM_XNEWS_POSTNEWARTICLE', '发布新闻');
define('_AM_XNEWS_ARTPUBLISHED', '您提交的新闻已经发布了！');
define('_AM_XNEWS_HELLO', '您好，%s');
define('_AM_XNEWS_YOURARTPUB', '您提供的新闻巳经发布了。');
define('_AM_XNEWS_TITLEC', '新闻标题：');
define('_AM_XNEWS_URLC', '网址：');
define('_AM_XNEWS_PUBLISHEDC', '发布时间：');
define('_AM_XNEWS_RUSUREDEL', '您确定要删除此新闻和所有的评论吗？');
define('_AM_XNEWS_YES', '是');
define('_AM_XNEWS_NO', '否');
define('_AM_XNEWS_INTROTEXT', '简述');
define('_AM_XNEWS_EXTEXT', '正文');
define('_AM_XNEWS_ALLOWEDHTML', '允许使用HTML语法：');
define('_AM_XNEWS_DISAMILEY', '禁用表情图');
define('_AM_XNEWS_DISHTML', '禁用HTML语法');
define('_AM_XNEWS_APPROVE', '<font color=red>核准</font>');
define('_AM_XNEWS_MOVETOTOP', '将该新闻移动到最顶端');
define('_AM_XNEWS_CHANGEDATETIME', '改变发布的日期/时间');
define('_AM_XNEWS_NOWSETTIME', '设置如下：%s'); // %s is datetime of publish
define('_AM_XNEWS_CURRENTTIME', '现在的时间：%s');  // %s is the current datetime
define('_AM_XNEWS_SETDATETIME', '设置发布的日期/时间');
define('_AM_XNEWS_MONTHC', '月：');
define('_AM_XNEWS_DAYC', '日：');
define('_AM_XNEWS_YEARC', '年：');
define('_AM_XNEWS_TIMEC', '时间：');
define('_AM_XNEWS_PREVIEW', '预览');
define('_AM_XNEWS_SAVE', '保存');
define('_AM_XNEWS_PUBINHOME', '发布在首页？');
define('_AM_XNEWS_ADD', '添加');

//%%%%%%	Admin Module Name  Topics 	%%%%%

define('_AM_XNEWS_ADDMTOPIC', '添加一个主分类');
define('_AM_XNEWS_TOPICNAME', '分类名称');
// Warning, changed from 40 to 255 characters.
define('_AM_XNEWS_MAX40CHAR', '(最大：255 字符)');
define('_AM_XNEWS_TOPICIMG', '分类图像');
define('_AM_XNEWS_IMGNAEXLOC', '图像名称 + 扩展名，位於 %s');
define('_AM_XNEWS_FEXAMPLE', '例如：games.gif');
define('_AM_XNEWS_ADDSUBTOPIC', '添加一个子分类');
define('_AM_XNEWS_IN', '於');
define('_AM_XNEWS_MODIFYTOPIC', '修改分类');
define('_AM_XNEWS_MODIFY', '修改');
define('_AM_XNEWS_PARENTTOPIC', '主分类');
define('_AM_XNEWS_SAVECHANGE', '保存更改');
define('_AM_XNEWS_DEL', '删除');
define('_AM_XNEWS_CANCEL', '取消');
define('_AM_XNEWS_WAYSYWTDTTAL', '警告：您确定要删除这个新闻分类及其所有的内容和评论吗？');

// Added in Beta6
define('_AM_XNEWS_TOPICSMNGR', '分类管理');
define('_AM_XNEWS_PEARTICLES', '发表/编辑新闻');
define('_AM_XNEWS_NEWSUB', '新发表的新闻');
define('_AM_XNEWS_POSTED', '发表');
define('_AM_XNEWS_GENERALCONF', '偏好设定');

// Added in RC2
define('_AM_XNEWS_TOPICDISPLAY', '是否显示新闻分类图案？');
define('_AM_XNEWS_TOPICALIGN', '位置');
define('_AM_XNEWS_RIGHT', '右');
define('_AM_XNEWS_LEFT', '左');

define('_AM_XNEWS_EXPARTS', '过期新闻');
define('_AM_XNEWS_EXPIRED', '过期时效');
define('_AM_XNEWS_CHANGEEXPDATETIME', '改变过期时效日期/时间');
define('_AM_XNEWS_SETEXPDATETIME', '设置过期时效日期/时间');
define('_AM_XNEWS_NOWSETEXPTIME', '当前设置为：%s');

// Added in RC3
define('_AM_XNEWS_ERRORTOPICNAME', '必须输入分类名称！');
define('_AM_XNEWS_EMPTYNODELETE', '内容为空！');

// Added 240304 (Mithrandir)
define('_AM_XNEWS_GROUPPERM', '权限');
define('_AM_XNEWS_SELFILE', '选择上传文件');

// Added by Hervè
define('_AM_XNEWS_UPLOAD_DBERROR_SAVE', '添加附件出错');
define('_AM_XNEWS_UPLOAD_ERROR', '上传文件出错');
define('_AM_XNEWS_UPLOAD_ATTACHFILE', '上传的文件');
define('_AM_XNEWS_APPROVEFORM', '审核权限');
define('_AM_XNEWS_SUBMITFORM', '提交权限');
define('_AM_XNEWS_VIEWFORM', '阅读权限');
define('_AM_XNEWS_APPROVEFORM_DESC', '选择谁能审核');
define('_AM_XNEWS_SUBMITFORM_DESC', '选择谁能提交');
define('_AM_XNEWS_VIEWFORM_DESC', '选择谁能阅读新闻');
define('_AM_XNEWS_DELETE_SELFILES', '删除选定的文件');
define('_AM_XNEWS_TOPIC_PICTURE', '上传图片');
define('_AM_XNEWS_UPLOAD_WARNING', '<B>提醒！请将下列目录属性设为可写：%s</B>');

define('_AM_XNEWS_UPGRADECOMPLETE', '更新完成');
define('_AM_XNEWS_UPDATEMODULE', '更新模块模板和区块');
define('_AM_XNEWS_UPGRADEFAILED', '更新失败');
define('_AM_XNEWS_UPGRADE', '更新');
define('_AM_XNEWS_ADD_TOPIC', '增加一个分类');
define('_AM_XNEWS_ADD_TOPIC_ERROR', '错误，该分类已存在！');
define('_AM_XNEWS_ADD_TOPIC_ERROR1', '错误：不能选择此分类作为父分类！');
define('_AM_XNEWS_SUB_MENU', '将该分类作为子菜单发布');
define('_AM_XNEWS_SUB_MENU_YESNO', '子菜单');
define('_AM_XNEWS_HITS', '点击数');
define('_AM_XNEWS_CREATED', '已创建');

define('_AM_XNEWS_TOPIC_DESCR', '分类描述');
define('_AM_XNEWS_USERS_LIST', '用户列表');
define('_AM_XNEWS_PUBLISH_FRONTPAGE', '发布在首页？');
define('_AM_XNEWS_UPGRADEFAILED1', '不能创建「新闻附件」的数据表');
define('_AM_XNEWS_UPGRADEFAILED2', '不能改变分类标题的长度');
define('_AM_XNEWS_UPGRADEFAILED21', '不能在分类表中增加新的字段');
define('_AM_XNEWS_UPGRADEFAILED3', '不能创建「新闻投票」的数据表');
define('_AM_XNEWS_UPGRADEFAILED4', '不能在「新闻」数据表中创建「评分」和「投票」二个字段');
define('_AM_XNEWS_UPGRADEFAILED0', '请记住此信息，并尝试使用phpMyadmin和sql纠正NEWS模块的「sql」文件夹中定义的文件');
define('_AM_XNEWS_UPGR_ACCESS_ERROR', '错误，使用此升级脚本，您必须是此模块的管理员');
define('_AM_XNEWS_PRUNE_BEFORE', '删除在此之前发布的新闻：');
define('_AM_XNEWS_PRUNE_EXPIREDONLY', '仅删除过期的新闻');
define('_AM_XNEWS_PRUNE_CONFIRM', '警告，您将永久删除在 %s 之前发布的新闻 (此操作不可撤消)。共有 %s 篇新闻。<br>您是否确定？');
define('_AM_XNEWS_PRUNE_TOPICS', '仅限於以下分类');
define('_AM_XNEWS_PRUNENEWS', '删除新闻');
define('_AM_XNEWS_EXPORT_NEWS', '新闻导出（XML）');
define('_AM_XNEWS_EXPORT_NOTHING', '抱歉，没有生成任何导出内容，请检查您的设定');
define('_AM_XNEWS_PRUNE_DELETED', '%d 篇新闻已删除');
define('_AM_XNEWS_PERM_WARNING', '<h2>警告，您有3个表单，因此有3个提交按钮</h2>');
define('_AM_XNEWS_EXPORT_BETWEEN', '导出以下范围的新闻：');
define('_AM_XNEWS_EXPORT_AND', ' -- ');
define('_AM_XNEWS_EXPORT_PRUNE_DSC', '如果您未选择任何分类，则将选择所有分类<br> 否则仅为选择的分类');
define('_AM_XNEWS_EXPORT_INCTOPICS', '是否包括分类定义？');
define('_AM_XNEWS_EXPORT_ERROR', '当尝试创建%s文件时产生错误。操作终止。');
define('_AM_XNEWS_EXPORT_READY', "您导出的xml文件已可以下载。<br><a href='%s'>点击此链接下载</a>。<br>当您完成下载後，请勿忘记 <a href='%s'>删除</a>。");
define('_AM_XNEWS_RSS_URL', 'RSS feed 链接');
define('_AM_XNEWS_NEWSLETTER', '新闻快报');
define('_AM_XNEWS_NEWSLETTER_BETWEEN', '选择新闻发布的时间范围：');
define('_AM_XNEWS_NEWSLETTER_READY', "您的新闻快报文件已可以下载。<br><a href='%s'>点击此链接下载</a>。<br>当您完成下载後，请勿忘记 <a href='%s'>删除</a>。");
define('_AM_XNEWS_DELETED_OK', '文件删除成功');
define('_AM_XNEWS_DELETED_PB', '删除此文件时发生错误');
define('_AM_XNEWS_STATS0', '分类统计');
define('_AM_XNEWS_STATS', '统计');
define('_AM_XNEWS_STATS1', '作者数量');
define('_AM_XNEWS_STATS2', '总计');
define('_AM_XNEWS_STATS3', '新闻统计');
define('_AM_XNEWS_STATS4', '阅读最多的新闻');
define('_AM_XNEWS_STATS5', '阅读最少的新闻');
define('_AM_XNEWS_STATS6', '评分最高的新闻');
define('_AM_XNEWS_STATS7', '阅读最多的作者');
define('_AM_XNEWS_STATS8', '评分最高的作者');
define('_AM_XNEWS_STATS9', '投稿最多的作者');
define('_AM_XNEWS_STATS10', '作者统计');
define('_AM_XNEWS_STATS11', '新闻数目');
define('_AM_XNEWS_HELP', '帮助');
define('_AM_XNEWS_MODULEADMIN', '模块管理');
define('_AM_XNEWS_GENERALSET', '偏好设定');
define('_AM_XNEWS_GOTOMOD', '转至模块前台');
define('_AM_XNEWS_NOTHING', '抱歉，没有任何内容可以下载，请检查您的设定！');
define('_AM_XNEWS_NOTHING_PRUNE', '抱歉，没有任何新闻可以删除，请检查您的设定！');
define('_AM_XNEWS_TOPIC_COLOR', '分类颜色');
define('_AM_XNEWS_COLOR', '颜色');
define('_AM_XNEWS_REMOVE_BR', '转换HTML &lt;br&gt; 标签为换行？');
// Added in 1.3 RC2
define('_AM_XNEWS_PLEASE_UPGRADE', "<a href='upgrade.php'><font color='#FF0000'>请更新此模块！</font></a>");

// Added in verisn 1.50
define('_AM_XNEWS_NEWSLETTER_HEADER', '信头');
define('_AM_XNEWS_NEWSLETTER_FOOTER', '信尾');
define('_AM_XNEWS_NEWSLETTER_HTML_TAGS', '清除HTML标记');
define('_AM_XNEWS_VERIFY_TABLES', '保持表格');
define('_AM_XNEWS_METAGEN', 'Metagen');
define('_AM_XNEWS_METAGEN_DESC', 'Metagen是一个能够帮助你的页面更够更好的被搜索引擎收录的系统。<br>除了你自己输入meta关键字和meta的描述，模块会自动生成。');
define('_AM_XNEWS_BLACKLIST', '黑名单');
define('_AM_XNEWS_BLACKLIST_DESC', '此名单上的词不能用来生成meta关键字。');
define('_AM_XNEWS_BLACKLIST_ADD', '添加');
define('_AM_XNEWS_BLACKLIST_ADD_DSC', '输入添加到此名单上的词。<br>(每一行一个词)');
define('_AM_XNEWS_META_KEYWORDS_CNT', '自动生成meta关键字的最大数目');
define('_AM_XNEWS_META_KEYWORDS_ORDER', '关键字顺序');
define('_AM_XNEWS_META_KEYWORDS_INTEXT', '按照在文中出现的顺序生成');
define('_AM_XNEWS_META_KEYWORDS_FREQ1', '词频顺序');
define('_AM_XNEWS_META_KEYWORDS_FREQ2', '词频反向排序');

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
define('_AM_XNEWS_CLONER_NEWNAMEDESC',
       "This will also affect the creation of the new module folder. <br> Case sensitivity and spaces are ignored and will be auto corrected. <br> eg. new name = <b>Library</b> new dir  = <b>library</b>, <br> new name <b>My Library</b> new dir = <b>mylibrary</b>. <br><br> Start module is: <font color='#008400'><b> %s </b></font><br>");
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
define('_AM_XNEWS_CLONER_IMPORTNEWSDESC2', 'The import button only appears if x' . 'News module stories table is empty. <br>
                                         If you added story item before importing from <br>
                                         original News module you will have to uninstall/reinstall x' . 'News. <br>
                                         If you already imported original News Module data, leave as is.');
define('_AM_XNEWS_CLONER_IMPORTNEWSSUB', 'Import');
define('_AM_XNEWS_CLONER_NEWSIMPORTED', 'Original News module data correctly imported');

// Added in version 1.68 Beta
define('_AM_XNEWS_DESCRIPTION', '<H3>x' . 'News is a clonable news module</H3> 
							  where users can post news/comments. The module can be cloned to enable one only method for many different tasks. Other than usual news it can be used for info, links and more all with their own blocks, topics and settings.');

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
