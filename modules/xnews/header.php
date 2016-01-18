<?php
include_once dirname(dirname(__DIR__)) . '/mainfile.php';
include_once __DIR__ . '/include/common.php';

//SEO activity
if ($xnews->getConfig('seo_enable') != 0) {
include_once XNEWS_MODULE_PATH . '/seo.php';
}
