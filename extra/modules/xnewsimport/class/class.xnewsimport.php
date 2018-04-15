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
 * @copyright    XOOPS Project http://sourceforge.net/projects/xoops/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package      xNews
 * @since        1.6.0
 * @author       XOOPS Development Team,
 * @version      $Id $
 */
// better place this on a separate file so you can reuse it
class xni_TableObject extends \XoopsObject
{
    /**
     * constructor
     * @param        $row
     * @param string $id_name
     * @param string $pid_name
     * @param string $title_name
     */
    public function __construct($row, $id_name = 'cid', $pid_name = 'pid', $title_name = 'title')
    {
        parent::__construct();
        $this->initVar($id_name, XOBJ_DTYPE_INT, $row[$id_name]);
        $this->initVar($pid_name, XOBJ_DTYPE_INT, $row[$pid_name]);
        $this->initVar($title_name, XOBJ_DTYPE_TXTBOX, $row[$title_name]);
    }
}
