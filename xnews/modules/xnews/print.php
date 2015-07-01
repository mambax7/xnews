<?php
/**
 * Print an article
 *
 * This page is used to print an article. The advantage of this script is that you
 * only see the article and nothing else.
 *
 * @package News
 * @author Xoops Modules Dev Team
 * @copyright	(c) The Xoops Project - www.xoops.org
 *
 * Parameters received by this page :
 * @page_param int              storyid Id of news to print
 * @page_title                  Story's title - Printer Friendly Page - Topic's title - Site's name
 * @template_name               This page does not use any template
 *
*/
include_once __DIR__ . '/header.php';
include_once XNEWS_MODULE_PATH . '/class/class.newsstory.php';

$storyid = isset($_GET['storyid']) ? intval($_GET['storyid']) : 0;
if (empty($storyid)) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 2, _MA_NW_NOSTORY);
}

// Verify that the article is published
$storyObj = new nw_NewsStory($storyid);
// Not yet published
if ($storyObj->published() == 0 || $storyObj->published() > time()) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 2, _MA_NW_NOSTORY);
    exit();
}

// Expired
if ($storyObj->expired() != 0 && $storyObj->expired() < time()) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 2, _MA_NW_NOSTORY);
    exit();
}

// Verify permissions
$gperm_handler =& xoops_gethandler('groupperm');
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
if (!$gperm_handler->checkRight('nw_view', $storyObj->topicid(), $groups, $xoopsModule->getVar('mid'))) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _NOPERM);
    exit();
}

$xoops_meta_keywords='';
$xoops_meta_description='';


if (trim($storyObj->keywords()) != '') {
    $xoops_meta_keywords = $storyObj->keywords();
} else {
    $xoops_meta_keywords = nw_createmeta_keywords($storyObj->hometext() . ' ' . $storyObj->bodytext());
}

if (trim($storyObj->description())!= '') {
    $xoops_meta_description = $storyObj->description();
} else {
    $xoops_meta_description = strip_tags($storyObj->title());
}


