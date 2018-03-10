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

require_once __DIR__ . '/header.php';

if (0 == $helper->getConfig('com_rule')) {
    // Comments are deactivated
    exit();
}
if (0 == $helper->getConfig('com_anonpost') && !is_object($xoopsUser)) {
    // Anonymous users can't post
    exit();
}

require_once XOOPS_ROOT_PATH . '/include/comment_post.php';
