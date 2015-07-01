<?php
/**
* Module: xNews Import
* Author: DNPROSSI
* Licence: GNU
*/

include_once dirname(__FILE__) . "/header.php";
include_once '../../../include/cp_header.php';
include_once XNI_MODULE_PATH . '/include/functions.php';
include_once XNI_MODULE_PATH . '/admin/functions.php';
include_once XNI_MODULE_PATH . '/class/class.newstopic.php';
include_once XNI_MODULE_PATH . '/class/class.newsstory.php';
include_once XNI_MODULE_PATH . '/class/class.xnewsimport.php';

function NewsImport()
{
	global $xoopsDB;
	xoops_cp_header();
	$importfrom = 'none';
	$importto = 'none';
        
	adminMenu(0, _AM_XNI_IMPORT);
        
	echo "<h1>" . _AM_XNI_IMPORT_TITLE . "</h1>";
	echo _AM_XNI_IMPORT_INFO . "<br /><br />";
        
	xoops_load('XoopsFormLoader');
        
	// Iterate through installed modules tables for articles, items, category, 
	// topics and add them to selectlist
	// This will also find clones
	$news_fieldsearch_array = array(
		'topic_id',
		'topic_pid',
		'banner'
	);
        
	$other_fieldsearch_array = array(
		'categoryid',
		'parentid',
		'moderator'
	);
		
	//Get From Module Data
	$module_handler =& xoops_gethandler('module');
	$installed_mods = $module_handler->getObjects();
	$listed_mods = array();
	$count = 0;
	foreach ( $installed_mods as $module ) {
		if ( $module->getVar('dirname') != 'system' && $module->getVar('isactive') == 1 ) {
			$module->loadInfo($module->getVar('dirname'));
			$modtables = $module->getInfo('tables');
			if ( $modtables != false && is_array($modtables) ) {
				foreach ( $modtables as $table ) {
					$newscount = 0;
					foreach ($news_fieldsearch_array as $field ) {
						if ( xni_fieldexists($field, $xoopsDB->prefix($table)) ) {
							$newscount++;
						} 
					}
					if ( $newscount == 2 ) { 
						$from_module_version = round($module->getVar('version') / 100, 2);
						if (($from_module_version >= 1.64)) {
							$importfrom_array["news/" . $module->getVar('dirname')] = $module->getVar('dirname') . " " . $from_module_version;
						} 
					}
					/*} elseif ($newscount == 3) {
						$from_module_version = round($module->getVar('version') / 100, 2);
						if (($from_module_version >= 2.0)) {
							$importfrom_array["ams/" . $module->getVar('dirname')] = $module->getVar('dirname') . " " . $from_module_version;
						}
					}
					$smartcount = 0;
					foreach ( $other_fieldsearch_array as $field ) {
						if ( xni_fieldexists($field, $xoopsDB->prefix($table)) ) {
							$smartcount++;
						} 
					}
					if ( $smartcount == 2 ) { 
						$from_module_version = round($module->getVar('version') / 100, 2);
						if (($from_module_version >= 2.0)) {
							$importfrom_array["smartsection/" . $module->getVar('dirname')] = $module->getVar('dirname') . " " . $from_module_version;
						}
					} elseif ($smartcount == 3) {
						$from_module_version = round($module->getVar('version') / 100, 2);
						if (($from_module_version >= 1.0)) {
							$importfrom_array["publisher/" . $module->getVar('dirname')] = $module->getVar('dirname') . " " . $from_module_version;
						}
					}*/
				}  
			}
		}
	}			
        
	$result = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix('news_clonerdata') . " WHERE clone_installed = 1");
	$ix = 0;
	while ( $clone = $xoopsDB->fetchArray($result) ) {
		$importto_array[$ix] = $clone['clone_dir'];
		$ix++;
	}
        
	if (isset($importfrom_array) && count($importfrom_array) > 0 && isset($importto_array) && count($importto_array) > 0) {

		$sform = new XoopsThemeForm(_AM_XNI_IMPORT_SELECTION, "op", xoops_getenv('PHP_SELF'));
		$sform->setExtra('enctype="multipart/form-data"');

		// Partners to import from
		$importfrom = new XoopsFormSelect('', 'importfrom', $importfrom);
		$importfrom->addOptionArray($importfrom_array);
		$importfrom_tray = new XoopsFormElementTray(_AM_XNI_IMPORT_FROM , '&nbsp;');
		$importfrom_tray->addElement($importfrom);
		$importfrom_tray->setDescription(_AM_XNI_IMPORT_FROM_DSC);
		$sform->addElement($importfrom_tray);

		// xNews & clones to import to
		$importto = new XoopsFormSelect('', 'importto', $importto);
		$importto->addOptionArray($importto_array);
		$importto_tray = new XoopsFormElementTray(_AM_XNI_IMPORT_TO , '&nbsp;');
		$importto_tray->addElement($importto);
		$importto_tray->setDescription(_AM_XNI_IMPORT_TO_DSC);
		$sform->addElement($importto_tray);

		// Buttons
		$button_tray = new XoopsFormElementTray('', '');
		$hidden = new XoopsFormHidden('op', 'topicselect');
		$button_tray->addElement($hidden);

		$butt_import = new XoopsFormButton('', '', _AM_XNI_IMPORT, 'submit');
		$butt_import->setExtra('onclick="this.form.elements.op.value=\'topicselect\'"');
		$button_tray->addElement($butt_import);

		$butt_cancel = new XoopsFormButton('', '', _AM_XNI_CANCEL, 'button');
		$butt_cancel->setExtra('onclick="history.go(-1)"');    
		$button_tray->addElement($butt_cancel);
		$sform->addElement($button_tray);
		$sform->display();
		unset($hidden);
	} else {
		echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-weight: bold; font-size: small; display: block; \">" . _AM_XNI_IMPORT_NO_MODULE . "</span>";
	}
}

