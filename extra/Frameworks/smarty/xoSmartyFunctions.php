<?php
/**
 * -------------------------------------------------------------------------------------
 * Common functions for Xoops's smarty plugins : xoSmartyFunctions.php
 *
 * Type            : common functions
 * Author:        : DuGris <http://www.dugris.info>
 * Purpose        : Common functions for Xoops's smarty plugins
 * -------------------------------------------------------------------------------------
 **/
define('XOSMARTY_FILENOTFOUND', 'XoSmartyPlugin : %s does not exist');
define('XOSMARTY_SECTIONNOTFOUND', 'XoSmartyPlugin : section [%s] does not exist in %s');
define('XOSMARTY_GDNOTINSTALLED', 'XoSmartyPlugin : GD Librairy is not installed');
define('XOSMARTY_DEFAULTVALUE', 'XoSmartyPlugin : %s use the default values');

/**
 * -------------------------------------------------------------------------------------
 * get the contents of the section of ini file :
 *
 * @param        string $section name of the section of ini file
 *
 * @return        array
 * -------------------------------------------------------------------------------------
 **/
function XoSmartyPluginGetSection($section = '')
{
    $config_file = 'xoSmartyPlugin';
    /** @var \XoopsLogger $logger */
    $logger = \XoopsLogger::getInstance();
    if (file_exists(XOOPS_ROOT_PATH . "/configs/$section.ini.php")) {
        $config_file = $section;
    }
    if (file_exists(XOOPS_ROOT_PATH . "/configs/$config_file.ini.php")) {
        $IniContent = parse_ini_file(XOOPS_ROOT_PATH . "/configs/$config_file.ini.php", true);
        if (!empty($section)) {
            if (array_key_exists($section, $IniContent)) {
                if (0 == count($IniContent[$section])) {
                    $logger->handleError(E_USER_WARNING, sprintf(XOSMARTY_SECTIONNOTFOUND, $section, "/configs/$config_file.ini.php"), __FILE__, __LINE__);

                    return [];
                }

                return $IniContent[$section];
            }
        }
        $logger->handleError(E_USER_WARNING, sprintf(XOSMARTY_SECTIONNOTFOUND, $section, "/configs/$config_file.ini.php"), __FILE__, __LINE__);

        return $IniContent;
    }
    $logger->handleError(E_USER_WARNING, sprintf(XOSMARTY_FILENOTFOUND, "/configs/$section.ini.php"), __FILE__, __LINE__);

    return [];
}

/**
 * -------------------------------------------------------------------------------------
 * Check if the GD Librairy is installed
 *
 * @return        bool        TRUE if GD Librairy is installed, FALSE otherwise
 * -------------------------------------------------------------------------------------
 **/
function XoSmartyPluginLoadGD()
{
    if (extension_loaded('gd')) {
        $required_functions = ['imagecreate', 'imagecreatetruecolor', 'imagecolorallocate', 'imagefilledrectangle', 'ImagePNG', 'imagedestroy', 'imageftbbox', 'ImageColorTransparent'];
        foreach ($required_functions as $func) {
            if (!function_exists($func)) {
                /** @var \XoopsLogger $logger */
                $logger = \XoopsLogger::getInstance();
                $logger->handleError(E_USER_WARNING, XOSMARTY_GDNOTINSTALLED, __FILE__, __LINE__);

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
 * @param        string $color hexacimal color
 *
 * @return        array            {0 => red color value, 1 => green color value, 2 => blue color value)
 * -------------------------------------------------------------------------------------
 **/
function XoSmartyPluginHTML2RGB($color = '#000000')
{
    if (0 === mb_strpos($color, '#')) {
        $color = mb_substr($color, 1, 6);
    }

    $ret[0] = hexdec(mb_substr($color, 0, 2));
    $ret[1] = hexdec(mb_substr($color, 2, 2));
    $ret[2] = hexdec(mb_substr($color, 4, 2));

    return $ret;
}

/**
 * -------------------------------------------------------------------------------------
 * Truncate string
 *
 * @param        string $string      string to truncate
 * @param        int    $length      determines how many characters to truncate to.
 * @param        string $etc         replace the truncated text
 * @param        bool   $break_words determines whether or not to truncate at a word boundary
 * @param        bool   $middle      determines whether the truncation happens at the end of the string
 *
 * @return        string
 * -------------------------------------------------------------------------------------
 **/
function XoSmartyPlugin_truncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false)
{
    if (0 == $length) {
        return '';
    }

    if (mb_strlen($string) > $length) {
        $length -= min($length, mb_strlen($etc));
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', mb_substr($string, 0, $length + 1));
        }
        if (!$middle) {
            return mb_substr($string, 0, $length) . $etc;
        }

        return mb_substr($string, 0, $length / 2) . $etc . mb_substr($string, -$length / 2);
    }

    return $string;
}

/**
 * @param string $path
 * @return bool
 */
function XoSmartyPlugin_write_index_file($path = '')
{
    if (empty($path)) {
        return false;
    }

    $path     = '/' === mb_substr($path, -1) ? mb_substr($path, 0, -1) : $path;
    $filename = $path . '/index.html';

    if (file_exists($filename)) {
        return true;
    }

    if (!$file = fopen($filename, 'w')) {
        echo 'failed open file';

        return false;
    }
    if (-1 == fwrite($file, '<script>history.go(-1);</script>')) {
        echo 'failed write file';

        return false;
    }
    fclose($file);

    return true;
}
