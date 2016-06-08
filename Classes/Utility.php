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
     * @param string $className
     *
     * @internal param mixed $params
     *
     * @return \StdClass
     */
    static public function createObject($className)
    {
        $arguments = func_get_args();
        $objectManager = new ObjectManager();
        $object = call_user_func_array([
            $objectManager,
            'get'
        ], $arguments);

        return $object;
    }

    /**
     * Get TYPO3 Version
     *
     * @param null $version
     * @return string
     */
    static public function getVersion($version = null)
    {
        if ($version === null) {
            $version = TYPO3_version;
        }
        return VersionNumberUtility::convertIntegerToVersionNumber($version);
    }

    /**
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    static public function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
