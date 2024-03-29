<?php
// $Id: admin.php,v 1.18 2004/07/26 17:51:25 hthouzard Exp $
//%%%%%%    Admin Module Name  Articles     %%%%%
define('_AM_XNEWS_DBUPDATED', 'پایگاه‌داده با موفقیت به‌روز شد!');
define('_AM_XNEWS_CONFIG', 'تنظیمات اخبار');
define('_AM_XNEWS_AUTOARTICLES', 'خبر‌های خودکار');
define('_AM_XNEWS_STORYID', 'شناسه خبر');
define('_AM_XNEWS_TITLE', 'عنوان');
define('_AM_XNEWS_TOPIC', 'سرفصل');
define('_AM_XNEWS_POSTER', 'فرستنده');
define('_AM_XNEWS_PROGRAMMED', 'زمان/تاریخ برنامه‌ریزی شده');
define('_AM_XNEWS_ACTION', 'عمل');
define('_AM_XNEWS_EDIT', 'ویرایش');
define('_AM_XNEWS_DELETE', 'حذف');
define('_AM_XNEWS_LAST10ARTS', 'آخرین %d خبر قرار‌گرفته در سایت');
define('_AM_XNEWS_PUBLISHED', 'تاریخ قرار گرفتن'); // Published Date
define('_AM_XNEWS_GO', 'برو!');
define('_AM_XNEWS_EDITARTICLE', 'ویرایش خبر');
define('_AM_XNEWS_POSTNEWARTICLE', 'نوشتن خبر جدید');
define('_AM_XNEWS_ARTPUBLISHED', 'خبر شما در سایت قرار گرفت!');
define('_AM_XNEWS_HELLO', 'سلام %s');
define('_AM_XNEWS_YOURARTPUB', 'خبری که برای سایت ارسال کرده‌بودید در سایت قرار گرفت');
define('_AM_XNEWS_TITLEC', 'عنوان: ');
define('_AM_XNEWS_URLC', 'آدرس: ');
define('_AM_XNEWS_PUBLISHEDC', 'تاریخ قرار‌گرفتن: ');
define('_AM_XNEWS_RUSUREDEL', 'آیا اطمینان دارید که می‌خواهید این خبر و تمام نظرهای آن را حذف کنید؟');
define('_AM_XNEWS_YES', 'بله');
define('_AM_XNEWS_NO', 'نه');
define('_AM_XNEWS_INTROTEXT', 'متن وارد شده');
define('_AM_XNEWS_EXTEXT', 'متن اضافی');
define('_AM_XNEWS_ALLOWEDHTML', 'اجازه برای استفاده از کدهای HTML: ');
define('_AM_XNEWS_DISAMILEY', 'غیر‌فعال کردن لبخندکها');
define('_AM_XNEWS_DISHTML', 'غیر فعال‌کردن HTML');
define('_AM_XNEWS_APPROVE', 'تایید');
define('_AM_XNEWS_MOVETOTOP', 'این خبر را بالا ببر');
define('_AM_XNEWS_CHANGEDATETIME', 'تغییر زمان/تاریخ قرار گرفتن خبر در سایت');
define('_AM_XNEWS_NOWSETTIME', 'در حال حاضر در این تاریخ قرار دارد: %s'); // %s is datetime of publish
define('_AM_XNEWS_CURRENTTIME', 'زمان در حال حاضر عبارت است از: %s');  // %s is the current datetime
define('_AM_XNEWS_SETDATETIME', 'تعیین زمان/تاریخ قرار گرفتن خبر در سایت');
define('_AM_XNEWS_MONTHC', 'ماه:');
define('_AM_XNEWS_DAYC', 'روز:');
define('_AM_XNEWS_YEARC', 'سال:');
define('_AM_XNEWS_TIMEC', 'زمان:');
define('_AM_XNEWS_PREVIEW', 'پیش‌نمایش');
define('_AM_XNEWS_SAVE', 'ذخیره');
define('_AM_XNEWS_PUBINHOME', 'قرار گرفتن خبر در صفحه اصلی؟');
define('_AM_XNEWS_ADD', 'اضافه');

