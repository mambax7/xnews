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

use Xmf\Request;
use XoopsModules\Xnews;

$currentFile = basename(__FILE__);
require_once __DIR__ . '/admin_header.php';

// require_once XNEWS_MODULE_PATH . '/class/deprecate/xnewstopic.php';
require_once XOOPS_ROOT_PATH . '/class/xoopslists.php';

// require_once XNEWS_MODULE_PATH . '/class/NewsStory.php';
// require_once XNEWS_MODULE_PATH . '/class/NewsTopic.php';
// require_once XNEWS_MODULE_PATH . '/class/Files.php';
// require_once XNEWS_MODULE_PATH . '/class/blacklist.php';
// require_once XNEWS_MODULE_PATH . '/class/registryfile.php';

require_once XOOPS_ROOT_PATH . '/class/uploader.php';
xoops_load('xoopspagenav');
require_once XOOPS_ROOT_PATH . '/class/tree.php';

$myts        = \MyTextSanitizer::getInstance();
$topicscount = 0;

$storiesTableName = $GLOBALS['xoopsDB']->prefix('nw_stories');
if (!nw_FieldExists('picture', $storiesTableName)) {
    nw_AddField('`picture` VARCHAR( 50 ) NOT NULL', $storiesTableName);
}

// **********************************************************************************************************************************************
// **** Main
// **********************************************************************************************************************************************

$op = Request::getString('op', 'metagen');

switch ($op) {
    case 'metagen':
        /**
         * Metagen
         *
         * Metagen is a system that can help you to have your page best indexed by search engines.
         * Except if you type meta keywords and meta descriptions yourself, the module will automatically create them.
         * From here you can also manage some other options like the maximum number of meta keywords to create and
         * the keywords apparition's order.
         */
        global $cfg;

        // admin navigation
        xoops_cp_header();
        $adminObject = \Xmf\Module\Admin::getInstance();
        $adminObject->displayNavigation($currentFile);

        xoops_load('XoopsFormLoader');

        $myts = \MyTextSanitizer::getInstance();
        xoops_loadLanguage('main', XNEWS_MODULE_DIRNAME);

        echo _AM_XNEWS_METAGEN_DESC . '<br>';

        // Metagen Options
        $registry = new Xnews\Registryfile('nw_metagen_options.txt');
        $content  = '';
        $content  = $registry->getfile();
        if ('' != xoops_trim($content)) {
            [$keywordscount, $keywordsorder] = explode(',', $content);
        } else {
            $keywordscount = $cfg['meta_keywords_count'] ?? 0;
            $keywordsorder = $cfg['meta_keywords_order'] ?? '';
        }
        $sform = new \XoopsThemeForm(_OPTIONS, 'metagenoptions', XNEWS_MODULE_URL . '/admin/index.php', 'post', true);
        $sform->addElement(new \XoopsFormHidden('op', 'metagenoptions'), false);
        $sform->addElement(new \XoopsFormText(_AM_XNEWS_META_KEYWORDS_CNT, 'keywordscount', 4, 6, $keywordscount), true);
        $keywordsorder = new \XoopsFormRadio(_AM_XNEWS_META_KEYWORDS_ORDER, 'keywordsorder', $keywordsorder);
        $keywordsorder->addOption(0, _AM_XNEWS_META_KEYWORDS_INTEXT);
        $keywordsorder->addOption(1, _AM_XNEWS_META_KEYWORDS_FREQ1);
        $keywordsorder->addOption(2, _AM_XNEWS_META_KEYWORDS_FREQ2);
        $sform->addElement($keywordsorder, false);
        $buttonTray = new \XoopsFormElementTray('', '');
        $submit_btn = new \XoopsFormButton('', 'post', _AM_XNEWS_MODIFY, 'submit');
        $buttonTray->addElement($submit_btn);
        $sform->addElement($buttonTray);
        $sform->display();

        // Blacklist
        $sform = new \XoopsThemeForm(_AM_XNEWS_BLACKLIST, 'metagenblacklist', XNEWS_MODULE_URL . '/admin/index.php', 'post', true);
        $sform->addElement(new \XoopsFormHidden('op', 'metagenblacklist'), false);

        // Remove words
        $remove_tray = new \XoopsFormElementTray(_AM_XNEWS_BLACKLIST);
        $remove_tray->setDescription(_AM_XNEWS_BLACKLIST_DESC);
        $blacklist = new \XoopsFormSelect('', 'blacklist', '', 5, true);
        $words     = [];

        $metablack = new Xnews\Blacklist();
        $words     = $metablack->getAllKeywords();
        if ($words && is_array($words)) {
            foreach ($words as $key => $value) {
                $blacklist->addOption($key, $value);
            }
        }

        $blacklist->setDescription(_AM_XNEWS_BLACKLIST_DESC);
        $remove_tray->addElement($blacklist, false);
        $remove_btn = new \XoopsFormButton('', 'go', _AM_XNEWS_DELETE, 'submit');
        $remove_tray->addElement($remove_btn, false);
        $sform->addElement($remove_tray);

        // Add some words
        $add_tray = new \XoopsFormElementTray(_AM_XNEWS_BLACKLIST_ADD);
        $add_tray->setDescription(_AM_XNEWS_BLACKLIST_ADD_DSC);
        $add_field = new \XoopsFormTextArea('', 'keywords', '', 5, 70);
        $add_tray->addElement($add_field, false);
        $add_btn = new \XoopsFormButton('', 'go', _AM_XNEWS_ADD, 'submit');
        $add_tray->addElement($add_btn, false);
        $sform->addElement($add_tray);
        $sform->display();

        xoops_cp_footer();
        break;
    case 'metagenoptions':
        // Save Metagen Options
        $registry = new Xnews\Registryfile('nw_metagen_options.txt');
        $registry->savefile(\Xmf\Request::getInt('keywordscount', 0, 'POST') . ',' . \Xmf\Request::getInt('keywordsorder', 0, 'POST'));
        redirect_header('index.php?op=metagen', 3, _AM_XNEWS_DBUPDATED);
        xoops_cp_footer();
        break;
    case 'metagenblacklist':
        // Save metagen's blacklist words
        $blacklist = new Xnews\Blacklist();
        $words     = $blacklist->getAllKeywords();

        if (\Xmf\Request::hasVar('go', 'POST') && _AM_XNEWS_DELETE == $_POST['go']) {
            foreach ($_POST['blacklist'] as $black_id) {
                $blacklist->delete($black_id);
            }
            $blacklist->store();
        } else {
            if (\Xmf\Request::hasVar('go', 'POST') && _AM_XNEWS_ADD == $_POST['go']) {
                $p_keywords = $_POST['keywords'];
                $keywords   = explode("\n", $p_keywords);
                foreach ($keywords as $keyword) {
                    if ('' != xoops_trim($keyword)) {
                        $blacklist->addkeywords(xoops_trim($keyword));
                    }
                }
                $blacklist->store();
            }
        }
        redirect_header('index.php?op=metagen', 3, _AM_XNEWS_DBUPDATED);
        xoops_cp_footer();
        break;
}
