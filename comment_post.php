<?php
require_once __DIR__ . '/header.php';

if (0 == $xnews->getConfig('com_rule')) {
    // Comments are deactivate
    ie();
}
if (0 == $xnews->getConfig('com_anonpost') && !is_object($xoopsUser)) {
    // Anonymous users can't post
    die();
}

require_once XOOPS_ROOT_PATH . '/include/comment_post.php';