//%%%%%%    Admin Module Name  Topics   %%%%%

define('_AM_XNEWS_ADDMTOPIC', 'اضافه‌کردن یک سرفصل اصلی');
define('_AM_XNEWS_TOPICNAME', 'عنوان سرفصل');
// Warning, changed from 40 to 255 characters.
define('_AM_XNEWS_MAX40CHAR', '(حداکثر: 255 نویسه)');
define('_AM_XNEWS_TOPICIMG', 'تصویر سرفصل');
define('_AM_XNEWS_IMGNAEXLOC', 'نام تصویر + پیشوند در %s قرار دارد');
define('_AM_XNEWS_FEXAMPLE', 'به عنوان مثال: games.gif');
define('_AM_XNEWS_ADDSUBTOPIC', 'اضافه‌کردن یک زیر سرفصل');
define('_AM_XNEWS_IN', 'در');
define('_AM_XNEWS_MODIFYTOPIC', 'اصلاح سرفصل');
define('_AM_XNEWS_MODIFY', 'اصلاح');
define('_AM_XNEWS_PARENTTOPIC', 'سرفصل والد');
define('_AM_XNEWS_SAVECHANGE', 'ذخیره تغییرات');
define('_AM_XNEWS_DEL', 'حذف');
define('_AM_XNEWS_CANCEL', 'انصراف');
define('_AM_XNEWS_WAYSYWTDTTAL', 'هشدار: آیا مطمئن هستید که می‌خواهید این سر فصل را با تمام خبرها و نظرهای موجود در آن حذف کنید؟');

// Added in Beta6
define('_AM_XNEWS_TOPICSMNGR', 'مدیریت سرفصل‌ها');
define('_AM_XNEWS_PEARTICLES', 'نوشتن/ویرایش خبرها');
define('_AM_XNEWS_NEWSUB', 'ارسال‌های جدید');
define('_AM_XNEWS_POSTED', 'فرستاده شده در');
define('_AM_XNEWS_GENERALCONF', 'تنظیمات اصلی');

// Added in RC2
define('_AM_XNEWS_TOPICDISPLAY', 'تصویر سرفصل نشان داده‌شود؟');
define('_AM_XNEWS_TOPICALIGN', 'موقعیت');
define('_AM_XNEWS_RIGHT', 'راست');
define('_AM_XNEWS_LEFT', 'چپ');

define('_AM_XNEWS_EXPARTS', 'خبرهای منقضی شده');
define('_AM_XNEWS_EXPIRED', 'منقضی شده در');
define('_AM_XNEWS_CHANGEEXPDATETIME', 'تغییر زمان/تاریخ منقضی شدن خبر در سایت');
define('_AM_XNEWS_SETEXPDATETIME', 'تعیین زمان/تاریخ منقضی شدن خبر در سایت');
define('_AM_XNEWS_NOWSETEXPTIME', 'در حال حاضر در این تاریخ قرار دارد: %s');

// Added in RC3
define('_AM_XNEWS_ERRORTOPICNAME', 'شما باید یک نام برای سرفصل وارد کنید!');
define('_AM_XNEWS_EMPTYNODELETE', 'چیزی برای حذف وجود ندارد!');

// Added 240304 (Mithrandir)
define('_AM_XNEWS_GROUPPERM', 'دسترسی برای دیدن/ارسال/تایید اخبار');
define('_AM_XNEWS_SELFILE', 'یک فایل را برای بارگذاری انتخاب کنید');

