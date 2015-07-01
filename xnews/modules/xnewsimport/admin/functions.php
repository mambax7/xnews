<?php
function adminmenu($currentoption = 0, $breadcrumb = '')
{
 	global $xoopsModule, $xoopsConfig;

	include XNI_MODULE_PATH . '/config.php';
	if (file_exists(XNI_MODULE_PATH . '/language/' . $xoopsConfig['language'] . '/modinfo.php')) {
		include_once XNI_MODULE_PATH . '/language/' . $xoopsConfig['language'] . '/modinfo.php';
	} else {
		include_once XNI_MODULE_PATH . '/language/english/modinfo.php';
	}

	$tblColors = array('','');
	if($currentoption>=0) {
		$tblColors[$currentoption] = 'current';
	}

	echo "
	   	<style type='text/css'>
			#buttontop { float:left; width:100%; background: #e7e7e7; font-size:93%; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }
			#buttonbar { float:left; width:100%; background: #e7e7e7 url('" . XNI_MODULE_URL . "/images/bg.png') repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 12px; }
			#buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }
			#buttonbar li { display:inline; margin:0; padding:0; }
			#buttonbar a { float:left; background:url('" . XNI_MODULE_URL . "/images/left_both.png') no-repeat left top; margin:0; padding:0 0 0 9px; border-bottom:1px solid #000; text-decoration:none; }
			#buttonbar a span { float:left; display:block; background:url('" . XNI_MODULE_URL . "/images/right_both.png') no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
			/* Commented Backslash Hack hides rule from IE5-Mac \*/
			#buttonbar a span {float:none;}
			/* End IE5-Mac hack */
			#buttonbar a:hover span { color:#333; }
			#buttonbar #current a { background-position:0 -150px; border-width:0; }
			#buttonbar #current a span { background-position:100% -150px; padding-bottom:5px; color:#333; }
			#buttonbar a:hover { background-position:0% -150px; }
			#buttonbar a:hover span { background-position:100% -150px; }
		
			#xo-newsicons a {
				display: block;
				float: left;
				text-align: center !important;
				height: 80px !important;
				width: 90px !important;
				vertical-align: middle;
				text-decoration: none;
				background-color: #f0f0f0;
				padding: 2px;
				margin: 3px;
				color: #666666;
				border: 1px solid #f9f9f9;
				-moz-border-radius: 9px;
				-webkit-border-radius: 9px;
				-khtml-border-radius: 9px;
				border-radius: 9px;
			}
			#xo-newsicons a:hover {
				color: #1E90FF;
				border-left: 1px solid #eee;
				border-top: 1px solid #eee;
				border-right: 1px solid #ccc;
				border-bottom: 1px solid #ccc;
				background: #f9f9f9;
				filter: alpha(opacity = 80);
				-moz-opacity: 0.8;
				-webkit-opacity: 0.8;
				-khtml-opacity: 0.8;
				opacity: 0.8;
			}		
		</style>
    ";
// | <a href='import.php'>". 'Import' ."</a>
	echo "<div id='buttontop'>";
	echo "<table style=\"width: 100%; padding: 0; \" cellspacing=\"0\"><tr>";
	echo "<td style=\"width: 60%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;\"><a href='" . XOOPS_URL . "/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $xoopsModule->getVar("mid") . "'>" . _AM_XNI_PREFERENCES . "</a> | <a href=\"#\">" . _AM_XNI_HELP . "</a> | <a href=\"index.php\">" . _AM_XNI_IMPORT_GOTOINDEX . "</a></td>";
	echo "<td style=\"width: 40%; font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;\"><b>" . $xoopsModule->name() . "  " . _AM_XNI_MODULEADMIN . "</b></td>";
	echo '</tr></table>';
	echo '</div>';
	echo "<div id='buttonbar'>";
	echo '<ul>';
	echo "<li id='" . $tblColors[0] . "'><a href=\"index.php?op=import\"\"><span>". _MI_XNI_IMPORT ."</span></a></li>\n";
	echo '<li id="' . $tblColors[1] . '"><a href="' . XOOPS_URL . '/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule->getVar("mid") . '"> <span>' . _MI_XNI_PREFERENCES . '</span></a></li>';
	echo '</ul></div>';
	echo '<br /><br /><pre>&nbsp;</pre><pre>&nbsp;</pre>';
}

function xni_collapsableBar($tablename = '', $iconname = '')
{

    ?>
	<script type="text/javascript"><!--
	function goto_URL(object)
	{
		window.location.href = object.options[object.selectedIndex].value;
	}

	function toggle(id)
	{
		if (document.getElementById) { obj = document.getElementById(id); }
		if (document.all) { obj = document.all[id]; }
		if (document.layers) { obj = document.layers[id]; }
		if (obj) {
			if (obj.style.display == "none") {
				obj.style.display = "";
			} else {
				obj.style.display = "none";
			}
		}
		return false;
	}

	var iconClose = new Image();
	iconClose.src = '../images/close12.gif';
	var iconOpen = new Image();
	iconOpen.src = '../images/open12.gif';

	function toggleIcon ( iconName )
	{
		if ( document.images[iconName].src == window.iconOpen.src ) {
			document.images[iconName].src = window.iconClose.src;
		} else if ( document.images[iconName].src == window.iconClose.src ) {
			document.images[iconName].src = window.iconOpen.src;
		}
		return;
	}

	//-->
	</script>
	<?php
	echo "<h4 style=\"color: #2F5376; margin: 6px 0 0 0; \"><a href='#' onClick=\"toggle('" . $tablename . "'); toggleIcon('" . $iconname . "');\">";
}
?>
