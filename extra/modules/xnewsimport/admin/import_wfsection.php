<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @author          Marius Scurtescu <mariuss@romanians.bc.ca>
 * @version         $Id: news.php 0 2009-06-11 18:47:04Z trabis $
 */
require_once dirname(__DIR__) . '/admin_header.php';
$myts = \MyTextSanitizer::getInstance();

$importFromModuleName = 'Smartsection ' . @$_POST['smartsection_version'];

$scriptname = 'smartsection.php';

$op = 'start';

if ('go' === \Xmf\Request::getCmd('op', 'start', 'POST')) {
    $op = 'go';
}

if ('start' === $op) {
    xoops_load('XoopsFormLoader');

    publisher_cpHeader();
    publisher_adminMenu(-1, _AM_PUBLISHER_IMPORT);
    publisher_openCollapsableBar('newsimport', 'newsimporticon', sprintf(_AM_PUBLISHER_IMPORT_FROM, $importFromModuleName), _AM_PUBLISHER_IMPORT_INFO);

    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('smartsection_categories'));
    list($totalCat) = $xoopsDB->fetchRow($result);

    if (0 == $totalCat) {
        echo '<span style="color: #567; margin: 3px 0 12px 0; font-size: small; display: block; ">' . _AM_PUBLISHER_IMPORT_NO_CATEGORY . '</span>';
    } else {
        require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';

        $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('smartsection_items'));
        list($totalArticles) = $xoopsDB->fetchRow($result);

        if (0 == $totalArticles) {
            echo '<span style="color: #567; margin: 3px 0 12px 0; font-size: small; display: block; ">' . sprintf(_AM_PUBLISHER_IMPORT_MODULE_FOUND_NO_ITEMS, $importFromModuleName, $totalArticles) . '</span>';
        } else {
            echo '<span style="color: #567; margin: 3px 0 12px 0; font-size: small; display: block; ">' . sprintf(_AM_PUBLISHER_IMPORT_MODULE_FOUND, $importFromModuleName, $totalArticles, $totalCat) . '</span>';

            $form = new \XoopsThemeForm(_AM_PUBLISHER_IMPORT_SETTINGS, 'import_form', PUBLISHER_ADMIN_URL . "/import/{$scriptname}");

            // Categories to be imported
            $sql = 'SELECT cat.categoryid, cat.parentid, cat.name, COUNT(art.itemid) FROM ' . $xoopsDB->prefix('smartsection_categories') . ' AS cat INNER JOIN ' . $xoopsDB->prefix('smartsection_items') . ' AS art ON cat.categoryid=art.categoryid GROUP BY art.categoryid';

            $result           = $xoopsDB->query($sql);
            $cat_cbox_options = [];

            while (list($cid, $pid, $cat_title, $art_count) = $xoopsDB->fetchRow($result)) {
                $cat_title              = $myts->displayTarea($cat_title);
                $cat_cbox_options[$cid] = "$cat_title ($art_count)";
            }

            $cat_label = new \XoopsFormLabel(_AM_PUBLISHER_IMPORT_CATEGORIES, implode('<br>', $cat_cbox_options));
            $cat_label->setDescription(_AM_PUBLISHER_IMPORT_CATEGORIES_DSC);
            $form->addElement($cat_label);

            // Publisher parent category
            $mytree = new \XoopsTree($xoopsDB->prefix('publisher_categories'), 'categoryid', 'parentid');
            ob_start();
            $mytree->makeMySelBox('name', 'weight', $preset_id = 0, $none = 1, $sel_name = 'parent_category');

            $parent_cat_sel = new \XoopsFormLabel(_AM_PUBLISHER_IMPORT_PARENT_CATEGORY, ob_get_contents());
            $parent_cat_sel->setDescription(_AM_PUBLISHER_IMPORT_PARENT_CATEGORY_DSC);
            $form->addElement($parent_cat_sel);
            ob_end_clean();

            $form->addElement(new \XoopsFormHidden('op', 'go'));
            $form->addElement(new \XoopsFormButton('', 'import', _AM_PUBLISHER_IMPORT, 'submit'));

            $form->addElement(new \XoopsFormHidden('from_module_version', $_POST['news_version']));

            $form->display();
        }
    }

    publisher_closeCollapsableBar('newsimport', 'newsimporticon');
    xoops_cp_footer();
}