// Added by Hervé
define('_AM_XNEWS_UPLOAD_DBERROR_SAVE', 'خطا در هنگام اتصال فایل به خبر');
define('_AM_XNEWS_UPLOAD_ERROR', 'خطا در هنگام بارگذاری فایل');
define('_AM_XNEWS_UPLOAD_ATTACHFILE', 'فایل(ها)ی متصل شده');
define('_AM_XNEWS_APPROVEFORM', 'دسترسی برای تایید اخبار');
define('_AM_XNEWS_SUBMITFORM', 'دسترسی برای ارسال اخبار');
define('_AM_XNEWS_VIEWFORM', 'دسترسی برای دیدن اخبار');
define('_AM_XNEWS_APPROVEFORM_DESC', 'انتخاب کنید چه گروه‌هایی مجاز به تایید اخبار باشند');
define('_AM_XNEWS_SUBMITFORM_DESC', 'انتخاب کنید چه گروه‌هایی مجاز به ارسال اخبار باشند');
define('_AM_XNEWS_VIEWFORM_DESC', 'انتخاب کنید چه گروه‌هایی مجاز به دیدن اخبار باشند');
define('_AM_XNEWS_DELETE_SELFILES', 'حذف فایل‌های انتخاب شده');
define('_AM_XNEWS_TOPIC_PICTURE', 'بارگذاری تصویر');
define('_AM_XNEWS_UPLOAD_WARNING', '<B>هشدار، فراموش نکنید که برای شاخه مقابل دسترسی برای نوشتن بدهید : %s</B>');

define('_AM_XNEWS_UPGRADECOMPLETE', 'ارتقا کامل شد');
define('_AM_XNEWS_UPDATEMODULE', 'تمپلیت‌های ماژول و بلاک را به روز کنید');
define('_AM_XNEWS_UPGRADEFAILED', 'ارتقا با مشکل روبرو شد');
define('_AM_XNEWS_UPGRADE', 'ارتقا');
define('_AM_XNEWS_ADD_TOPIC', 'اضافه‌کردن سر فصل');
define('_AM_XNEWS_ADD_TOPIC_ERROR', 'خطا: سرفصل در حال حاضر وجود دارد!');
define('_AM_XNEWS_ADD_TOPIC_ERROR1', 'خطا: امکان اینکه این سرفصل را به عنوان سرفصل والد قرار داد وجود ندارد');
define('_AM_XNEWS_SUB_MENU', 'قرار دادن این سرفصل به عنوان یک زیر منو در منوی اصلی');
define('_AM_XNEWS_SUB_MENU_YESNO', 'زیرمنو؟');
define('_AM_XNEWS_HITS', 'بازدید');
define('_AM_XNEWS_CREATED', 'ساخته‌شده در');

