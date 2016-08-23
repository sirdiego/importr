<?php
/**
 * TruncateTable.php
 */
namespace HDNET\Importr\Feature;

use HDNET\Importr\Processor\Configuration;
use HDNET\Importr\Service\DatabaseService;

/**
 * Class TruncateTable
 */
class TruncateTable
{
    /**
     * @var DatabaseService
     */
    protected $databaseService;

    /**
     * TruncateTable constructor.
     *
     * @param DatabaseService $databaseService
     */
    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    /**
     * @return void
     */
    public static function enable()
    {
        FeatureRegistry::enable('preParseConfiguration', Configuration::class);
    }

    /**
     * To truncate a table from the importr you
     * have to use the "truncate: " configuration.
     * If you pass a string, then the string is
     * interpreted as a table name. If you pass
     * an array, every element is used as a table
     * name.
     *
     * @param Configuration $processor
     * @param array         $configuration
     */
    public function execute(Configuration $processor, array $configuration)
    {
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
