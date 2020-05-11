<?php

declare(strict_types=1);
namespace HDNET\Importr;

use TYPO3\CMS\Core\Utility\VersionNumberUtility;

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
     * @param  null $version
     * @return string
     */
    public static function getVersion($version = null)
    {
        if ($version === null) {
            $version = TYPO3_version;
        }
        return VersionNumberUtility::convertIntegerToVersionNumber($version);
    }
}