define('_AM_XNEWS_TOPIC_DESCR', 'شرح سرفصل');
define('_AM_XNEWS_USERS_LIST', 'فهرست کاربران');
define('_AM_XNEWS_PUBLISH_FRONTPAGE', 'در صفحه اصلی قرار گیرد؟');
define('_AM_XNEWS_UPGRADEFAILED1', 'امکان ایجاد جدول stories_files وجود ندارد');
define('_AM_XNEWS_UPGRADEFAILED2', 'امکان تغییر طول عنوان سرفصل وجود ندارد');
define('_AM_XNEWS_UPGRADEFAILED21', 'امکان اضافه‌کردن فیلدهای جدید به جدول سرفصل‌ها وجود ندارد');
define('_AM_XNEWS_UPGRADEFAILED3', 'امکان ایجاد جدول stories_votedata وجود ندارد');
define('_AM_XNEWS_UPGRADEFAILED4', "امکان ایجاد دو فیلد 'rating' و 'votes' برای جدول 'story' وجود ندارد");
define('_AM_XNEWS_UPGRADEFAILED0', 'لطفا به پیغام‌ها توجه کنید و سعی کنید ایرادات را با رفتن به phpMyadmin و sql بر طرف کنید');
define('_AM_XNEWS_UPGR_ACCESS_ERROR', 'خطا در اجرای فایل به‌روز رسانی! شما باید مدیر ماژول باشید تا ارتقا انجام شود');
define('_AM_XNEWS_PRUNE_BEFORE', 'هرس کردن خبرهایی که قبل از این تاریخ در سایت قرار گرفته‌اند');
define('_AM_XNEWS_PRUNE_EXPIREDONLY', 'فقط خبرهایی را حذف کن که منقضی شده‌اند');
define('_AM_XNEWS_PRUNE_CONFIRM', 'هشدار، شما می‌خواهید اخباری را که قبل از %s منتشر شده‌اند پاک کنید (البته این کار غیر ممکن نیست). این عمل اخبار %s را نمایان می کند.<br>آیا اطمینان دارید؟');
define('_AM_XNEWS_PRUNE_TOPICS', 'محدود به سرفصل‌های زیر');
define('_AM_XNEWS_PRUNENEWS', 'هرس کردن خبرها');
define('_AM_XNEWS_EXPORT_NEWS', 'خروجی اخبار با فرمت xml');
define('_AM_XNEWS_EXPORT_NOTHING', 'متاسفانه چیزی برای خارج کردن وجود ندارد درخواست خود را بررسی کنید');
define('_AM_XNEWS_PRUNE_DELETED', '%d خبر حذف شد');
define('_AM_XNEWS_PERM_WARNING', '<h2>هشدار، شما سه فرم در پیش رو دارید در نتیجه سه دکمه برای ارسال دارید</h2>');
define('_AM_XNEWS_EXPORT_BETWEEN', 'خروجی گرفتن از خبرهای قرار گرفته در سایت بین تاریخ');
define('_AM_XNEWS_EXPORT_AND', ' و ');
define('_AM_XNEWS_EXPORT_PRUNE_DSC', 'اگر هیچکدام را انتخاب نکنید همه سرفصل‌ها در نظر گرفته می‌شوند<br> وگرنه فقط سرفصل‌های انتخاب شده در نظر گرفته می‌شوند');
define('_AM_XNEWS_EXPORT_INCTOPICS', 'تعریف‌های سرفصل‌ها را هم شامل شود؟');
define('_AM_XNEWS_EXPORT_ERROR', 'خطا در هنگام ایجاد فایل %s. عمل متوقف شد.');
define('_AM_XNEWS_EXPORT_READY', "فایل خروجی به صورت xml برای دانلود آماده است <br><a href='%s'>بر روی این لینک کلید کنید تا دانلود آغاز شود</a>.<br>فراموش نکنید که بعد از اتمام دانلود <a href='%s'>با کلیک بر روی این لینک آن را حذف کنید</a>");
define('_AM_XNEWS_RSS_URL', 'آدرس برای RSS feed');
define('_AM_XNEWS_NEWSLETTER', 'خبرنامه');
define('_AM_XNEWS_NEWSLETTER_BETWEEN', 'انتخاب خبرهای قرار گرفته در سایت بین تاریخ');
define('_AM_XNEWS_NEWSLETTER_READY', "فایل خبرنامه برای دانلود آماده است <br><a href='%s'>بر روی این لینک کلید کنید تا دانلود آغاز شود</a>.<br>فراموش نکنید که بعد از اتمام دانلود <a href='%s'>با کلیک بر روی این لینک آن را حذف کنید</a>");
define('_AM_XNEWS_DELETED_OK', 'فایل با موفقیت حذف شد');
define('_AM_XNEWS_DELETED_PB', 'مشکلی در هنگام حذف فایل رخ داد');
define('_AM_XNEWS_STATS0', 'آمار سرفصل‌ها');
define('_AM_XNEWS_STATS', 'آمار');
define('_AM_XNEWS_STATS1', 'نویسنده تک خبر');
define('_AM_XNEWS_STATS2', 'همه');
define('_AM_XNEWS_STATS3', 'آمار خبرها');
define('_AM_XNEWS_STATS4', 'خبرهای بیشتر خوانده شده');
define('_AM_XNEWS_STATS5', 'خبرهای کمتر خوانده شده');
define('_AM_XNEWS_STATS6', 'خبرهای با ارزش بیشتر');
define('_AM_XNEWS_STATS7', 'نویسنده‌هایی که خبرهایشان بیشتر از همه خوانده شده است');
define('_AM_XNEWS_STATS8', 'نویسنده‌هایی که خبرهایشان بیشترین ارزش را کسب کرده است');
define('_AM_XNEWS_STATS9', 'اعضایی که بالاترین همکاری را در ارسال خبر دارند');
define('_AM_XNEWS_STATS10', 'آمار نویسنده‌ها');
define('_AM_XNEWS_STATS11', 'تعداد خبر فرستاده شده');
define('_AM_XNEWS_HELP', 'کمک');
define('_AM_XNEWS_MODULEADMIN', 'مدیریت ماژول');
define('_AM_XNEWS_GENERALSET', 'تنظیمات ماژول');
define('_AM_XNEWS_GOTOMOD', 'برو به ماژول');
define('_AM_XNEWS_NOTHING', 'متاسفانه چزی برای دانلود وجود ندارد مسیر خود را بررسی کنید');
define('_AM_XNEWS_NOTHING_PRUNE', 'متاسفانه خبری برای هرس‌شدن وجود ندارد درخواست خود را بررسی کنید');
define('_AM_XNEWS_TOPIC_COLOR', 'رنگ سرفصل');
define('_AM_XNEWS_COLOR', 'رنگ');
define('_AM_XNEWS_REMOVE_BR', 'تبدیل کدهای &lt;br&gt; به خط جدید؟');
// Added in 1.3 RC2
define('_AM_XNEWS_PLEASE_UPGRADE', "<a href='upgrade.php'><font color='#FF0000'>لطفا در صورتی که ورژن ماژول اخبار شما 1.1 یا 1.2.1 است با کلیک بر روی این لینک ماژول را ارتقا دهید !</font></a>");

