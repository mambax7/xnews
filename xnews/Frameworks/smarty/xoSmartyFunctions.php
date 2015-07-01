<?php
/**
 * -------------------------------------------------------------------------------------
 * Common functions for Xoops's smarty plugins : xoSmartyFunctions.php
 *
 * Type			: common functions
 * Author:		: DuGris <http://www.dugris.info>
 * Purpose		: Common functions for Xoops's smarty plugins
 * -------------------------------------------------------------------------------------
**/

define("XOSMARTY_FILENOTFOUND", "XoSmartyPlugin : %s does not exist");
define("XOSMARTY_SECTIONNOTFOUND", "XoSmartyPlugin : section [%s] does not exist in %s");
define("XOSMARTY_GDNOTINSTALLED", "XoSmartyPlugin : GD Librairy is not installed");
define("XOSMARTY_DEFAULTVALUE", "XoSmartyPlugin : %s use the default values");

/**
 * -------------------------------------------------------------------------------------
 * get the contents of the section of ini file :
 *
 * @param		String		$section			name of the section of ini file
 *
 * @return		Array
 * -------------------------------------------------------------------------------------
**/
function XoSmartyPluginGetSection( $section = '' ) {
	$config_file = 'xoSmartyPlugin';
	if ( file_exists( XOOPS_ROOT_PATH . "/configs/$section.ini.php" ) ) {
		$config_file = $section;
	}
	if ( file_exists( XOOPS_ROOT_PATH . "/configs/$config_file.ini.php" ) ) {
		$IniContent = parse_ini_file( XOOPS_ROOT_PATH . "/configs/$config_file.ini.php", true);
		if ( !empty($section) ) {
			if ( array_key_exists( $section, $IniContent) ) {
				if ( count($IniContent[$section]) == 0 ) {
			 		XoopsErrorHandler_HandleError( E_USER_WARNING, sprintf(XOSMARTY_SECTIONNOTFOUND, $section, "/configs/$config_file.ini.php"), __FILE__, __LINE__ );
					return array();
				}
				return $IniContent[$section];
			}
		}
 		XoopsErrorHandler_HandleError( E_USER_WARNING, sprintf(XOSMARTY_SECTIONNOTFOUND, $section, "/configs/$config_file.ini.php"), __FILE__, __LINE__ );
		return $IniContent;
	}
	XoopsErrorHandler_HandleError( E_USER_WARNING, sprintf(XOSMARTY_FILENOTFOUND, "/configs/$section.ini.php") , __FILE__, __LINE__ );
	return array();
}

/**
 * -------------------------------------------------------------------------------------
 * Check if the GD Librairy is installed
 *
 * @return		Boolean		TRUE if GD Librairy is installed, FALSE otherwise
 * -------------------------------------------------------------------------------------
**/
function XoSmartyPluginLoadGD() {
	if (extension_loaded('gd')) {
		$required_functions = array("imagecreate", "imagecreatetruecolor", "imagecolorallocate", "imagefilledrectangle", "ImagePNG", "imagedestroy", "imageftbbox", "ImageColorTransparent");
		foreach($required_functions as $func) {
			if( !function_exists($func) ) {
				XoopsErrorHandler_HandleError( E_USER_WARNING, XOSMARTY_GDNOTINSTALLED , __FILE__, __LINE__ );
				return false;
			}
		}
	}
	return true;
}

/**
 * -------------------------------------------------------------------------------------
 * Convert Hexacimal color in RGB color
 *
 * @param		String		$color		hexacimal color
 *
 * @return		Array			{0 => red color value, 1 => green color value, 2 => blue color value)
 * -------------------------------------------------------------------------------------
**/

function XoSmartyPluginHTML2RGB( $color = '#000000' ) {
	if (substr($color,0,1)=="#") $color=substr($color,1,6);

	$ret[0] = hexdec(substr($color, 0, 2));
	$ret[1] = hexdec(substr($color, 2, 2));
	$ret[2] = hexdec(substr($color, 4, 2));
	return $ret;
}

/**
 * -------------------------------------------------------------------------------------
 * Truncate string
 *
 * @param		string		$string			string to truncate
 * @param		int			$length			determines how many characters to truncate to.
 * @param		string		$etc				replace the truncated text
 * @param		boolean		$break_words	determines whether or not to truncate at a word boundary
 * @param		boolean		$middle			determines whether the truncation happens at the end of the string
 *
 * @return		String
 * -------------------------------------------------------------------------------------
**/
function XoSmartyPlugin_truncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false) {
	if ( $length == 0 ) {
		return '';
	}

	if ( strlen($string) > $length ) {
		$length -= min($length, strlen($etc));
		if ( !$break_words && !$middle ) {
			$string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
		}
		if ( !$middle ) {
			return substr($string, 0, $length) . $etc;
		} else {
			return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
		}
	} else {
		return $string;
	}
}

function XoSmartyPlugin_write_index_file( $path = '') {
	if ( empty($path) ) { return false; }

	$path = substr($path, -1) == "/" ? substr($path, 0, -1) : $path;
	$filename = $path . '/index.html';

	if ( file_exists($filename) ) { return true; }

	if ( !$file = fopen($filename, "w") ) {
		echo 'failed open file';
		return false;
	}
	if ( fwrite($file, "<script>history.go(-1);</script>") == -1 ) {
		echo 'failed write file';
		return false;
	}
	fclose($file);
	return true;
}
?>