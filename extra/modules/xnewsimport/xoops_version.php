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
 * @package      xNewsImport
 * @since        1.6.0
 * @author       XOOPS Development Team, DNPROSSI
 * @version      $Id $
 */

defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

$modversion['name']        = 'xNews Importer';
$modversion['version']     = 1.02;
$modversion['description'] = 'xNews Importer Beta - This is an xNews Import module.';
$modversion['author']      = 'DNPROSSI';
$modversion['credits']     = '';
$modversion['help']        = 'page=help';
$modversion['license']     = 'GNU GPL 2.0 or later';
$modversion['license_url'] = 'www.gnu.org/licenses/gpl-2.0.html';
$modversion['official']    = 0;
$modversion['image']       = 'images/module_logo.png';
$modversion['dirname']     = basename(__DIR__);

//$modversion['dirmoduleadmin'] = '/Frameworks/moduleclasses/moduleadmin';
//$modversion['icons16']        = '../../Frameworks/moduleclasses/icons/16';
//$modversion['icons32']        = '../../Frameworks/moduleclasses/icons/32';
$modversion['modicons16'] = 'assets/images/icons/16';
$modversion['modicons32'] = 'assets/images/icons/32';
//about
$modversion['release_date']        = '2013/09/14';
$modversion['module_website_url']  = 'www.xoops.org';
$modversion['module_website_name'] = 'XOOPS';
$modversion['module_status']       = 'Beta 1';
$modversion['min_php']             = '5.2';
$modversion['min_xoops']           = '2.5.6';
$modversion['min_admin']           = '1.1';
$modversion['min_db']              = [
    'mysql'  => '5.0.7',
    'mysqli' => '5.0.7'
];

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = 'xnews_import';

// Admin
$modversion['hasAdmin']    = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex']  = 'admin/index.php';
$modversion['adminmenu']   = 'admin/menu.php';

// Menu
$modversion['hasMain'] = 0;

$modversion['min_php']             = '5.5';
$modversion['min_xoops']           = '2.5.9';
$modversion['min_admin']           = '1.2';
$modversion['min_db']              = ['mysql' => '5.5'];

// Config
$i = 0;
/**
 * Number of news and topics to display in the module's admin part
 */
++$i;
$modversion['config'][$i]['name']        = 'storycountadmin';
$modversion['config'][$i]['title']       = '_MI_XNI_STORYCOUNTADMIN';
$modversion['config'][$i]['description'] = '_MI_XNI_STORYCOUNTADMIN_DESC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 10;
$modversion['config'][$i]['options']     = ['5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30, '35' => 35, '40' => 40];
