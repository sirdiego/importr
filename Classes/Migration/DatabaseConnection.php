<?php

declare(strict_types=1);

namespace HDNET\Importr\Migration;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class DatabaseConnection implements DatabaseConnectionMigrationInterface
{
    // @codingStandardsIgnoreStart
    public function exec_TRUNCATEquery($table)
    {
        // @codingStandardsIgnoreEnd
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $connection = $connectionPool->getConnectionForTable($table);
        $connection->truncate($table);
    }
}
