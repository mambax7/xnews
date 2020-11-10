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

use XoopsModules\Xnews;

$currentFile = basename(__FILE__);
require_once __DIR__ . '/admin_header.php';
// require_once XNEWS_MODULE_PATH . '/class/deprecate/xnewstopic.php';
// admin navigation
xoops_cp_header();
$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation($currentFile);

$permToSet = isset($_REQUEST['permToSet']) ? $_REQUEST['permToSet'] : 'nw_approve';
// permissions selector
xoops_load('XoopsFormLoader');
$opForm   = new \XoopsSimpleForm('', 'opform', $currentFile, 'get');
$opSelect = new \XoopsFormSelect('', 'permToSet', $permToSet, 1, false);
$opSelect->setExtra('onchange="document.forms.opform.submit()"');
$opSelect->addOption('nw_approve', _AM_XNEWS_APPROVEFORM);
$opSelect->addOption('nw_submit', _AM_XNEWS_SUBMITFORM);
$opSelect->addOption('nw_view', _AM_XNEWS_VIEWFORM);
$opForm->addElement($opSelect);
$opForm->display();
// switch permission
switch ($permToSet) {
    case 'nw_approve':
        $titleOfForm = _AM_XNEWS_APPROVEFORM;
        $permName    = 'nw_approve';
        $permDesc    = _AM_XNEWS_APPROVEFORM_DESC;
        break;
    case 'nw_submit':
        $titleOfForm = _AM_XNEWS_SUBMITFORM;
        $permName    = 'nw_submit';
        $permDesc    = _AM_XNEWS_SUBMITFORM_DESC;
        break;
    case 'nw_view':
        $titleOfForm = _AM_XNEWS_VIEWFORM;
        $permName    = 'nw_view';
        $permDesc    = _AM_XNEWS_VIEWFORM_DESC;
        break;
}
// render permissions grid
$module_id = $GLOBALS['xoopsModule']->getVar('mid');
require_once $GLOBALS['xoops']->path('/class/xoopsform/grouppermform.php');
$permissionsForm = new \XoopsGroupPermForm($titleOfForm, $module_id, $permName, $permDesc, "admin/{$currentFile}");
$xt              = new Xnews\Deprecate\DeprecateTopic($xoopsDB->prefix('nw_topics'));
$alltopics       = $xt->getTopicsList();
foreach ($alltopics as $topic_id => $topic) {
    $permissionsForm->addItem($topic_id, $topic['title'], $topic['pid']);
}
echo $permissionsForm->render();
echo "<br>\n";
unset($permissionsForm);

require_once __DIR__ . '/admin_footer.php';
