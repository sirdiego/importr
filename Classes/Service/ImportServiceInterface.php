<?php
namespace HDNET\Importr\Service;

use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Domain\Model\Strategy;

/**
 * ImportServiceInterface
 */
interface ImportServiceInterface
{
    /**
     * @param Import   $import
     * @param int|null $pointer
     * @return void
     */
    public function updateImport(Import $import, $pointer = null);

    /**
     * @param string   $filepath
     * @param Strategy $strategy
     * @param array    $configuration
     * @return void
     */
    public function addToQueue($filepath, Strategy $strategy, array $configuration = []);
}