function PrintPage() {
    global $xoopsConfig, $xoopsModule, $storyObj, $xoops_meta_keywords, $xoops_meta_description;
    $myts =& MyTextSanitizer::getInstance();
    $datetime = formatTimestamp($storyObj->published(), $xnews->getConfig('dateformat'));
?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo _LANGCODE; ?>" lang="<?php echo _LANGCODE; ?>">
<?php
    echo "<head>\n";
    echo '<title>' . $myts->htmlSpecialChars($storyObj->title()) . ' - ' . _MA_NW_PRINTER . ' - ' . $myts->htmlSpecialChars($storyObj->topic_title()) . ' - ' . $xoopsConfig['sitename'].'</title>';
    echo '<meta http-equiv="Content-Type" content="text/html; charset=' . _CHARSET . '" />';
    echo '<meta name="AUTHOR" content="' . $xoopsConfig['sitename'] . '" />';
    echo '<meta name="keywords" content="' . $xoops_meta_keywords . '" />';
    echo '<meta name="COPYRIGHT" content="Copyright (c) 2006 by ' . $xoopsConfig['sitename'] . '" />';
    echo '<meta name="DESCRIPTION" content="' . $xoops_meta_description . '" />';
    echo '<meta name="GENERATOR" content="XOOPS" />';
    $supplemental = '';
    if ($xnews->getConfig('footNoteLinks')) {
        $supplemental = "footnoteLinks('content','content'); ";
?>
    <script type="text/javascript">
    // <![CDATA[
    /*------------------------------------------------------------------------------
    Function:       footnoteLinks()
    Author:         Aaron Gustafson (aaron at easy-designs dot net)
    Creation Date:  8 May 2005
    Version:        1.3
    Homepage:       http://www.easy-designs.net/code/footnoteLinks/
    License:        Creative Commons Attribution-ShareAlike 2.0 License
                    http://creativecommons.org/licenses/by-sa/2.0/
    Note:           This version has reduced functionality as it is a demo of
                    the script's development
    ------------------------------------------------------------------------------*/
    function footnoteLinks(containerID,targetID) {
        if (!document.getElementById ||
            !document.getElementsByTagName ||
            !document.createElement) return false;
        if (!document.getElementById(containerID) ||
            !document.getElementById(targetID)) return false;
        var container = document.getElementById(containerID);
        var target    = document.getElementById(targetID);
        var h2        = document.createElement('h2');
        addClass.apply(h2,['printOnly']);
        var h2_txt    = document.createTextNode('<?php echo _MA_NW_LINKS; ?>');
        h2.appendChild(h2_txt);
        var coll = container.getElementsByTagName('*');
        var ol   = document.createElement('ol');
        addClass.apply(ol,['printOnly']);
        var myArr = [];
        var thisLink;
        var num = 1;
        for (var i=0; i<coll.length; i++) {
            if ( coll[i].getAttribute('href') ||
                coll[i].getAttribute('cite') ) {
                thisLink = coll[i].getAttribute('href') ? coll[i].href : coll[i].cite;
                var note = document.createElement('sup');
                addClass.apply(note,['printOnly']);
                var note_txt;
                var j = inArray.apply(myArr,[thisLink]);
                if ( j || j===0 ) { // if a duplicate
                    // get the corresponding number from
                    // the array of used links
                    note_txt = document.createTextNode(j+1);
                } else { // if not a duplicate
                    var li     = document.createElement('li');
                    var li_txt = document.createTextNode(thisLink);
                    li.appendChild(li_txt);
                    ol.appendChild(li);
                    myArr.push(thisLink);
                    note_txt = document.createTextNode(num);
                    num++;
                }
                note.appendChild(note_txt);
                if (coll[i].tagName.toLowerCase() == 'blockquote') {
                    var lastChild = lastChildContainingText.apply(coll[i]);
                    lastChild.appendChild(note);
                } else {
                    coll[i].parentNode.insertBefore(note, coll[i].nextSibling);
                }
            }
        }
        target.appendChild(h2);
        target.appendChild(ol);
        return true;
    }
    // ]]>
    </script>
    <script type="text/javascript">
    // <![CDATA[
    /*------------------------------------------------------------------------------
    Excerpts from the jsUtilities Library
    Version:        2.1
    Homepage:       http://www.easy-designs.net/code/jsUtilities/
    License:        Creative Commons Attribution-ShareAlike 2.0 License
                    http://creativecommons.org/licenses/by-sa/2.0/
    Note:           If you change or improve on this script, please let us know.
    ------------------------------------------------------------------------------*/
    if(Array.prototype.push == null) {
        Array.prototype.push = function(item) {
            this[this.length] = item;
            return this.length;
        };
    };
    // ---------------------------------------------------------------------
    //                  function.apply (if unsupported)
    //           Courtesy of Aaron Boodman - http://youngpup.net
    // ---------------------------------------------------------------------
    if (!Function.prototype.apply) {
        Function.prototype.apply = function(oScope, args) {
            var sarg = [];
            var rtrn, call;
            if (!oScope) oScope = window;
            if (!args) args = [];
            for (var i = 0; i < args.length; i++) {
                sarg[i] = "args["+i+"]";
            };
            call = "oScope.__applyTemp__(" + sarg.join(",") + ");";
            oScope.__applyTemp__ = this;
            rtrn = eval(call);
            oScope.__applyTemp__ = null;
            return rtrn;
        };
    };
    function inArray(needle) {
        for (var i=0; i < this.length; i++) {
            if (this[i] === needle) {
                return i;
            }
        }
        return false;
    }
    function addClass(theClass) {
        if (this.className != '') {
            this.className += ' ' + theClass;
        } else {
            this.className = theClass;
        }
    }
    function lastChildContainingText() {
        var testChild = this.lastChild;
        var contentCntnr = ['p','li','dd'];
        while (testChild.nodeType != 1) {
            testChild = testChild.previousSibling;
        }
        var tag = testChild.tagName.toLowerCase();
        var tagInArr = inArray.apply(contentCntnr, [tag]);
        if (!tagInArr && tagInArr!==0) {
            testChild = lastChildContainingText.apply(testChild);
        }
      return testChild;
    }
    // ]]>
    </script>
    <style type="text/css" media="screen">
    .printOnly {
      display: none;
    }
    </style>
<?php
    }
    echo '</head>';
    echo '<body bgcolor="#ffffff" text="#000000" onload="' . $supplemental . ' window.print()">
        <div id="content">
        <table border="0"><tr><td align="center">
        <table border="0" width="100%" cellpadding="0" cellspacing="1" bgcolor="#000000"><tr><td>
        <table border="0" width="100%" cellpadding="20" cellspacing="1" bgcolor="#ffffff"><tr><td align="center">
        <img src="' . XOOPS_URL . '/images/logo.gif" border="0" alt="" /><br /><br />
        <h3>' . $storyObj->title() . '</h3>
        <small><b>' . _MA_NW_DATE . '</b>&nbsp;' . $datetime . ' | <b>' . _MA_NW_TOPICC . '</b>&nbsp;' . $myts->htmlSpecialChars($storyObj->topic_title()) . '</small><br /><br /></td></tr>';
    echo '<tr valign="top" style="font:12px;"><td>' . $storyObj->hometext() . '<br />';
    $bodytext = $storyObj->bodytext();
    $bodytext = str_replace('[pagebreak]', "<br style=\"page-break-after:always;\" />", $bodytext);
    if ($bodytext != ''){
        echo $bodytext . '<br /><br />';
    }
    echo '</td></tr></table></td></tr></table>
        <br /><br />';
    printf(_MA_NW_THISCOMESFROM, htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES));
    echo '<br /><a href="' . XOOPS_URL . '/">'.XOOPS_URL . '</a><br /><br />';
    echo _MA_NW_URLFORSTORY . ' <!-- Tag below can be used to display Permalink image --><!--img src="' . XNEWS_MODULE_URL . '/images/x.gif" /--><br />
        <a class="ignore" href="' . XNEWS_MODULE_URL . '/article.php?storyid=' . $storyObj->storyid() . '">' . XNEWS_MODULE_URL . '/article.php?storyid=' . $storyObj->storyid() . '</a>
        </td></tr></table></div>
        </body>
        </html>
        ';
}

PrintPage();