function TopicSelect() 
{
	xoops_cp_header();
	echo '<script type="text/javascript" src="' . XNI_MODULE_URL . '/js/funcs.js"></script>';
    xoops_load('XoopsFormLoader');
        
    adminMenu(0, _AM_XNI_IMPORT);
	global $xoopsDB;	
	
	$begin = isset($_GET['begin']) ? intval($_GET['begin']) : 0;
    include_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
	include_once XOOPS_ROOT_PATH . '/class/xoopstopic.php';
	include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
	include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
	include_once XOOPS_ROOT_PATH . '/class/tree.php';
	$myts =& MyTextSanitizer::getInstance();
		
	//Detect in out modules and prepare form
	$from_import = (isset($_POST['importfrom'])) ? $_POST['importfrom'] : 'nonselected';
    $to_import_clone_id = (isset($_POST['importto'])) ? $_POST['importto'] : 'nonselected';    
    
    $options = explode('/', $from_import);
    
    $from_import_type = $options[0];
    $from_import_dirname = $options[1];
    
    //Get selected xNews module or clone to import to
    $result = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix('news_clonerdata') . " WHERE clone_installed = 1");
	$ix = 0;
	while ( $clone = $xoopsDB->fetchArray($result) ) {
		$clone_arr[$ix] = $clone;
		$ix++;
	}
    
    $to_import_dirname = $clone_arr[$to_import_clone_id]['clone_dir'];
    $to_subprefix = $clone_arr[$to_import_clone_id]['clone_subprefix'] . "_";
    
    if ($from_import_dirname == $to_import_dirname) 
    {
		//REDIRECT IF SAME
		redirect_header('index.php?op=import', 2, "It's not possible to import the same ".$options[1]." module!");
	}
    
    $from_import_type = $options[0];
    $from_import_dirname = $options[1];
    
    $from_topic_id_type = '';
	$from_topic_pid_type = '';
	$from_topic_title_type = '';
	$from_table_name = '';
       
    //GET ALL MODULE-FROM DATA
    $module_handler = &xoops_gethandler('module');
    $from_module = &$module_handler->getByDirname($from_import_dirname);
    $from_module->loadInfo($from_module->getVar('dirname'));
	
	$from_modtables = $from_module->getInfo('tables');
	if ( $from_modtables != false && is_array($from_modtables) ) {
		foreach ( $from_modtables as $from_table ) {
			$from_table_arr = explode('_', $from_table); 
			if ( count($from_table_arr) > 0 ) { //&& $from_import_dirname != 'news') {				
				if ($from_import_dirname != 'news') {
					$subprefix = $from_table_arr[0] . "_"; 
				} else { 
					$subprefix = ''; 
				}
				//trigger_error($subprefix . "  NEWS   ".$from_table_arr[0], E_USER_WARNING);
				switch ($from_import_type) {
					case 'news':
						$from_subprefix = $subprefix;
						$from_topics_arr = xni_gettopics($from_subprefix);
						$from_topic_id = 'topic_id';
						$from_topic_pid = 'topic_pid';
						$from_topic_title = 'topic_title';
						$from_topic_table = $from_subprefix . 'topics';
						$from_story_table = $from_subprefix . 'stories'; 
					break;
					/*case 'ams':
					    $from_subprefix = $subprefix;
					    $from_topics_arr = xni_gettopics($from_subprefix);
					    $from_topic_id = 'topic_id';
						$from_topic_pid = 'topic_pid';
						$from_topic_title = 'topic_title';
						$from_topic_table = $from_subprefix . 'topics';
						$from_story_table = $from_subprefix . 'stories';
					break;
					case 'smartsection':
						$from_subprefix = $subprefix;
						$from_topics_arr = xni_getcategories($from_subprefix);
						$from_topic_id = 'categoryid';
						$from_topic_pid = 'parentid';
						$from_topic_title = 'name';
						$from_topic_table = $from_subprefix . 'categories';
						$from_story_table = $from_subprefix . 'items';
					break;
					case 'publisher':
						$from_subprefix = $subprefix;
						$from_topics_arr = xni_getcategories($from_subprefix);
						$from_topic_id = 'categoryid';
						$from_topic_pid = 'parentid';
						$from_topic_title = 'name';
						$from_topic_table = $from_subprefix . 'categories';
						$from_story_table = $from_subprefix . 'items';
					break;*/
				}	
				
				//trigger_error($from_table_arr[0], E_USER_WARNING); 
			} 
		}
	}
    
    //------------------------------------------------------------------

    $result = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix($from_topic_table));
    list ($totalCat) = $xoopsDB->fetchRow($result);

    if ($totalCat == 0) {
        echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \">" . _AM_XNI_IMPORT_NO_CATEGORY . "</span>";
    } else {
        
        $result = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix($from_story_table));
        list ($totalArticles) = $xoopsDB->fetchRow($result);

        if ($totalArticles == 0) {
            echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \">" . sprintf(_AM_XNI_IMPORT_MODULE_FOUND_NO_ITEMS, $from_import_dirname, $totalArticles) . "</span>";
        } else {
            echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \">" . sprintf(_AM_XNI_IMPORT_MODULE_FOUND, $totalArticles, $totalCat, $from_import_dirname) . "</span>";

            $form = new XoopsThemeForm(_AM_XNI_IMPORT_SETTINGS, 'import_form',  XNI_MODULE_URL . "/admin/index.php");
            
			$table_name = $from_topic_table;
            $topiclist=new XoopsFormSelect(_AM_XNI_IMPORT_FROM_TOPICS, 'from_topics','',5,true);
		    $topics_arr=array();
			$xt = new xni_NewsTopic();
			$allTopics = $xt->getAllTopics($table_name, false); // The webmaster can see everything
			$topic_tree = new XoopsObjectTree($allTopics, 'topic_id', 'topic_pid');
			$topics_arr = $topic_tree->getAllChild(0);
			if(count($topics_arr)) {
				foreach ($topics_arr as $onetopic) {
					$topiclist->addOption($onetopic->topic_id(),$onetopic->topic_title());
				}
			}
			$topiclist->setDescription(_AM_XNI_IMPORT_FROM_TOPICS_DSC);
			$form->addElement($topiclist, false);
                        
			/*$to_table_name = $to_subprefix . 'topics';
            $to_topiclist=new XoopsFormSelect(_AM_XNI_IMPORT_TO_TOPICS, 'to_topic','',5,false);
		    $to_topics_arr=array();
			$to_xt = new xni_NewsTopic();
			$to_allTopics = $to_xt->getAllTopics($to_table_name, false); // The webmaster can see everything
			$to_topic_tree = new XoopsObjectTree($to_allTopics, 'topic_id', 'topic_pid');
			$to_topics_arr = $to_topic_tree->getAllChild(0);
			if(count($to_topics_arr)) {
				foreach ($to_topics_arr as $to_onetopic) {
					$to_topiclist->addOption($to_onetopic->topic_id(),$to_onetopic->topic_title());
				}
			}
			$to_topiclist->setDescription(_AM_XNI_IMPORT_TO_TOPICS_DSC);
			$form->addElement($to_topiclist, false);
			*/
			$form->addElement (new XoopsFormHidden('importfromdirname', $from_import_dirname));
			$form->addElement (new XoopsFormHidden('importtodirname', $to_import_dirname));
			$form->addElement (new XoopsFormHidden('importfromsubprefix', $from_subprefix));
			$form->addElement (new XoopsFormHidden('importtosubprefix', $to_subprefix));
            
            $form->addElement (new XoopsFormHidden('op', 'startimport'));
            $form->addElement (new XoopsFormButton ('', 'import', _AM_XNI_IMPORT, 'submit'));

            $form->display();
        }
    }
    xoops_cp_footer();   
}

