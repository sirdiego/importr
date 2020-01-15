<?php
/**
 * DatabaseService.php
 */

namespace HDNET\Importr\Service;

use HDNET\Importr\Migration\DatabaseConnection;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

/**
 * DatabaseService
 */
class DatabaseService
{
    /**
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection|\HDNET\Importr\Migration\DatabaseConnectionMigrationInterface
     */
    public function getDatabaseConnection()
    {
        if (VersionNumberUtility::convertVersionNumberToInteger(VersionNumberUtility::getNumericTypo3Version()) > 9005000) {
            return GeneralUtility::makeInstance(DatabaseConnection::class);
        }

        return $GLOBALS['TYPO3_DB'];
    }
}
