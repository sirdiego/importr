<?php
namespace HDNET\Importr\Feature;

use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Service\DatabaseService;
use HDNET\Importr\Service\ManagerInterface;

class TruncateTable extends AbstractFeature
{
    /**
     * @var DatabaseService
     */
    protected $databaseService;

    /**
     * TruncateTable constructor.
     * @param DatabaseService $databaseService
     */
    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

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
    public function execute(ManagerInterface $manager, Import $import)
    {
        $configuration = $import->getStrategy()
            ->getConfiguration();
        if (isset($configuration['truncate'])) {
            if (is_array($configuration['truncate'])) {
                foreach ($configuration['truncate'] as $table) {
                    $this->databaseService->getDatabaseConnection()
                        ->exec_TRUNCATEquery($table);
                }
            } else {
                $this->databaseService->getDatabaseConnection()
                    ->exec_TRUNCATEquery($configuration['truncate']);
            }
        }
    }
}
