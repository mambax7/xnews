<?php
$currentFile = basename(__FILE__);
include_once __DIR__ . '/admin_header.php';
include_once XNEWS_MODULE_PATH . '/class/deprecate/xnewstopic.php';
// admin navigation
xoops_cp_header();
$indexAdmin = new ModuleAdmin();
echo $indexAdmin->addNavigation($currentFile);
//
$permToSet = isset($_REQUEST['permToSet']) ? $_REQUEST['permToSet'] : 'nw_approve';
// permissions selector
xoops_load('XoopsFormLoader');
$opForm = new XoopsSimpleForm('', 'opform', $currentFile, 'get');
$opSelect = new XoopsFormSelect('', 'permToSet', $permToSet, 1, false);
$opSelect->setExtra('onchange="document.forms.opform.submit()"');
$opSelect->addOption('nw_approve', _AM_NW_APPROVEFORM);
$opSelect->addOption('nw_submit', _AM_NW_SUBMITFORM);
$opSelect->addOption('nw_view', _AM_NW_VIEWFORM);
$opForm->addElement($opSelect);
$opForm->display();
// switch permission
switch ($permToSet) {
    case 'nw_approve':
        $titleOfForm = _AM_NW_APPROVEFORM;
        $permName = 'nw_approve';
        $permDesc = _AM_NW_APPROVEFORM_DESC;
        break;
    case 'nw_submit':
        $titleOfForm = _AM_NW_SUBMITFORM;
        $permName = 'nw_submit';
        $permDesc = _AM_NW_SUBMITFORM_DESC;
        break;
    case 'nw_view':
        $titleOfForm = _AM_NW_VIEWFORM;
        $permName = 'nw_view';
        $permDesc = _AM_NW_VIEWFORM_DESC;
        break;
}
// render permissions grid
$module_id = $GLOBALS['xoopsModule']->getVar('mid');
include_once $GLOBALS['xoops']->path('/class/xoopsform/grouppermform.php');
$permissionsForm = new XoopsGroupPermForm($titleOfForm, $module_id, $permName, $permDesc, "admin/{$currentFile}");
$xt = new XnewsDeprecateTopic($xoopsDB->prefix('nw_topics'));
$alltopics =& $xt->getTopicsList();
foreach ($alltopics as $topic_id => $topic) {
    $permissionsForm->addItem($topic_id, $topic['title'], $topic['pid']);
}
echo $permissionsForm->render();
echo "<br />\n";
unset ($permissionsForm);

include __DIR__ . '/admin_footer.php';
