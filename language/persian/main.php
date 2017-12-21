<?php
// $Id: main.php,v 1.9 2004/07/26 17:51:25 hthouzard Exp $
//%%%%%%        File Name index.php         %%%%%
define('_MD_XNEWS_PRINTER', 'صفحه مناسب چاپ');
define('_MD_XNEWS_SENDSTORY', 'پیشنهاد این صفحه');
define('_MD_XNEWS_READMORE', 'ادامه');
define('_MD_XNEWS_COMMENTS', 'نظر');
define('_MD_XNEWS_ONECOMMENT', '1 نظر');
define('_MD_XNEWS_BYTESMORE', '%s کلمه در ادامه متن');
define('_MD_XNEWS_NUMCOMMENTS', '%s نظر');
define('_MD_XNEWS_MORERELEASES', 'خبرهای دیگر از');

//%%%%%%        File Name submit.php        %%%%%
define('_MD_XNEWS_SUBMITNEWS', 'ارسال خبر برای صفحه اول');
define('_MD_XNEWS_TITLE', 'عنوان');
define('_MD_XNEWS_TOPIC', 'دسته');
define('_MD_XNEWS_THESCOOP', 'متن');
define('_MD_XNEWS_NOTIFYPUBLISH', 'هنگام انتشار به من خبر بده');
define('_MD_XNEWS_POST', 'بفرست');
define('_MD_XNEWS_GO', 'برو!');
define('_MD_XNEWS_THANKS', 'از نوشته شما سپاس گذاریم.'); //submission of news article

define('_MD_XNEWS_NOTIFYSBJCT', 'نوشته برای سایت من'); // Notification mail subject
define('_MD_XNEWS_NOTIFYMSG', 'یک نوشته جدید برای صفحه اول سایت اومده.'); // Notification mail message

//%%%%%%        File Name archive.php        %%%%%
define('_MD_XNEWS_NEWSARCHIVES', 'آرشیو اخبار');
define('_MD_XNEWS_ARTICLES', 'نوشته‌ها');
define('_MD_XNEWS_VIEWS', 'بازدید');
define('_MD_XNEWS_DATE', 'تاریخ');
define('_MD_XNEWS_ACTIONS', 'کار');
define('_MD_XNEWS_PRINTERFRIENDLY', 'صفحه مناسب چاپ');

define('_MD_XNEWS_THEREAREINTOTAL', ' %s نوشته در کل');

// %s is your site name
define('_MD_XNEWS_INTARTICLE', 'نوشته جالب در سایت %s');
define('_MD_XNEWS_INTARTFOUND', 'این یک خبر جالب است که در %s پیدا کردم');

define('_MD_XNEWS_TOPICC', 'عنوان:');
define('_MD_XNEWS_URL', 'آدرس:');
define('_MD_XNEWS_NOSTORY', 'متاسفانه نوشته انتخاب شده وجود ندارد.');

//%%%%%%    File Name print.php     %%%%%

define('_MD_XNEWS_URLFORSTORY', 'نشانی این صفحه :');

// %s represents your site name
define('_MD_XNEWS_THISCOMESFROM', 'نوشته‌ای از %s');

// Added by Hervé
define('_MD_XNEWS_ATTACHEDFILES', 'فایل‌های پیوست‌شده:');
define('_MD_XNEWS_ATTACHEDLIB', 'این خبر دارای فایل پیوست شده است');
define('_MD_XNEWS_NEWSSAMEAUTHORLINK', 'خبرهای فرستاده شده توسط این شخص');
define('_MD_XNEWS_NEWS_NO_TOPICS', 'متاسفانه سرفصلی برای ارسال خبر وجود ندارد. اول باید یک سرفصل بسازید');
define('_MD_XNEWS_PREVIOUS_ARTICLE', 'خبر قبلی');
define('_MD_XNEWS_NEXT_ARTICLE', 'خبر بعدی');
define('_MD_XNEWS_OTHER_ARTICLES', 'سایر خبرها');

