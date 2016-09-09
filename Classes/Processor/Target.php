<?php

namespace HDNET\Importr\Processor;

use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Service\ImportServiceInterface;
use HDNET\Importr\Service\Targets\TargetInterface;

/**
 * Target
 */
class Target
{
    /**
     * @var ImportServiceInterface
     */
    protected $importService;

    /**
     * Target constructor.
     *
     * @param ImportServiceInterface $importService
     */
    public function __construct(ImportServiceInterface $importService)
    {
        $this->importService = $importService;
    }

    /**
     * @param TargetInterface $target
     * @param mixed           $entry
     * @param Import          $import
     * @param int             $pointer
     *
     * @throws \Exception
     */
    public function process(TargetInterface $target, $entry, Import $import, $pointer)
    {
        try {
            $result = $target->processEntry($entry);
            $import->increaseCount($result);
        } catch (\Exception $e) {
            $import->increaseCount(TargetInterface::RESULT_ERROR);
            $this->importService->updateImport($import, $pointer + 1);
            throw $e;
        }
    }
}
