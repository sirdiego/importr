<?php

namespace HDNET\Importr\Processor;

use HDNET\Importr\Domain\Model\Strategy;
use HDNET\Importr\Domain\Repository\StrategyRepository;
use HDNET\Importr\Exception\ReinitializeException;
use HDNET\Importr\Service\ImportServiceInterface;
use HDNET\Importr\Service\ManagerInterface;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

/**
 * Configuration
 */
class Configuration
{
    /**
     * @var Dispatcher
     */
    protected $signalSlotDispatcher;

    /**
     * @var StrategyRepository
     */
    protected $strategyRepository;

    /**
     * @var ImportServiceInterface
     */
    protected $importService;

    /**
     * Configuration constructor.
     * @param Dispatcher $signalSlotDispatcher
     * @param StrategyRepository $strategyRepository
     */
    public function __construct(Dispatcher $signalSlotDispatcher, StrategyRepository $strategyRepository, ImportServiceInterface $importService)
    {
        $this->signalSlotDispatcher = $signalSlotDispatcher;
        $this->strategyRepository = $strategyRepository;
        $this->importService = $importService;
    }

    /**
     * @param array $configuration
     * @param ManagerInterface $manager
     *
     * @throws ReinitializeException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function process(array $configuration, ManagerInterface $manager)
    {
        $this->signalSlotDispatcher->dispatch(__CLASS__, 'preParseConfiguration', [
            $this,
            $configuration
        ]);
        if (isset($configuration['updateInterval'])) {
            $manager->setUpdateInterval((int)$configuration['updateInterval']);
        }
        if (isset($configuration['createImport']) && is_array($configuration['createImport'])) {
            foreach ($configuration['createImport'] as $create) {
                $strategy = $this->strategyRepository->findByUid((int)$create['importId']);
                if ($strategy instanceof Strategy) {
                    $filepath = isset($create['filepath']) ? $create['filepath'] : '';
                    $this->importService->addToQueue($filepath, $strategy, $create);
                }
            }
        }
        if (isset($configuration['reinitializeScheduler'])) {
            throw new ReinitializeException();
        }
        $this->signalSlotDispatcher->dispatch(__CLASS__, 'postParseConfiguration', [
            $this,
            $configuration
        ]);
    }
}
