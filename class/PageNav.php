<?php

namespace XoopsModules\Xnews;

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
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

/**
 * Class to facilitate navigation in a multi page document/list
 *
 * @package          kernel
 * @subpackage       util
 *
 * @author           Kazumi Ono     <onokazu@xoops.org>
 * @copyright    (c) 2000-2003 The Xoops Project - www.xoops.org
 */

/**
 * Class XoopsPageNav
 */
class PageNav extends \XoopsPageNav
{
    /**
     * Constructor
     *
     * @param int    $total_items   Total number of items
     * @param int    $items_perpage Number of items per page
     * @param int    $current_start First item on the current page
     * @param string $start_name    Name for "start" or "offset"
     * @param string $extra_arg     Additional arguments to pass in the URL
     **/
    public function __construct($total_items, $items_perpage, $current_start, $start_name = 'start', $extra_arg = '')
    {
        parent::__construct($total_items, $items_perpage, $current_start, $start_name, $extra_arg);
    }

    /**
     * Create an enhanced navigational dropdown list
     *
     * @param bool $showbutton Show the "Go" button?
     * @param null $titles
     * @return string
     */
    public function renderEnhancedSelect($showbutton = false, $titles = null)
    {
        if ($this->total < $this->perpage) {
            return;
        }
        $total_pages = \ceil($this->total / $this->perpage);
        $ret         = '';
        if ($total_pages > 1) {
            $ret          = '<form name="pagenavform">';
            $ret          .= '<select name="pagenavselect" onchange="location=this.options[this.options.selectedIndex].value;">';
            $counter      = 1;
            $current_page = (int)\floor(($this->current + $this->perpage) / $this->perpage);
            while ($counter <= $total_pages) {
                if (isset($titles[$counter - 1])) {
                    $title = $titles[$counter - 1];
                } else {
                    $title = $counter;
                }
                if ($counter == $current_page) {
                    $ret .= '<option value="' . $this->url . (($counter - 1) * $this->perpage) . '" selected="selected">' . $title . '</option>';
                } else {
                    $ret .= '<option value="' . $this->url . (($counter - 1) * $this->perpage) . '">' . $title . '</option>';
                }
                $counter++;
            }
            $ret .= '</select>';
            if ($showbutton) {
                $ret .= '&nbsp;<input type="submit" value="' . _GO . '" >';
            }
            $ret .= '</form>';
        }

        return $ret;
    }
}
