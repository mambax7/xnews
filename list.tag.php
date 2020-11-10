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
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

/*
 * Created on 03/12/2008
 *
 * This page will display a list of articles which belong to a tag
 *
 * @package News
 * @author Hervé Thouzard of Instant Zero
 * @copyright (c) Instant Zero - http://www.instant-zero.com
 */
require_once __DIR__ . '/header.php';

if (!$helper->getConfig('tags')) {
    redirect_header('index.php', 3, _ERRORS);
}
require_once XOOPS_ROOT_PATH . '/modules/tag/list.tag.php';
