<?php

declare(strict_types=1);

namespace HDNET\Importr\Processor;

use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Service\ImportServiceInterface;
use HDNET\Importr\Service\Targets\TargetInterface;
use JMS\Serializer\EventDispatcher\EventDispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;
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
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * Target constructor.
     *
     * @param ImportServiceInterface $importService
     * @param EventDispatcherInterface $signalSlotDispatcher
     */
    public function __construct(ImportServiceInterface $importService, EventDispatcherInterface $eventDispatcher)
    {
        $this->importService = $importService;
        $this->eventDispatcher = $eventDispatcher;
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
        $result = $this->eventDispatcher->dispatch(
            $this,
            $name,
            [$configuration, $entry]
        );

        return $result[1];
    }
}
