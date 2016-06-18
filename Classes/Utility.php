<?php
namespace HDNET\Importr;

use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Description of Utility
 *
 * @author timlochmueller
 */
class Utility
{
    /**
     * Get TYPO3 Version
     *
     * @param null $version
     * @return string
     */
    public static function getVersion($version = null)
    {
        if ($version === null) {
            $version = TYPO3_version;
        }
        return VersionNumberUtility::convertIntegerToVersionNumber($version);
    }

    /**
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    public static function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
