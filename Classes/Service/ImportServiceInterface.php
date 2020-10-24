<?php

declare(strict_types=1);
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
     */
    public function updateImport(Import $import, $pointer = null);

    /**
     * @param string   $filepath
     * @param Strategy $strategy
     * @param array    $configuration
     */
    public function addToQueue($filepath, Strategy $strategy, array $configuration = []);
}