// Added by Hervé in version 1.3 for rating
define('_MD_XNEWS_RATETHISNEWS', 'ارزش‌گذاری این خبر');
define('_MD_XNEWS_RATEIT', 'رای دهید!');
define('_MD_XNEWS_TOTALRATE', 'تمام رای‌ها');
define('_MD_XNEWS_RATINGLTOH', 'ارزش‌گذاری (از کم به زیاد)');
define('_MD_XNEWS_RATINGHTOL', 'ارزش‌گذاری (از زیاد به کم)');
define('_MD_XNEWS_RATINGC', 'ارزش: ');
define('_MD_XNEWS_RATINGSCALE', 'از 1 تا 10 می‌توانید رای دهید 1 یعنی به درد نخور و 10 یعنی عالی');
define('_MD_XNEWS_BEOBJECTIVE', 'لطفا منصف باشید. اگر یکی 1 یا 10 بگیرد این ارزش‌یابی مفید نخواهد بود.');
define('_MD_XNEWS_DONOTVOTE', 'برای نوشته‌های خودتان رای ندهید.');
define('_MD_XNEWS_RATING', 'ارزش');
define('_MD_XNEWS_VOTE', 'رای');
define('_MD_XNEWS_NORATING', 'هیچ عددی انتخاب نشده است');
define('_MD_XNEWS_USERAVG', 'میانگین ارزش این کاربر');
define('_MD_XNEWS_DLRATINGS', 'ارزش این خبر (کل رای‌ها: %s)');
define('_MD_XNEWS_ONEVOTE', '1 رای');
define('_MD_XNEWS_NUMVOTES', '%u رای');        // Warning
define('_MD_XNEWS_CANTVOTEOWN', 'شما نمی‌توانید به نوشته‌های خود رای دهید.<br>تمام رای‌ها ذخیره و بازبینی می‌شوند');
define('_MD_XNEWS_VOTEDELETED', 'داده‌های ارزش‌گذاری حذف شد.');
define('_MD_XNEWS_VOTEONCE', 'لطفا به یک نوشته بیش از یک بار رای ندهید');
define('_MD_XNEWS_VOTEAPPRE', 'با تشکر از رای شما');
define('_MD_XNEWS_THANKYOU', 'با تشکر از وقتی که برای رای دادن در سایت %s گذاشتید'); // %s is your site name
define('_MD_XNEWS_RSSFEED', 'RSS Feed');    // Warning, this text is included insided an Alt attribut (for a picture), so take care to the quotes
define('_MD_XNEWS_AUTHOR', 'نویسنده');
define('_MD_XNEWS_META_DESCRIPTION', 'شرح Meta (برای موتورهای جستجو)');
define('_MD_XNEWS_META_KEYWORDS', 'کلمات کلیدی Meta (برای موتورهای جستجو)');
define('_MD_XNEWS_MAKEPDF', 'از این خبر یک pdf بساز');
define('_MD_POSTEDON', 'ارسال شده در تاریخ: ');
define('_MD_XNEWS_AUTHOR_ID', 'شناسه نویسنده');
define('_MD_XNEWS_POST_SORRY', "متاسفانه یا سرفصلی وجود ندارد و یا شما دسترسی به هیچ سرفصلی برای ارسال خبر ندارید. اگر شما وب‌مستر هستید، به صفحه دسترسی‌ها بروید و دسترسی‌ها برای 'ارسال' را تنظیم کنید.");

// Added in v 1.50
define('_MD_XNEWS_LINKS', 'لینک‌ها');
define('_MD_XNEWS_PAGE', 'صفحه‌ها');
define('_MD_XNEWS_BOOKMARK_ME', 'این خبر را در این سایت‌ها بوک مارک کن');
define('_AM_XNEWS_NEWS_TOTAL', 'تعداد کل %u خبر');
define('_AM_XNEWS_NEWS_WHOS_WHO', 'اسامی ارسال‌کنندگان خبر');
define('_MD_XNEWS_NEWS_LIST_OF_AUTHORS', 'در اینجا فهرستی از تمام نویسندگان خبر این سایت را مشاهده می‌کنید، روی نام نویسنده کلیک کنید تا فهرست خبرهای ارسال شده را ببینید');
define('_AM_XNEWS_NEWS_TOPICS_DIRECTORY', 'شاخه سرفصل‌ها');
define('_MD_XNEWS_PAGE_AUTO_SUMMARY', 'صفحه %d : %s');

// Added in version 1.51
define('_MD_XNEWS_BOOKMARK_TO_BLINKLIST', 'ارسال خبر به Blinklist');
define('_MD_XNEWS_BOOKMARK_TO_DELICIOUS', 'ارسال خبر به del.icio.us');
define('_MD_XNEWS_BOOKMARK_TO_DIGG', 'ارسال خبر به Digg');
define('_MD_XNEWS_BOOKMARK_TO_FARK', 'ارسال خبر به Fark');
define('_MD_XNEWS_BOOKMARK_TO_FURL', 'ارسال خبر به Furl');
define('_MD_XNEWS_BOOKMARK_TO_NEWSVINE', 'ارسال خبر به Newsvine');
define('_MD_XNEWS_BOOKMARK_TO_REDDIT', 'ارسال خبر به Reddit');
define('_MD_XNEWS_BOOKMARK_TO_SIMPY', 'ارسال خبر به Simpy');
define('_MD_XNEWS_BOOKMARK_TO_SPURL', 'ارسال خبر به Spurl');
define('_MD_XNEWS_BOOKMARK_TO_YAHOO', 'ارسال خبر به یاهو');

// Added in version 1.56
define('_MD_XNEWS_NOTYETSTORY', 'متاسفانه خبر انتخاب‌شده شما هنوز منتشر نشده‌است. لطفا بعدا مراجعه و امتحان کنید.');
define('_MD_XNEWS_SELECT_IMAGE', 'انتخاب تصویر برای اضافه شدن به خبر');
define('_MD_XNEWS_CURENT_PICTURE', 'تصویر فعلی');

// Added in version 1.68
define('_MD_XNEWS_SP', ':');
define('_MD_XNEWS_POSTED', 'فرستاده شده در تاریخ');

// Added in version 1.68 RC1
define('_MD_XNEWS_NO_COMMENT', 'بدون نظر');
define('_MD_XNEWS_METASIZE', "متاسفانه شما نمیتوانید مطالب را به صورت '+len+' در محل ورود متن وارد کنید باید آنها را به طور کامل وارد کنید.");

// Added in version 1.68 RC3
define('_MD_XNEWS_SEO_TOPICS', 'شاخه ها');
define('_MD_XNEWS_SEO_ARTICLES', 'مطالب');
define('_MD_XNEWS_SEO_PRINT', 'چاپ');
define('_MD_XNEWS_SEO_PDF', 'pdf');
