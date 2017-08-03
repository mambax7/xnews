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
 * @copyright    XOOPS Project http://sourceforge.net/projects/xoops/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package      xNews
 * @since        1.6.0
 * @author       XOOPS Development Team,
 * @version      $Id $
 */

require_once __DIR__ . '/../../mainfile.php';

define('XNI_SUBPREFXNI', 'XNI');
define('XNI_MODULE_DIR_NAME', 'xnewsimport');
define('XNI_MODULE_PATH', XOOPS_ROOT_PATH . '/modules/' . XNI_MODULE_DIR_NAME);
define('XNI_MODULE_URL', XOOPS_URL . '/modules/' . XNI_MODULE_DIR_NAME);
define('XNI_UPLOADS_NEWS_PATH', XOOPS_ROOT_PATH . '/uploads/' . XNI_MODULE_DIR_NAME);
define('XNI_IMAGES_FILES_PATH', XOOPS_ROOT_PATH . '/uploads/' . XNI_MODULE_DIR_NAME . '/images');
