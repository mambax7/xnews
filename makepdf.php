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
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author     XOOPS Development Team
 */


// 19-12-2008
// DNPROSSI - Made a few changes to 1.63
// Corrected UTF-8 problems
// Corrected xlanguage incompatability
//
// Moved /pdf to Frameworks/tcpdf - for more XOOPS like system
// removed file pdf.php called from /news/templates/nw_news_article.html
// changed link to pdf.php in /news/templates/nw_news_article.html
//   to module /news/makepdf.php wirh calls to Frameworks/tcpdf
// UPDATED TCPDF to latest - 4.4.006 (2008-12-11)
// ------------------------------------------------------------------------- //

use Xoopsmodules\xnews;

require_once __DIR__ . '/header.php';

/** @var \Xoopsmodules\xnews\Helper $helper */
$helper = \Xoopsmodules\xnews\Helper::getInstance();

error_reporting(0);
require_once __DIR__ . '/header.php';

if (!is_file(XOOPS_ROOT_PATH . '/class/libraries/vendor/tecnickcom/tcpdf/tcpdf.php')) {
    redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewtopic.php?topic_id=' . $topic_id, 3, 'TCPDF for Xoops not installed');
} else {
    require_once XOOPS_ROOT_PATH . '/class/libraries/vendor/tecnickcom/tcpdf/tcpdf.php';
}

require_once XNEWS_MODULE_PATH . '/class/class.newsstory.php';


// Verifications on the article
$storyid = isset($_GET['storyid']) ? (int)$_GET['storyid'] : 0;

if (empty($storyid)) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _MD_XNEWS_NOSTORY);
}

$article = new XNewsStory($storyid);
// Not yet published
if (0 == $article->published() || $article->published() > time()) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _MD_XNEWS_NOSTORY);
}

// Expired
if (0 != $article->expired() && $article->expired() < time()) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _MD_XNEWS_NOSTORY);
}

$gpermHandler = xoops_getHandler('groupperm');
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
if (!isset($xoopsModule)) {
    $moduleHandler = xoops_getHandler('module');
    $xoopsModule   = $moduleHandler->getByDirname(XNEWS_MODULE_DIRNAME);
}

if (!$gpermHandler->checkRight('nw_view', $article->topicid(), $groups, $xoopsModule->getVar('mid'))) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _NOPERM);
}

//$filename = XNEWS_MODULE_PATH . '/language/' . $xoopsConfig['language'] . '/main.php';
//if (file_exists($filename)) {
//    require_once $filename;
//} else {
//    require_once XNEWS_MODULE_PATH . '/language/english/main.php';
//}
//

$storypage  = isset($_GET['page']) ? (int)$_GET['page'] : 0;
//$dateformat = nw_getmoduleoption('dateformat', NW_MODULE_DIR_NAME);
$dateformat = $helper->getConfig('dateformat');
$hcontent   = '';






$pdf_data['title']    = $article->title();
$pdf_data['subtitle'] = $article->topic_title();

$pdf_data['subsubtitle'] = $xoopsModule->name();
$pdf_data['date']        = ': ' . date(_DATESTRING, $article->published());

$memberHandler = xoops_getHandler('member');
$author        = $memberHandler->getUser($article->uid());
if ($author->getVar('name')) {
    $pdf_data['author'] = $author->getVar('name') . '(' . $author->getVar('uname') . ')';
} else {
    $pdf_data['author'] = $author->getVar('uname');
}

ob_start();
$GLOBALS['xoopsTpl']->display('db:nw_news_article_pdf.tpl');
$content = ob_get_contents();
ob_end_clean();

$pdf_data['content'] = $content;

define('PDF_CREATOR', $GLOBALS['xoopsConfig']['sitename']);
define('PDF_AUTHOR', $pdf_data['author']);
define('PDF_HEADER_TITLE', $pdf_data['title']);
define('PDF_HEADER_STRING', $pdf_data['subtitle']);
define('PDF_HEADER_LOGO', 'logo.png');
define('K_PATH_IMAGES', XOOPS_ROOT_PATH . '/images/');

//$filename = XOOPS_ROOT_PATH . '/Frameworks/tcpdf/config/lang/' . _LANGCODE . '.php';
//if (file_exists($filename)) {
//    require_once $filename;
//} else {
//    require_once XOOPS_ROOT_PATH . '/Frameworks/tcpdf/config/lang/en.php';
//}

$dateformat = $xnews->getConfig('dateformat');

//DNPROSSI Added - xlanguage installed and active
$moduleHandler = xoops_getHandler('module');
$xlanguage     = $moduleHandler->getByDirname('xlanguage');
if (is_object($xlanguage) && true === $xlanguage->getVar('isactive')) {
    $xlang = true;
} else {
    $xlang = false;
}