// Added in verisn 1.50
define('_AM_XNEWS_NEWSLETTER_HEADER', 'سر صفحه');
define('_AM_XNEWS_NEWSLETTER_FOOTER', 'پا صفحه');
define('_AM_XNEWS_NEWSLETTER_HTML_TAGS', 'کدهای html  حذف شوند؟');
define('_AM_XNEWS_VERIFY_TABLES', 'تعمیر جدول‌ها');
define('_AM_XNEWS_METAGEN', 'موتور متا');
define('_AM_XNEWS_METAGEN_DESC', 'موتورمتا Metagen  سیستمی است که باعث بهتر ایندکس شدن لینک‌های خبری شما در موتورهای جستجو میشود<br>اگر کلمات کلیدی و شرح مطلب را خودتان ننویسید ماژول به طور اتوماتیک آنها را می‌نویسد.');
define('_AM_XNEWS_BLACKLIST', 'لیست سیاه');
define('_AM_XNEWS_BLACKLIST_DESC', 'کلمات نوشته شده در این لیست در ساختن کلمات موتور متا استفاده نمی‌شوند');
define('_AM_XNEWS_BLACKLIST_ADD', 'اضافه‌کردن');
define('_AM_XNEWS_BLACKLIST_ADD_DSC', 'کلمات را برای اضافه شدن وارد کنید<br>(هر خط یک کلمه)');
define('_AM_XNEWS_META_KEYWORDS_CNT', 'حداکثر تعداد کلمات‌کلیدی برای ساخته‌شدن توسط موتور متا به صورت اتوماتیک');
define('_AM_XNEWS_META_KEYWORDS_ORDER', 'چینش کلمات‌کلیدی');
define('_AM_XNEWS_META_KEYWORDS_INTEXT', 'ساخته شدن با ترتیبی که در متن ظاهر شده‌اند');
define('_AM_XNEWS_META_KEYWORDS_FREQ1', 'به ترتیبی که بیشتر استفاده شده‌اند');
define('_AM_XNEWS_META_KEYWORDS_FREQ2', 'به ترتیبی که کمتر استفاده شده‌اند');

// Added in version 1.67 Beta
define('_AM_XNEWS_SUBPREFIX', 'زیر-پیشوند');

define('_AM_XNEWS_CLONER', 'تکثیر');
define('_AM_XNEWS_CLONER_CLONES', 'کپی ها');
define('_AM_XNEWS_CLONER_ADD', 'اضافه کردن کپی');
define('_AM_XNEWS_CLONER_ID', 'ID');
define('_AM_XNEWS_CLONER_NAME', 'اخبار');
define('_AM_XNEWS_CLONER_DIRFOL', 'مسیر/پوشه');
define('_AM_XNEWS_CLONER_VERSION', 'نسخه');

