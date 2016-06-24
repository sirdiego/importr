<?php
/**
 * SignalService.php
 *
 * General file information
 *
 * @category Extension
 * @package  importr
 * @author   Tim Spiekerkoetter HDNET GmbH & Co. <tim.spiekerkoetter@hdnet.de>
 * @version  CVS: $Id:08.02.13$
 */
namespace HDNET\Importr\Service;

use HDNET\Importr\Feature\RenameFile;
use HDNET\Importr\Feature\TruncateTable;

/**
 * SignalService.php
 *
 * General class information
 *
 * @package    importr
 * @subpackage Service
 * @author     Tim Spiekerkoetter HDNET GmbH & Co. <tim.spiekerkoetter@hdnet.de>
 */
class SignalService
{
    /**
     * @deprecated
     */
    public function truncateTable()
    {
        TruncateTable::enable();
    }

    /**
     * @deprecated
     */
    public function renameFile()
    {
        RenameFile::enable();
    }
}
