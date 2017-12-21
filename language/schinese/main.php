<?php
// $Id: main.php,v 1.9 2004/07/26 17:51:25 hthouzard Exp $
//%%%%%%                File Name index.php             %%%%%
define('_MD_XNEWS_PRINTER', '可打印模式');
define('_MD_XNEWS_SENDSTORY', '转发给朋友');
define('_MD_XNEWS_READMORE', '阅读全文...');
define('_MD_XNEWS_COMMENTS', '发表评论');
define('_MD_XNEWS_ONECOMMENT', '1篇评论');
define('_MD_XNEWS_BYTESMORE', '%s字符 (含本文)');
define('_MD_XNEWS_NUMCOMMENTS', '%s篇评论');
define('_MD_XNEWS_MORERELEASES', '更多内容 ');

//%%%%%%                File Name submit.php            %%%%%
define('_MD_XNEWS_SUBMITNEWS', '发布新闻');
define('_MD_XNEWS_TITLE', '标题');
define('_MD_XNEWS_TOPIC', '新闻分类');
define('_MD_XNEWS_THESCOOP', '摘要');
define('_MD_XNEWS_NOTIFYPUBLISH', '若有人评论，发mail通知');
define('_MD_XNEWS_POST', '发布');
define('_MD_XNEWS_GO', '确定');
define('_MD_XNEWS_THANKS', '感谢您提供新闻。'); //submission of news article

define('_MD_XNEWS_NOTIFYSBJCT', '有人提供新的新闻'); // Notification mail subject
define('_MD_XNEWS_NOTIFYMSG', '有人到站上提供了新闻，请去看一看。'); // Notification mail message

//%%%%%%                File Name archive.php           %%%%%
define('_MD_XNEWS_NEWSARCHIVES', '按月归档');
define('_MD_XNEWS_ARTICLES', '新闻');
define('_MD_XNEWS_VIEWS', '阅读数');
define('_MD_XNEWS_DATE', '日期');
define('_MD_XNEWS_ACTIONS', '功能选项');
define('_MD_XNEWS_PRINTERFRIENDLY', '可打印模式');

define('_MD_XNEWS_THEREAREINTOTAL', '总计有%s篇新闻');

// %s is your site name
define('_MD_XNEWS_INTARTICLE', '不错的新闻来自於%s');
define('_MD_XNEWS_INTARTFOUND', '我在%s发现不错的新闻哦');

define('_MD_XNEWS_TOPICC', '新闻类别：');
define('_MD_XNEWS_URL', '链接 (URL)：');
define('_MD_XNEWS_NOSTORY', '抱歉，尚未选择新闻。');

//%%%%%%        File Name print.php     %%%%%

define('_MD_XNEWS_URLFORSTORY', '本篇新闻的链接网址是：');

// %s represents your site name
define('_MD_XNEWS_THISCOMESFROM', '本篇新闻来自：%s');

// Added by Herv?
define('_MD_XNEWS_ATTACHEDFILES', '附件：');
define('_MD_XNEWS_ATTACHEDLIB', '该新闻有附件');
define('_MD_XNEWS_NEWSSAMEAUTHORLINK', '该作者发布的其他新闻');
define('_MD_XNEWS_NO_TOPICS', '尚无分类，请在提交新闻前先创建至少一个分类');
define('_MD_XNEWS_PREVIOUS_ARTICLE', '上一篇');
define('_MD_XNEWS_NEXT_ARTICLE', '下一篇');
define('_MD_XNEWS_OTHER_ARTICLES', '其他新闻');

