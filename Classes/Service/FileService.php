<?php

declare(strict_types=1);

namespace HDNET\Importr\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * FileService
 */
class FileService
{
    /**
     * @param string $filename
     * @return string
     */
    public function getFileAbsFileName($filename)
    {
        return GeneralUtility::getFileAbsFileName($filename);
    }
}