$tempcontent = $article->title() . ' ' . $article->topic_title() . ' ' . $article->uname() . ' ' . $article->hometext() . ' ' . $article->bodytext();
$multylang   = nw_detect_utf8_lang_encoding(urlencode($tempcontent));

$content = '';
$content .= '<b><i><u>'
            . $myts->undoHtmlSpecialChars($article->title())
            . '</u></i></b><br><b>'
            . $myts->undoHtmlSpecialChars($article->topic_title())
            . '</b><br>'
            . _POSTEDBY
            . ' : '
            . $myts->undoHtmlSpecialChars($article->uname())
            . '<br>'
            . _MD_XNEWS_POSTEDON
            . ' '
            . formatTimestamp($article->published(), $dateformat)
            . '<br><br><br>';
//$content .= $myts->undoHtmlSpecialChars($article->hometext()) . '<br><br><br>' . $myts->undoHtmlSpecialChars($article->bodytext());
//$content = str_replace('[pagebreak]','<br>',$content);
$content .= $myts->undoHtmlSpecialChars($article->hometext()) . '<br>' . $myts->undoHtmlSpecialChars($article->bodytext());
$content = str_replace('[pagebreak]', '<tcpdf method="AddPage">', $content);

//DNPROSSI Scan for mixed single-multibyte language in content and use later
//$multylang = nw_detect_utf8_lang_encoding($content);

//DNPROSSI Added - Get correct language and remove tags from text to be sent to PDF
if (true === $xlang) {
    require_once XOOPS_ROOT_PATH . '/modules/xlanguage/include/functions.php';
    $content = xlanguage_ml($content);
}

$pdf          = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$doc_title    = $myts->undoHtmlSpecialChars($article->title());
$doc_keywords = 'XOOPS';

//DNPROSSI ADDED gbsn00lp chinese to tcpdf fonts dir
if (_LANGCODE == 'cn' || 'cn' === $multylang) {
    $pdf->SetFont('gbsn00lp', '', 10);
}
if (_LANGCODE === 'fa' || 'fa' === $multylang) {
    // RTL direction for persian language
    $pdf->setRTL(true);
    //$pdf->SetFont('dejavusans', '', 12); almohanad
    $pdf->SetFont('almohanad', '', 18);
}

// set document information
//$pdf->SetCreator($pdf_data['author']);
//$pdf->SetAuthor($pdf_data['author']);
//$pdf->SetTitle($pdf_data['title']);
//$pdf->SetSubject($pdf_data['subtitle']);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle($doc_title);
$pdf->SetSubject($doc_title);
$pdf->SetKeywords($doc_keywords);

// set default header data
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
//$pdf->SetHeaderData('', '', $firstLine, $secondLine);
//$pdf->SetHeaderData('logo_example.png', '25', $firstLine, $secondLine);
//$firstLine = 'Your Site Name';
//$secondLine = 'Any thing you feel writing here';
//$pdf->SetHeaderData('logo_file.png', '25', $firstLine, $secondLine);

//UTF-8 char sample
//$pdf->SetHeaderData(PDF_HEADER_LOGO, '25', 'Éèéàùìò', $multylang);//$article->title());

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 10, PDF_MARGIN_RIGHT);
//set auto page breaks
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(1); //set image scale factor

//DNPROSSI ADDED FOR SCHINESE - PERSIAN
if (_LANGCODE === 'cn' || 'cn' === $multylang) {
    $pdf->setHeaderFont(['gbsn00lp', '', 10]);
    $pdf->setFooterFont(['gbsn00lp', '', 10]);
} elseif (_LANGCODE === 'fa' || 'fa' === $multylang) {
    $pdf->setHeaderFont(['almohanad', '', 18]);
    $pdf->setFooterFont(['almohanad', '', 18]);
} else {
    $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
    $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
}

$pdf->setLanguageArray($l); //set language items

//initialize document
//$pdf->AliasNbPages();

//initialize document
$pdf->Open();


// ***** For Testing Purposes
/*$pdf->AddPage();

// print a line using Cell()
*$pdf->Cell(0, 10, K_PATH_URL. '  ---- Path Url', 1, 1, 'C');
$pdf->Cell(0, 10, K_PATH_MAIN. '  ---- Path Main', 1, 1, 'C');
$pdf->Cell(0, 10, K_PATH_FONTS. '  ---- Path Fonts', 1, 1, 'C');
$pdf->Cell(0, 10, K_PATH_IMAGES. '  ---- Path Images', 1, 1, 'C');
*/
// ***** End Test

$pdf->AddPage();
$pdf->writeHTML($content, true, 0);
//Added for buffer error in TCPDF when using chinese charset
ob_end_clean();
$pdf->Output();
