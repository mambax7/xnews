<?php
// xNews Import Module                    										
// Created by DNPROSSI
												
if (!defined('XOOPS_ROOT_PATH')) {
	die('XOOPS root path not defined');
}

$modversion['name'] = "xNews Importer";
$modversion['version'] = 1.01;
$modversion['description'] = "xNews Importer Beta - This is an xNews Import module.";
$modversion['author'] = "DNPROSSI";
$modversion['credits'] = "";
$modversion['help'] = "";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 0;
$modversion['image'] = "images/xnewsimport_logo.png";
$modversion['dirname'] = "xnewsimport";

$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = 'xnews_import';

// Admin
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Menu
$modversion['hasMain'] = 0;

// Config
$i = 0;
/**
 * Number of news and topics to display in the module's admin part
 */
$i++;
$modversion['config'][$i]['name'] = 'storycountadmin';
$modversion['config'][$i]['title'] = '_MI_XNI_STORYCOUNTADMIN';
$modversion['config'][$i]['description'] = '_MI_XNI_STORYCOUNTADMIN_DESC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 10;
$modversion['config'][$i]['options'] = array('5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30, '35' => 35, '40' => 40);

?>
