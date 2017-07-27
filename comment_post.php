<?php
require_once __DIR__ . '/header.php';

if ($xnews->getConfig('com_rule') == 0) {
    // Comments are deactivate
    ie();
}
if ($xnews->getConfig('com_anonpost') == 0 && !is_object($xoopsUser)) {
    // Anonymous users can't post
    die();
}

require_once XOOPS_ROOT_PATH . '/include/comment_post.php';
