<?php

declare(strict_types=1);

namespace HDNET\Importr\Migration;

interface DatabaseConnectionMigrationInterface
{
    // @codingStandardsIgnoreStart
    public function exec_TRUNCATEquery($table);
    // @codingStandardsIgnoreEnd
}
