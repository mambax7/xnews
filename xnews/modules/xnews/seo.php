<?php
if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

$seoOp = @$_GET['seoOp'];
$seoArg = @$_GET['seoArg'];
//trigger_error('out', E_USER_ERROR);
if ( empty($seoOp) && @$_SERVER['PATH_INFO'] ) {
	//SEO mode is path-info
	//
	//	Sample URL for path-info
	//	http://localhost/modules/xnews/index.php/articles.1/seo-is-active.html
	//
	$data = explode("/", $_SERVER['PATH_INFO']);
	
	$seoParts = explode('.', $data[1]);
	if ( count($seoParts) == 2 ) { 
		$seoOp = $seoParts[0];
		$seoArg = $seoParts[1];
	}
	if ( count($seoParts) == 3 ) { 
		$seoOp = $seoParts[1];
		$seoArg = $seoParts[2];
	}  
	
	// for multi-argument modules, where stroyid and topic_id both are required.
	// $seoArg = substr($data[1], strlen($seoOp) +1);
}

$seoMap = array(
		_MA_NW_SEO_TOPICS => 'index.php',
		_MA_NW_SEO_ARTICLES => 'article.php',
		_MA_NW_SEO_PRINT => 'print.php',
		_MA_NW_SEO_PDF => 'makepdf.php'
);

if ( !empty($seoOp) && !empty($seoMap[$seoOp]) )
{
	//module specific dispatching logic, other module must implement as 
	//per their requirements.
	$url_arr = explode('/modules/', $_SERVER['PHP_SELF']);
	$newUrl = $url_arr[0] . '/modules/' . NW_MODULE_DIR_NAME . '/' . $seoMap[$seoOp];
	
	$_ENV['PHP_SELF'] = $newUrl;
	$_SERVER['SCRIPT_NAME'] = $newUrl;
	$_SERVER['PHP_SELF'] = $newUrl;
	switch ($seoOp) {
		case _MA_NW_SEO_TOPICS:
			$_SERVER['REQUEST_URI'] = $newUrl . '?topic_id=' . $seoArg;
			$_GET['topic_id'] = $seoArg;
			break;
		case _MA_NW_SEO_ARTICLES:
		case _MA_NW_SEO_PRINT:
		case _MA_NW_SEO_PDF:
		default:
			$_SERVER['REQUEST_URI'] = $newUrl . '?storyid=' . $seoArg;
			$_GET['storyid'] = $seoArg;
		}	
	
	include ( $seoMap[$seoOp] );
	exit;
	//trigger_error('out', E_USER_WARNING);
}
?>