if ('go' === $op) {
    publisher_cpHeader();
    publisher_adminMenu(-1, _AM_PUBLISHER_IMPORT);
    publisher_openCollapsableBar('newsimportgo', 'newsimportgoicon', sprintf(_AM_PUBLISHER_IMPORT_FROM, $importFromModuleName), _AM_PUBLISHER_IMPORT_RESULT);

    $moduleHandler          = xoops_getHandler('module');
    $moduleObj              = $moduleHandler->getByDirname('smartsection');
    $smartsection_module_id = $moduleObj->getVar('mid');

    $grouppermHandler = xoops_getHandler('groupperm');

    $cnt_imported_cat      = 0;
    $cnt_imported_articles = 0;

    $parentId = $_POST['parent_category'];

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix('smartsection_categories');

    $resultCat = $xoopsDB->query($sql);

    $newCatArray     = [];
    $newArticleArray = [];

    $oldToNew = [];
    while (false !== ($arrCat = $xoopsDB->fetchArray($resultCat))) {
        $newCat           = [];
        $newCat['oldid']  = $arrCat['categoryid'];
        $newCat['oldpid'] = $arrCat['parentid'];

        $categoryObj = $publisher->getHandler('category')->create();

        $categoryObj->setVars($arrCat);
        $categoryObj->setVar('categoryid', 0);

        // Copy category image
        if (('blank.gif' !== $arrCat['image']) && ('' != $arrCat['image'])) {
            copy(XOOPS_ROOT_PATH . '/uploads/smartsection/images/category/' . $arrCat['image'], XOOPS_ROOT_PATH . '/uploads/publisher/images/category/' . $arrCat['image']);
        }

        if (!$publisher->getHandler('category')->insert($categoryObj)) {
            echo sprintf(_AM_PUBLISHER_IMPORT_CATEGORY_ERROR, $arrCat['name']) . '<br>';
            continue;
        }

        $newCat['newid'] = $categoryObj->categoryid();
        ++$cnt_imported_cat;

        echo sprintf(_AM_PUBLISHER_IMPORT_CATEGORY_SUCCESS, $categoryObj->name()) . "<br\>";

        $sql            = 'SELECT * FROM ' . $xoopsDB->prefix('smartsection_items') . ' WHERE categoryid=' . $arrCat['categoryid'];
        $resultArticles = $xoopsDB->query($sql);

        while (false !== ($arrArticle = $xoopsDB->fetchArray($resultArticles))) {
            // insert article
            $itemObj = $publisher->getHandler('item')->create();

            $itemObj->setVars($arrArticle);
            $itemObj->setVar('itemid', 0);
            $itemObj->setVar('categoryid', $categoryObj->categoryid());

            // TODO: move article images to image manager

            // HTML Wrap
            // TODO: copy contents folder
            /*
             if ($arrArticle['htmlpage']) {
             $pagewrap_filename = XOOPS_ROOT_PATH . "/modules/wfsection/html/" .$arrArticle['htmlpage'];
             if (file_exists($pagewrap_filename)) {
             if (copy($pagewrap_filename, XOOPS_ROOT_PATH . "/uploads/publisher/content/" . $arrArticle['htmlpage'])) {
             $itemObj->setVar('body', "[pagewrap=" . $arrArticle['htmlpage'] . "]");
             echo sprintf("&nbsp;&nbsp;&nbsp;&nbsp;" . _AM_PUBLISHER_IMPORT_ARTICLE_WRAP, $arrArticle['htmlpage']) . "<br>";
             }
             }
             }
             */

            if (!$itemObj->store()) {
                echo sprintf('  ' . _AM_PUBLISHER_IMPORT_ARTICLE_ERROR, $arrArticle['title']) . '<br>';
                continue;
            }

            // Linkes files
            $sql               = 'SELECT * FROM ' . $xoopsDB->prefix('smartsection_files') . ' WHERE itemid=' . $arrArticle['itemid'];
            $resultFiles       = $xoopsDB->query($sql);
            $allowed_mimetypes = null;
            while (false !== ($arrFile = $xoopsDB->fetchArray($resultFiles))) {
                $filename = XOOPS_ROOT_PATH . '/uploads/smartsection/' . $arrFile['filename'];
                if (file_exists($filename)) {
                    if (copy($filename, XOOPS_ROOT_PATH . '/uploads/publisher/' . $arrFile['filename'])) {
                        $fileObj = $publisher->getHandler('file')->create();
                        $fileObj->setVars($arrFile);
                        $fileObj->setVar('fileid', 0);

                        if ($fileObj->store($allowed_mimetypes, true, false)) {
                            echo '&nbsp;&nbsp;&nbsp;&nbsp;' . sprintf(_AM_PUBLISHER_IMPORTED_ARTICLE_FILE, $arrFile['filename']) . '<br>';
                        }
                    }
                }
            }

            $newArticleArray[$arrArticle['itemid']] = $itemObj->itemid();
            echo '&nbsp;&nbsp;' . sprintf(_AM_PUBLISHER_IMPORTED_ARTICLE, $itemObj->title()) . '<br>';
            ++$cnt_imported_articles;
        }

        // Saving category permissions
        $groupsIds = $grouppermHandler->getGroupIds('category_read', $arrCat['categoryid'], $smartsection_module_id);
        publisher_saveCategory_Permissions($groupsIds, $categoryObj->categoryid(), 'category_read');
        $groupsIds = $grouppermHandler->getGroupIds('item_submit', $arrCat['categoryid'], $smartsection_module_id);
        publisher_saveCategory_Permissions($groupsIds, $categoryObj->categoryid(), 'item_submit');

        // Saving items permissions
        publisher_overrideItemsPermissions($groupsIds, $categoryObj->categoryid());

        $newCatArray[$newCat['oldid']] = $newCat;
        unset($newCat);
        echo '<br>';
    }

    // Looping through cat to change the parentid to the new parentid
    foreach ($newCatArray as $oldid => $newCat) {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('categoryid', $newCat['newid']));
        $oldpid = $newCat['oldpid'];
        if (0 == $oldpid) {
            $newpid = $parentId;
        } else {
            $newpid = $newCatArray[$oldpid]['newid'];
        }
        $publisher->getHandler('category')->updateAll('parentid', $newpid, $criteria);
        unset($criteria);
    }

    // Looping through the comments to link them to the new articles and module
    echo _AM_PUBLISHER_IMPORT_COMMENTS . '<br>';

    $publisher_module_id = $publisher->getModule()->mid();

    $commentHandler = xoops_getHandler('comment');
    $criteria       = new \CriteriaCompo();
    $criteria->add(new \Criteria('com_modid', $smartsection_module_id));
    $comments = $commentHandler->getObjects($criteria);
    foreach ($comments as $comment) {
        $comment->setVar('com_itemid', $newArticleArray[$comment->getVar('com_itemid')]);
        $comment->setVar('com_modid', $publisher_module_id);
        $comment->setNew();
        if (!$commentHandler->insert($comment)) {
            echo '&nbsp;&nbsp;' . sprintf(_AM_PUBLISHER_IMPORTED_COMMENT_ERROR, $comment->getVar('com_title')) . '<br>';
        } else {
            echo '&nbsp;&nbsp;' . sprintf(_AM_PUBLISHER_IMPORTED_COMMENT, $comment->getVar('com_title')) . '<br>';
        }
    }

    echo '<br><br>Done.<br>';
    echo sprintf(_AM_PUBLISHER_IMPORTED_CATEGORIES, $cnt_imported_cat) . '<br>';
    echo sprintf(_AM_PUBLISHER_IMPORTED_ARTICLES, $cnt_imported_articles) . '<br>';
    echo "<br><a href='" . PUBLISHER_URL . "/'>" . _AM_PUBLISHER_IMPORT_GOTOMODULE . '</a><br>';

    publisher_closeCollapsableBar('newsimportgo', 'newsimportgoicon');
    xoops_cp_footer();
}
