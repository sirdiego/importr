<?php

namespace HDNET\Importr\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * FileService
 */
class FileService
{
    /**
     * @param string $filename
     * @param bool $onlyRelative
     * @param bool $relToTYPO3_mainDir
     * @return string
     */
    public function getFileAbsFileName($filename, $onlyRelative = true, $relToTYPO3_mainDir = false)
    {
        return GeneralUtility::getFileAbsFileName($filename, $onlyRelative = true, $relToTYPO3_mainDir = false);
    }
}