function StartImport()
{
	global $xoopsDB, $xoopsConfig, $xoopsModule;
	
	xoops_cp_header();
	
	if (isset($_POST['importfromdirname'])) 
	{
		$from_module_dirname = $_POST['importfromdirname'];
		$from_module_subprefix = $_POST['importfromsubprefix'];
		$to_module_dirname = $_POST['importtodirname'];
		$to_module_subprefix = $_POST['importtosubprefix'];
	}
	
	$module_handler =& xoops_gethandler('module');
	$moduleObj = $module_handler->getByDirname($from_module_dirname);
	$news_module_id = $moduleObj->getVar('mid');

    $gperm_handler =& xoops_gethandler('groupperm');

    $cnt_imported_cat = 0;
    $cnt_imported_articles = 0;
    $cnt_imported_comments = 0;
    $cnt_imported_files = 0;

    // If none selected then import all topics
	if (!isset($_POST['from_topics'])) 
	{
	   $resultCat = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix($from_module_subprefix . "topics"));
	   include_once('import.php');  
	}
	// Import selected topics
	else 
	{
		$ftpcs = $_POST['from_topics'];
		$ftpcs = implode("', '", $ftpcs);	
		$resultCat = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix($from_module_subprefix . "topics") . " WHERE topic_id IN ('".$ftpcs."')");
		include_once('import.php');  
	}
	
	xoops_cp_footer();   
}

$op = 'none';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

switch ($op) {
	case "startimport":
        StartImport();
        break;
    case "topicselect":
        TopicSelect();
        break;

    case "import":
	    NewsImport();
        break;
       
    case "default":
    default:
        xoops_cp_header();
        adminmenu(-1);
        echo '<h4>' . _AM_XNI_CONFIG . '</h4>';
        echo"<table width='100%' border='0' cellspacing='1' class='outer'><tr><td width='59%' class=\"odd\" id=\"xo-newsicons\" >";
        echo "<b><a href='index.php?op=import'><img  src='" . XNI_MODULE_URL . "/images/import32.png' alt='' /><br/>" . _AM_XNI_IMPORT_TITLE . "</a></b>";
        echo "<b><a href='" . XOOPS_URL . "/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $xoopsModule -> getVar( 'mid' ) . "'><img  src='" . NW_MODULE_URL . "/images/prefs32.png' alt='' /><br/>" . _AM_XNI_PREFERENCES . "</a></b>";
        echo "<br /><br />\n";
        echo"</td><td width='50%' class=\"even\" id=\"xo-newsicons\" >";
        echo _AM_XNI_DESCRIPTION . "<br />";
        echo "</td></tr></table>";
        break;
}

xoops_cp_footer();
?>
