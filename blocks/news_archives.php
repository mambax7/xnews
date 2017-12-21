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

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

/**
 * Solves issue when upgrading xoops version
 * Paths not set and block would not work
 */
if (!defined('XNEWS_MODULE_PATH')) {
    define('XNEWS_SUBPREFIX', 'nw');
    define('XNEWS_MODULE_DIRNAME', 'xnews');
    define('XNEWS_MODULE_PATH', XOOPS_ROOT_PATH . '/modules/' . XNEWS_MODULE_DIRNAME);
    define('XNEWS_MODULE_URL', XOOPS_URL . '/modules/' . XNEWS_MODULE_DIRNAME);
    define('XNEWS_UPLOADS_NEWS_PATH', XOOPS_ROOT_PATH . '/uploads/' . XNEWS_MODULE_DIRNAME);
    define('XNEWS_TOPICS_FILES_PATH', XOOPS_ROOT_PATH . '/uploads/' . XNEWS_MODULE_DIRNAME . '/topics');
    define('XNEWS_ATTACHED_FILES_PATH', XOOPS_ROOT_PATH . '/uploads/' . XNEWS_MODULE_DIRNAME . '/attached');
    define('XNEWS_TOPICS_FILES_URL', XOOPS_URL . '/uploads/' . XNEWS_MODULE_DIRNAME . '/topics');
    define('XNEWS_ATTACHED_FILES_URL', XOOPS_URL . '/uploads/' . XNEWS_MODULE_DIRNAME . '/attached');
}

require_once XNEWS_MODULE_PATH . '/class/class.newsstory.php';

/**
 * Display archives
 * @param array $options :
 *                       0 = sort order (0=older first, 1=newer first)
 *                       1 = Starting date, year
 *                       2 = Starting date, month
 *                       3 = Ending date, year
 *                       4 = Ending date, month
 *                       5 = until today ?
 * @return array|string
 */
function nw_b_news_archives_show($options)
{
    global $xoopsDB, $xoopsConfig;
    require_once XNEWS_MODULE_PATH . '/class/class.newsstory.php';
    require_once XNEWS_MODULE_PATH . '/include/functions.php';
    require_once XOOPS_ROOT_PATH . '/language/' . $xoopsConfig['language'] . '/calendar.php';

    if (file_exists(XNEWS_MODULE_PATH . '/language/' . $xoopsConfig['language'] . '/main.php')) {
        require_once XNEWS_MODULE_PATH . '/language/' . $xoopsConfig['language'] . '/main.php';
    } else {
        require_once XNEWS_MODULE_PATH . '/language/english/main.php';
    }

    $months_arr    = [
        1  => _CAL_JANUARY,
        2  => _CAL_FEBRUARY,
        3  => _CAL_MARCH,
        4  => _CAL_APRIL,
        5  => _CAL_MAY,
        6  => _CAL_JUNE,
        7  => _CAL_JULY,
        8  => _CAL_AUGUST,
        9  => _CAL_SEPTEMBER,
        10 => _CAL_OCTOBER,
        11 => _CAL_NOVEMBER,
        12 => _CAL_DECEMBER
    ];
    $block         = [];
    $sort_order    = 0 == $options[0] ? 'ASC' : 'DESC';
    $starting_date = mktime(0, 0, 0, (int)$options[2], 1, (int)$options[1]);
    if (1 != (int)$options[5]) {
        $ending_date = mktime(23, 59, 59, (int)$options[4], 28, (int)$options[3]);
    } else {
        $ending_date = time();
    }
    $sql    = "SELECT distinct(FROM_UNIXTIME(published,'%Y-%m')) as published";
    $sql    .= " FROM {$xoopsDB->prefix('nw_stories')}";
    $sql    .= " WHERE published >= {$starting_date} AND published <= {$ending_date} ORDER BY published {$sort_order}";
    $result = $xoopsDB->query($sql);
    if (!$result) {
        return '';
    }
    while ($myrow = $xoopsDB->fetchArray($result)) {
        $year                = (int)substr($myrow['published'], 0, 4);
        $month               = (int)substr($myrow['published'], 5, 2);
        $formated_month      = $months_arr[$month];
        $block['archives'][] = ['month' => $month, 'year' => $year, 'formated_month' => $formated_month];
    }
    //DNPROSSI ADDED
    $block['newsmodule_url'] = XNEWS_MODULE_URL;

    return $block;
}

