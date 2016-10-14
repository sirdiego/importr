<?php

namespace HDNET\Importr\Processor;

use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Service\ImportServiceInterface;
use HDNET\Importr\Service\Targets\TargetInterface;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

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
     * @var Dispatcher
     */
    protected $signalSlotDispatcher;

    /**
     * Target constructor.
     *
     * @param ImportServiceInterface $importService
     * @param Dispatcher $signalSlotDispatcher
     */
    public function __construct(ImportServiceInterface $importService, Dispatcher $signalSlotDispatcher)
    {
        $this->importService = $importService;
        $this->signalSlotDispatcher = $signalSlotDispatcher;
    }

    /**
     * @param TargetInterface $target
     * @param mixed $entry
     * @param Import $import
     * @param int $pointer
     *
     * @throws \Exception
     */
    public function process(TargetInterface $target, $entry, Import $import, $pointer)
    {
        try {
            $entry = $this->emitEntrySignal('preProcess', $target->getConfiguration(), $entry);
            $result = $target->processEntry($entry);
            $import->increaseCount($result);
        } catch (\Exception $e) {
            $import->increaseCount(TargetInterface::RESULT_ERROR);
            $this->importService->updateImport($import, $pointer + 1);
            throw $e;
        }
    }

    /**
     * @param string $name
     * @param array $configuration
     * @param mixed $entry
     *
     * @return mixed
     */
    protected function emitEntrySignal($name, array $configuration, $entry)
    {
        $result = $this->signalSlotDispatcher->dispatch(
            __CLASS__,
            $name,
            [$configuration, $entry]
        );

        return $result[1];
    }
}
