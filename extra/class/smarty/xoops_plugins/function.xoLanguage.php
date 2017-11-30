<?php
/**
 * -------------------------------------------------------------------------------------
 * Smarty plugin for xoops : xoLanguage.php
 *
 * Type			: function
 * Name			: xoLanguage
 * Version		: 1.0
 * Author:		: DuGris <http://www.dugris.info>
 * Purpose		: Change language without changing the page in progress
 * -------------------------------------------------------------------------------------
 * Input:
 *				tag		=	field name
 *				language	=	value
 * -------------------------------------------------------------------------------------
 * Usage in xoops template :
 *
 *					<a href="<{xoLanguage tag="lang" language="french"}>" />Fran�ais</a>
 *					<a href="<{xoLanguage tag="lang" language="english"}>" />English</a>
 *
 * -------------------------------------------------------------------------------------
**/
include_once XOOPS_ROOT_PATH . '/Frameworks/smarty/xoSmartyFunctions.php';

/**
 * @param $params
 * @param $smarty
 */
function smarty_function_xoLanguage($params, &$smarty)
{
    $url = 'http://' . xoops_getenv('HTTP_HOST') . xoops_getenv('PHP_SELF');
    if (@!empty($params['tag']) && @!empty($params['language'])) {
        $query_array = array_filter(explode('&', xoops_getenv('QUERY_STRING')));
        $query_new = [];
        foreach ($query_array as $query) {
            if (substr($query, 0, strlen($params['tag']) + 1) != $params['tag'] . '=') {
                $vals = explode('=', $query);
                foreach (array_keys($vals) as $key) {
                    if (preg_match('/^a-z0-9$/i', $vals[$key])) {
                        $vals[$key] = urlencode($vals[$key]);
                    }
                }
                $query_new[] = implode('=', $vals);
            }
        }
        $query_string = '';
        $query_string = implode('&', array_map('htmlspecialchars', $query_new));
        $query_string .= empty($query_string)? '' : '&';
        
        //DNPROSSI - Find occurence of seo enabled module
        $seoOp = @$_GET['seoOp'];
        $seoArg = @$_GET['seoArg'];
        if (empty($seoOp) && @$_SERVER['PATH_INFO']) {
            //SEO mode is path-info
            $data = explode('/', $_SERVER['PATH_INFO']);
            $seoParts = explode('.', $data[1]);
            if (2 == count($seoParts)) {
                $seoOp = $seoParts[0];
                $seoArg = $seoParts[1];
            }
            if (3 == count($seoParts)) {
                $seoOp = $seoParts[1];
                $seoArg = $seoParts[2];
            }
            //trigger_error($seoOp.' - '.$seoArg, E_USER_WARNING);
        }
        
        if (@!empty($seoOp)) {
            switch ($seoOp) {
                case _MA_NW_SEO_TOPICS:
                    $url .= '?topic_id=' . $seoArg;
                break;
                case _MA_NW_SEO_ARTICLES:
                    $url .= '?storyid=' . $seoArg;
                break;
                case _MA_NW_SEO_PRINT:
                case _MA_NW_SEO_PDF:
            }
            $url .= $value . '&';
            unset($value);
        } else {
            $url .= '?' . $query_string ;
        }
        
        $url .= $params['tag'] . '=' . $params['language'];
        unset($params);
    }
    if (!defined('xoLanguage')) {
        define('xoLanguage', 1);
        $GLOBALS['xoopsLogger']->addExtra('plugin smarty for xoops => xoLanguage ', 'Loaded');
    }
    echo $url;
}
