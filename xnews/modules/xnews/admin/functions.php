<?php
// $Id: functions.php 8207 2011-11-07 04:18:27Z beckmi $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
/**
 * Function used to display a menu similar to the launcher on OS X
 *
 * Enable webmasters to navigate thru the module's features.
 * Each time you select an option in the admin panel of the news module, this option is highlighted in this menu
 *
 * NOTE : Please give credits if you copy this code !
 *
 * @package News
 * @author Instant Zero (http://www.instant-zero.com) & Dojo Javscript Toolkit
 * @copyright	(c) Instant Zero - http://www.instant-zero.com
 */
function adminmenu($currentoption = 0, $breadcrumb = '')
{
	global $xoopsModule, $xoopsConfig;

	include NW_MODULE_PATH . '/config.php';
	if (file_exists(NW_MODULE_PATH . '/language/' . $xoopsConfig['language'] . '/modinfo.php')) {
		include_once NW_MODULE_PATH . '/language/' . $xoopsConfig['language'] . '/modinfo.php';
	} else {
		include_once NW_MODULE_PATH . '/language/english/modinfo.php';
	}

	if(!$cfg['use_fun_menu']) {
		$tblColors = array('','','','','','','','','');
		if($currentoption>=0) {
			$tblColors[$currentoption] = 'current';
		}

		/* Nice buttons styles */
		echo "
	    	<style type='text/css'>
    		#buttontop { float:left; width:100%; background: #e7e7e7; font-size:93%; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }
    		#buttonbar { float:left; width:100%; background: #e7e7e7 url('" . NW_MODULE_URL . "/images/bg.png') repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 12px; }
    		#buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }
			#buttonbar li { display:inline; margin:0; padding:0; }
			#buttonbar a { float:left; background:url('" . NW_MODULE_URL . "/images/left_both.png') no-repeat left top; margin:0; padding:0 0 0 9px; border-bottom:1px solid #000; text-decoration:none; }
			#buttonbar a span { float:left; display:block; background:url('" . NW_MODULE_URL . "/images/right_both.png') no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
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
	filter: alpha(opacity =                         80);
	-moz-opacity: 0.8;
	-webkit-opacity: 0.8;
	-khtml-opacity: 0.8;
	opacity: 0.8;
}
			</style>
    	";

		echo "<div id='buttontop'>";
		echo "<table style=\"width: 100%; padding: 0; \" cellspacing=\"0\"><tr>";
		echo "<td style=\"width: 60%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;\"><a href=\"index.php\">" . _AM_NW_INDEX . "</a> | <a class=\"nobutton\" href=\"".XOOPS_URL."/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=".$xoopsModule->getVar('mid')."\">" . _AM_NW_GENERALSET . "</a> | <a href=\"../index.php\">" . _AM_NW_GOTOMOD . "</a> | <a href=\"#\">" . _AM_NW_HELP . "</a> | <a href='index.php?op=verifydb'>". _AM_NW_VERIFY_TABLES ."</a></td>";
		echo "<td style=\"width: 40%; font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;\"><b>" . $xoopsModule->name() . "  " . _AM_NW_MODULEADMIN . "</b> " . $breadcrumb . "</td>";
		echo '</tr></table>';
		echo '</div>';

		echo "<div id='buttonbar'>";
		echo '<ul>';
		echo "<li id='" . $tblColors[0] . "'><a href=\"index.php?op=topicsmanager\"\"><span>"._MI_NW_ADMENU2 ."</span></a></li>\n";
		echo "<li id='" . $tblColors[1] . "'><a href=\"index.php?op=newarticle\"><span>" . _MI_NW_ADMENU3 . "</span></a></li>\n";
		echo "<li id='" . $tblColors[2] . "'><a href=\"groupperms.php\"><span>" . _MI_NW_GROUPPERMS . "</span></a></li>\n";
		echo "<li id='" . $tblColors[3] . "'><a href=\"index.php?op=prune\"><span>" . _MI_NW_PRUNENEWS . "</span></a></li>\n";
		echo "<li id='" . $tblColors[4] . "'><a href=\"index.php?op=export\"><span>" . _MI_NW_EXPORT . "</span></a></li>\n";
		echo "<li id='" . $tblColors[5] . "'><a href=\"index.php?op=configurenewsletter\"><span>" . _MI_NW_NEWSLETTER . "</span></a></li>\n";
		echo "<li id='" . $tblColors[6] . "'><a href=\"index.php?op=stats\"><span>" . _MI_NW_STATS . "</span></a></li>\n";
		echo "<li id='" . $tblColors[7] . "'><a href=\"index.php?op=metagen\"><span>" . _MI_NW_METAGEN . "</span></a></li>\n";
		echo "<li id='" . $tblColors[8] . "'><a href='" . XOOPS_URL . "/modules/" . "x" . "news" . "/admin/index.php?op=cloner'><span>" . _MI_NW_CLONER . "</span></a></li>\n";
		echo '</ul></div>';
		echo '<br /><br /><pre>&nbsp;</pre><pre>&nbsp;</pre>';
	} else {
		?>
<script
	type="text/javascript" src="../js/dojo.js"></script>
<script language="JavaScript" type="text/javascript">
		dojo.require("dojo.widget.FisheyeList");
		dojo.hostenv.writeIncludes();
	</script>
<script>
		function load_app(id){
 			urltogo = new Array("../../system/admin.php?fct=preferences&op=showmod&mod=<?php echo $xoopsModule->getVar('mid'); ?>","../index.php","#","index.php?op=verifydb","index.php?op=topicsmanager","index.php?op=newarticle","groupperms.php","index.php?op=prune","index.php?op=export","index.php?op=configurenewsletter","index.php?op=stats","index.php?op=metagen","index.php?op=cloner");
    		window.location.href = urltogo[id];
		}
	</script>

<style>
.dojoHtmlFisheyeListBar {
	margin: 0 auto;
	text-align: center;
}

.outerbar {
	background-color: #ffffff;
	text-align: center;
	position: relative;
	left: 0px;
	top: 0px;
	width: 100%;
}
</style>

<div class="outerbar">
<div class="dojo-FisheyeList" dojo:itemWidth="50" dojo:itemHeight="50"
	dojo:itemMaxWidth="200" dojo:itemMaxHeight="200"
	dojo:orientation="horizontal" dojo:effectUnits="2"
	dojo:itemPadding="10" dojo:attachEdge="top" dojo:labelEdge="bottom"
	dojo:enableCrappySvgSupport="false">

<div class="dojo-FisheyeListItem" onClick="load_app(0)"
	dojo:iconsrc="../images/options.png"
	caption="<?php echo _AM_NW_GENERALSET; ?>"></div>
<div class="dojo-FisheyeListItem" onClick="load_app(1);"
	dojo:iconsrc="../images/home.png"
	caption="<?php echo _AM_NW_GOTOMOD; ?>"></div>
<div class="dojo-FisheyeListItem" onClick="load_app(2);"
	dojo:iconsrc="../images/help.png" caption="<?php echo _AM_NW_HELP; ?>">
</div>
<div class="dojo-FisheyeListItem" onClick="load_app(3);"
	dojo:iconsrc="../images/maintain.png"
	caption="<?php echo _AM_NW_VERIFY_TABLES; ?>"></div>
<div class="dojo-FisheyeListItem" onClick="load_app(4);"
	dojo:iconsrc="../images/topics.png"
	caption="<?php echo _MI_NW_ADMENU2; ?>"></div>
<div class="dojo-FisheyeListItem" onClick="load_app(5);"
	dojo:iconsrc="../images/articles.png"
	dojo:caption="<?php echo _MI_NW_ADMENU3; ?>"></div>
<div class="dojo-FisheyeListItem" onClick="load_app(6);"
	dojo:iconsrc="../images/permissions.png"
	dojo:caption="<?php echo _MI_NW_GROUPPERMS; ?>"></div>
<div class="dojo-FisheyeListItem" onClick="load_app(7);"
	dojo:iconsrc="../images/purge.png"
	dojo:caption="<?php echo _MI_NW_PRUNENEWS; ?>"></div>
<div class="dojo-FisheyeListItem" onClick="load_app(8);"
	dojo:iconsrc="../images/export.png"
	dojo:caption="<?php echo _MI_NW_EXPORT; ?>"></div>
<div class="dojo-FisheyeListItem" onClick="load_app(9);"
	dojo:iconsrc="../images/newsletter.png"
	dojo:caption="<?php echo _MI_NW_NEWSLETTER; ?>"></div>
<div class="dojo-FisheyeListItem" onClick="load_app(10);"
	dojo:iconsrc="../images/statistics.png"
	dojo:caption="<?php echo _MI_NW_STATS; ?>"></div>
<div class="dojo-FisheyeListItem" onClick="load_app(11);"
	dojo:iconsrc="../images/metagen.png"
	dojo:caption="<?php echo _MI_NW_METAGEN; ?>"></div>
<div class="dojo-FisheyeListItem" onClick="load_app(12);"
	dojo:iconsrc="../images/cloner.png"
	dojo:caption="<?php echo _MI_NW_CLONER; ?>"></div>

</div>
</div>
		<?php
	}
}


function nw_collapsableBar($tablename = '', $iconname = '')
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
