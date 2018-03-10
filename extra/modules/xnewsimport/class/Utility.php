<?php namespace XoopsModules\Xnewsimport;

use Xmf\Request;
use XoopsModules\Xnewsimport;
use XoopsModules\Xnewsimport\Common;

/**
 * Class Utility
 */
class Utility
{
    use Common\VersionChecks; //checkVerXoops, checkVerPhp Traits

    use Common\ServerStats; // getServerStats Trait

    use Common\FilesManagement; // Files Management Trait

    //--------------- Custom module methods -----------------------------
}
