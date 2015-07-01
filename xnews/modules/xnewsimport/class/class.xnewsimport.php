<?php

// better place this on a separate file so you can reuse it
class xni_TableObject extends XoopsObject
{
    /**
     * constructor
     */
    function xni_TableObject($row, $id_name = 'cid', $pid_name = 'pid', $title_name = 'title')
    {
        $this->XoopsObject();
        $this->initVar($id_name, XOBJ_DTYPE_INT, $row[$id_name]);
        $this->initVar($pid_name, XOBJ_DTYPE_INT, $row[$pid_name]);
        $this->initVar($title_name, XOBJ_DTYPE_TXTBOX, $row[$title_name]);
    }
}

?>