/**
 * @param $options
 * @return string
 */
function nw_b_news_archives_edit($options)
{
    global $xoopsDB;
    $syear    = $smonth = $eyear = $emonth = $older = $recent = 0;
    $selsyear = $selsmonth = $seleyear = $selemonth = 0;
    $form     = '';

    $selsyear  = $options[1];
    $selsmonth = $options[2];
    $seleyear  = $options[3];
    $selemonth = $options[4];

    $tmpstory = new XNewsStory;
    $tmpstory->GetOlderRecentnews($older, $recent);    // We are searching for the module's older and more recent article's date

    // Min and max value for the two dates selectors
    // We are going to use the older news for the starting date
    $syear  = date('Y', $older);
    $smonth = date('n', $older);
    $eyear  = date('Y', $recent);
    $emonth = date('n', $recent);
    // Verify parameters
    if (0 == $selsyear && 0 == $selsmonth) {
        $selsyear  = $syear;
        $selsmonth = $smonth;
    }
    if (0 == $seleyear && 0 == $selemonth) {
        $seleyear  = $eyear;
        $selemonth = $emonth;
    }

    // Sort order *************************************************************
    // (0=older first, 1=newer first)
    $form .= '<b>' . _MB_XNEWS_ORDER . "</b>&nbsp;<select name='options[]'>";
    $form .= "<option value='0'";
    if (0 == $options[0]) {
        $form .= " selected='selected'";
    }
    $form .= '>' . _MB_XNEWS_OLDER_FIRST . "</option>\n";
    $form .= "<option value='1'";
    if (1 == $options[0]) {
        $form .= " selected='selected'";
    }
    $form .= '>' . _MB_XNEWS_RECENT_FIRST . '</option>';
    $form .= "</select>\n";

    // Starting and ending dates **********************************************
    $form .= '<br><br><b>' . _MB_XNEWS_STARTING_DATE . '</b><br>';
    $form .= _MB_XNEWS_CAL_YEAR . "&nbsp;<select name='options[]'>";
    for ($i = $syear; $i <= $eyear; $i++) {
        $selected = ($i == $selsyear) ? "selected='selected'" : '';
        $form     .= "<option value='" . $i . "'" . $selected . '>' . $i . '</option>';
    }
    $form .= '</select>&nbsp;' . _MB_XNEWS_CAL_MONTH . "&nbsp;<select name='options[]'>";
    for ($i = 1; $i <= 12; $i++) {
        $selected = ($i == $selsmonth) ? "selected='selected'" : '';
        $form     .= "<option value='" . $i . "'" . $selected . '>' . $i . '</option>';
    }
    $form .= '</select>';

    $form .= '<br><br><b>' . _MB_XNEWS_ENDING_DATE . '</b><br>';
    $form .= _MB_XNEWS_CAL_YEAR . "&nbsp;<select name='options[]'>";
    for ($i = $syear; $i <= $eyear; $i++) {
        $selected = ($i == $seleyear) ? "selected='selected'" : '';
        $form     .= "<option value='" . $i . "'" . $selected . '>' . $i . '</option>';
    }
    $form .= '</select>&nbsp;' . _MB_XNEWS_CAL_MONTH . "&nbsp;<select name='options[]'>";
    for ($i = 1; $i <= 12; $i++) {
        $selected = ($i == $selemonth) ? "selected='selected'" : '';
        $form     .= "<option value='" . $i . "'" . $selected . '>' . $i . '</option>';
    }
    $form .= '</select>';

    // Or until today *********************************************************
    $form    .= '<br>';
    $checked = 1 == $options[5] ? ' checked' : '';
    $form    .= "<input type='checkbox' value='1' name='options[]'" . $checked . '>';
    $form    .= ' <b>' . _MB_XNEWS_UNTIL_TODAY . '</b>';

    return $form;
}

/**
 * @param $options
 */
function nw_b_news_archives_onthefly($options)
{
    $options = explode('|', $options);
    $block   = &nw_b_news_archives_show($options);

    $tpl = new XoopsTpl();
    $tpl->assign('block', $block);
    $tpl->display('db:nw_news_block_archives.tpl');
}
