<?php
namespace HDNET\Importr\Feature;

use HDNET\Importr\Service\ManagerInterface;
use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Utility;

class TruncateTable {
    /**
     * To truncate a table from the importr you
     * have to use the "truncate: " configuration.
     * If you pass a string, then the string is
     * interpreted as a table name. If you pass
     * an array, every element is used as a table
     * name.
     *
     * @param ManagerInterface $manager
     * @param Import $import
     */
    public function execute(ManagerInterface $manager, Import $configuration)
    {
        $configuration = $import->getStrategy()
            ->getConfiguration();
        if (isset($configuration['truncate'])) {
            if (is_array($configuration['truncate'])) {
                foreach ($configuration['truncate'] as $table) {
                    Utility::getDatabaseConnection()
                        ->exec_TRUNCATEquery($table);
                }
            } else {
                Utility::getDatabaseConnection()
                    ->exec_TRUNCATEquery($configuration['truncate']);
            }
        }
    }
}
