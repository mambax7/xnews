<?php
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

error_reporting(0);
include_once __DIR__ . '/header.php';

include_once XNEWS_MODULE_PATH . '/class/class.newsstory.php';

// Verifications on the article
$storyid = isset($_GET['storyid']) ? intval($_GET['storyid']) : 0;

if (empty($storyid))  {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _MA_NW_NOSTORY);
    exit();
}

$article = new nw_NewsStory($storyid);
// Not yet published
if ($article->published() == 0 || $article->published() > time()) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _MA_NW_NOSTORY);
    exit();
}

// Expired
if ($article->expired() != 0 && $article->expired() < time()) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _MA_NW_NOSTORY);
    exit();
}

$gperm_handler =& xoops_gethandler('groupperm');
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
if (!isset($xoopsModule)) {
    $module_handler =& xoops_gethandler('module');
    $xoopsModule =& $module_handler->getByDirname(XNEWS_MODULE_DIRNAME);
}

if (!$gperm_handler->checkRight('nw_view', $article->topicid(), $groups, $xoopsModule->getVar('mid'))) {
    redirect_header(XNEWS_MODULE_URL . '/index.php', 3, _NOPERM);
    exit();
}

require_once XOOPS_ROOT_PATH . '/Frameworks/tcpdf/tcpdf.php';

$filename = XNEWS_MODULE_PATH . '/language/' . $xoopsConfig['language'] . '/main.php';
if (file_exists( $filename)) {
    include_once $filename;
} else {
    include_once XNEWS_MODULE_PATH . '/language/english/main.php';
}

$filename = XOOPS_ROOT_PATH . '/Frameworks/tcpdf/config/lang/' . _LANGCODE . '.php';
if(file_exists($filename)) {
    include_once $filename;
} else {
    include_once XOOPS_ROOT_PATH . '/Frameworks/tcpdf/config/lang/en.php';
}

$dateformat = $xnews->getConfig('dateformat');

//DNPROSSI Added - xlanguage installed and active 
$module_handler =& xoops_gethandler('module');
$xlanguage = $module_handler->getByDirname('xlanguage');
if (is_object($xlanguage) && $xlanguage->getVar('isactive') == true) {
    $xlang = true;
} else {
    $xlang = false;
}

$tempcontent = $article->title() . ' ' . $article->topic_title() . ' ' . $article->uname() . ' ' . $article->hometext()  . ' ' . $article->bodytext();
$multylang = nw_detect_utf8_lang_encoding(urlencode($tempcontent));

$content = '';
$content .= '<b><i><u>' . $myts->undoHtmlSpecialChars($article->title()) . '</u></i></b><br /><b>' . $myts->undoHtmlSpecialChars($article->topic_title()) . '</b><br />' . _POSTEDBY.' : ' . $myts->undoHtmlSpecialChars($article->uname()) . '<br />' . _MA_NW_POSTEDON . ' ' . formatTimestamp($article->published(), $dateformat) . '<br /><br /><br />';
//$content .= $myts->undoHtmlSpecialChars($article->hometext()) . '<br /><br /><br />' . $myts->undoHtmlSpecialChars($article->bodytext());
//$content = str_replace('[pagebreak]','<br />',$content);
$content .= $myts->undoHtmlSpecialChars($article->hometext()) . '<br />' . $myts->undoHtmlSpecialChars($article->bodytext());
$content = str_replace('[pagebreak]', '<tcpdf method="AddPage" />', $content);

//DNPROSSI Scan for mixed single-multibyte language in content and use later
//$multylang = nw_detect_utf8_lang_encoding($content);

//DNPROSSI Added - Get correct language and remove tags from text to be sent to PDF
if ($xlang == true) { 
   include_once XOOPS_ROOT_PATH . '/modules/xlanguage/include/functions.php';
   $content = xlanguage_ml($content);
}

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$doc_title = $myts->undoHtmlSpecialChars($article->title());
$doc_keywords = 'XOOPS';

//DNPROSSI ADDED gbsn00lp chinese to tcpdf fonts dir
if (_LANGCODE == "cn" || $multylang == "cn") {
    $pdf->SetFont('gbsn00lp', '', 10);
} 
if (_LANGCODE == "fa" || $multylang == "fa") {
    // RTL direction for persian language
    $pdf->setRTL(true);
    //$pdf->SetFont('dejavusans', '', 12); almohanad
    $pdf->SetFont('almohanad', '', 18);
}

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetTitle($doc_title);
$pdf->SetSubject($doc_title);
$pdf->SetKeywords($doc_keywords);

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
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
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(1); //set image scale factor

//DNPROSSI ADDED FOR SCHINESE - PERSIAN
if ( _LANGCODE == "cn" || $multylang == "cn" ) { 
    $pdf->setHeaderFont(Array('gbsn00lp', '', 10));
    $pdf->setFooterFont(Array('gbsn00lp', '', 10));
} elseif (_LANGCODE == "fa" || $multylang == "fa") {
    $pdf->setHeaderFont(Array('almohanad', '', 18));
    $pdf->setFooterFont(Array('almohanad', '', 18));
} else {
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
}

$pdf->setLanguageArray($l); //set language items

//initialize document
$pdf->AliasNbPages();

/*$pdf->AddPage();

// print a line using Cell()
*$pdf->Cell(0, 10, K_PATH_URL. '  ---- Path Url', 1, 1, 'C');
$pdf->Cell(0, 10, K_PATH_MAIN. '  ---- Path Main', 1, 1, 'C');
$pdf->Cell(0, 10, K_PATH_FONTS. '  ---- Path Fonts', 1, 1, 'C');
$pdf->Cell(0, 10, K_PATH_IMAGES. '  ---- Path Images', 1, 1, 'C');
*/
$pdf->AddPage();
$pdf->writeHTML($content, true, 0);
//Added for buffer error in TCPDF when using chinese charset
ob_end_clean();
$pdf->Output();
