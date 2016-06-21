<?php
/**
 * DatabaseService.php
 */

namespace HDNET\Importr\Service;

/**
 * DatabaseService
 */
class DatabaseService
{
    /**
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    public function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