// Added by Herv?in version 1.3 for rating
define('_MD_XNEWS_RATETHISNEWS', '对此新闻评分');
define('_MD_XNEWS_RATEIT', '评分');
define('_MD_XNEWS_TOTALRATE', '总评分');
define('_MD_XNEWS_RATINGLTOH', '升序评分 (从低到高)');
define('_MD_XNEWS_RATINGHTOL', '降序评分 (从高到低)');
define('_MD_XNEWS_RATINGC', '评分: ');
define('_MD_XNEWS_RATINGSCALE', '分值范围为：1 - 10。1为最低，10为最高。');
define('_MD_XNEWS_BEOBJECTIVE', '请认真评分，若每篇新闻都为1或10，评分将毫无意义。');
define('_MD_XNEWS_DONOTVOTE', '请不要给自己的新闻评分。');
define('_MD_XNEWS_RATING', '评分');
define('_MD_XNEWS_VOTE', '投票');
define('_MD_XNEWS_NORATING', '尚未选择评分。');
define('_MD_XNEWS_USERAVG', '平均用户评分');
define('_MD_XNEWS_DLRATINGS', '新闻评分 (总票数: %s)');
define('_MD_XNEWS_ONEVOTE', '1 票');
define('_MD_XNEWS_NUMVOTES', '%u 票');                // Warning
define('_MD_XNEWS_CANTVOTEOWN', '您不能对自己的新闻投票。<br>所有投票将被记录和检查。');
define('_MD_XNEWS_VOTEDELETED', '投票数据已删除。');
define('_MD_XNEWS_VOTEONCE', '请勿对同一篇新闻多次投票。');
define('_MD_XNEWS_VOTEAPPRE', '感谢您的投票。');
define('_MD_XNEWS_THANKYOU', '感谢您在 %s 参加投票'); // %s is your site name
define('_MD_XNEWS_RSSFEED', 'RSS Feed');       // Warning, this text is included insided an Alt attribut (for a picture), so take care to the quotes
define('_MD_XNEWS_AUTHOR', '作者');
define('_MD_XNEWS_META_DESCRIPTION', 'Meta 描述');
define('_MD_XNEWS_META_KEYWORDS', 'Meta 关键词');
define('_MD_XNEWS_MAKEPDF', '创建PDF');
define('_MD_XNEWS_POSTEDON', '发布於：');
define('_MD_XNEWS_AUTHOR_ID', '作者ID');
define('_MD_XNEWS_POST_SORRY', '抱歉，尚无该分类或是您尚不具备在分类中发表的权限。如果您是管理员，请进入权限管理并设定「提交」权限。');

// Added in v 1.50
define('_MD_XNEWS_LINKS', '链接');
define('_MD_XNEWS_PAGE', '页');
define('_MD_XNEWS_BOOKMARK_ME', '在以下网站做书签');
define('_AM_XNEWS_TOTAL', '共 %u 篇文章');
define('_AM_XNEWS_WHOS_WHO', '作者列表');
define('_MD_XNEWS_LIST_OF_AUTHORS', '这是作者列表, 点击名字可看到他的文章');
define('_AM_XNEWS_TOPICS_DIRECTORY', '新闻目录');
define('_MD_XNEWS_PAGE_AUTO_SUMMARY', '页 %d : %s');

// Added in version 1.51
define('_MD_XNEWS_BOOKMARK_TO_BLINKLIST', '加入Blinklist');
define('_MD_XNEWS_BOOKMARK_TO_DELICIOUS', '加入del.icio.us');
define('_MD_XNEWS_BOOKMARK_TO_DIGG', '加入Digg');
define('_MD_XNEWS_BOOKMARK_TO_FARK', '加入Fark');
define('_MD_XNEWS_BOOKMARK_TO_FURL', '加入Furl');
define('_MD_XNEWS_BOOKMARK_TO_NEWSVINE', '加入Newsvine');
define('_MD_XNEWS_BOOKMARK_TO_REDDIT', '加入Reddit');
define('_MD_XNEWS_BOOKMARK_TO_SIMPY', '加入Simpy');
define('_MD_XNEWS_BOOKMARK_TO_SPURL', '加入Spurl');
define('_MD_XNEWS_BOOKMARK_TO_YAHOO', '加入Yahoo');

// Added in version 1.56
define('_MD_XNEWS_NOTYETSTORY', '很抱歉，所选的新闻尚未发布。请返回，然后再试一次.');
define('_MD_XNEWS_ON', '于');
define('_MD_XNEWS_READS', '次阅读');

// Added in version 1.68 BETA
define('_MD_XNEWS_SP', ':');
define('_MD_XNEWS_POSTED', 'Posted');

// Added in version 1.68 RC1
define('_MD_XNEWS_NO_COMMENT', 'No comment');
define('_MD_XNEWS_METASIZE', "Sorry, you may not add more than '+len+' characters into the text area box you just completed.");

// Added in version 1.68 RC3
define('_MD_XNEWS_SEO_TOPICS', 'topics');
define('_MD_XNEWS_SEO_ARTICLES', 'articles');
define('_MD_XNEWS_SEO_PRINT', 'print');
define('_MD_XNEWS_SEO_PDF', 'pdf');