define('_AM_XNEWS_CLONER_NEWNAME', 'نام ماژول جدید');
define(
    '_AM_XNEWS_CLONER_NEWNAMEDESC',
    "این قسمت یک پوشه جدید برای ماژول ایجاد میکند. <br> حساسیت ها و نام های نا صحیح به طور خودکار اصلاح میشود. <br> به طور مثال. نام جدید = <b>Library</b> شاخه جدید  = <b>library</b>, <br> نام جدید <b>My Library</b> شاخه جدید = <b>mylibrary</b>. <br><br> نام ماژول فعلی: <font color='#008400'><b> %s </b></font><br>"
);
define('_AM_XNEWS_CLONER_NEWNAMELABEL', 'ماژول جدید:');

define('_AM_XNEWS_CLONER_DIREXISTS', "مسیر/شاخه '%s' هم اکنون موجود است !!");
define('_AM_XNEWS_CLONER_CREATED', "ماژول '%s' هم اکنون تکثیر شد !!");
define('_AM_XNEWS_CLONER_UPRADED', "ماژول '%s' هم اکنون به روز شد!!");
define('_AM_XNEWS_CLONER_NOMODULEID', 'شماره ماژول تنظیم نشده است');

define('_AM_XNEWS_CLONER_UPDATE', 'به روز کردن');
define('_AM_XNEWS_CLONER_INSTALL', 'نصب');
define('_AM_XNEWS_CLONER_UNINSTALL', 'حذف');
define('_AM_XNEWS_CLONER_ACTION_INSTALL', 'نصب/حذف');

define('_AM_XNEWS_CLONER_IMPORTNEWS', 'وارد کردن اطلاعات ماژول اخبار');
define('_AM_XNEWS_CLONER_IMPORTNEWSDESC1', 'ماژول اخبار در دسترس است ! اطلاعات آن منتقل شود؟');
define(
    '_AM_XNEWS_CLONER_IMPORTNEWSDESC2',
    'این گزینه زمانی فعال است که جدول stories این ماژول خالی باشد. <br>
                                         اگر شما قبل از وارد کردن اطلاعات خبری در این ماژول ارسال کرده باشید<br>
                                          باید ابتدا این ماژول رو از نصب خارج کرده و مجدد نصب کنید. <br>
                                         اگر شما هم اکنون اطلاعات را از ماژول اخبار منتقل کرده اید از این بخش خارج شود.'
);
define('_AM_XNEWS_CLONER_IMPORTNEWSSUB', 'وارد کردن');
define('_AM_XNEWS_CLONER_NEWSIMPORTED', 'اطلاعات ماژول اصلی اخبار هم اکنون منتقل شذ');

// Added in version 1.68 Beta
define(
    '_AM_XNEWS_DESCRIPTION',
    '<H3>ماژول xNews نسخه تکثیر شونده ماژول News</H3>
                              کاربران میتوانند خبر یا نظر ارسال کنند . این ماژول میتواند تکثیر شود و با یک روش امکان استفاده های متفاوت را فراهم کند . همچنین علاوه بر اخبار برای استفاده های دیگر هم مناسب است . و میتوانید آن را با استفاده از عنوان ها و بلاک ها و تنظیمات  شخصی سازی کنید. '
);

// Added in version 1.68 RC1
define('_AM_XNEWS_CLONER_CLONEDELETED', "کلون '%s' با موفقیت حذف شد .");
define('_AM_XNEWS_CLONER_CLONEDELETEDERR', "کلون '%s' حذف نشد . لطفا دسترسی ها را برسی کنید");
define('_AM_XNEWS_CLONER_CLONEUPGRADED', 'به روز شده');
define('_AM_XNEWS_CLONER_UPGRADEFORCE', 'آپگرید اجباری');
define('_AM_XNEWS_CLONER_CLONEDELETION', 'حذف کلون');
define('_AM_XNEWS_CLONER_SUREDELETE', "آیا اطمینان دارید که میخواهید کلون <font color='#000000'>'%s'</font> را حذف کنید؟<br>");
define('_AM_XNEWS_CLONER_CLONEID', 'ID کلون تنظیم نشده است !');

// Added in version 1.68 RC2
define('_AM_XNEWS_INDEX', 'صفحه اصلی مدیریت');

// Added in version 1.68 RC3
define('_AM_XNEWS_DOLINEBREAK', 'فعال کردن خط شکسته');

define('_AM_XNEWS_TOPICS', 'سرفصل ها');
