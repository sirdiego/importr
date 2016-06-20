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
        \HDNET\Importr\Feature\TruncateTable::enable('preParseConfiguration');
    }

    /**
     * @deprecated
     */
    public function renameFile()
    {
        \HDNET\Importr\Feature\RenameFile::enable('afterImport');
    }
}
