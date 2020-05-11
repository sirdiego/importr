<?php

declare(strict_types=1);

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
     *
     * @param Dispatcher $signalSlotDispatcher
     * @param StrategyRepository $strategyRepository
     * @param ImportServiceInterface $importService
     */
    public function __construct(Dispatcher $signalSlotDispatcher, StrategyRepository $strategyRepository, ImportServiceInterface $importService)
    {
        $this->signalSlotDispatcher = $signalSlotDispatcher;
        $this->strategyRepository = $strategyRepository;
        $this->importService = $importService;
    }

    /**
     * @param array            $configuration
     * @param ManagerInterface $manager
     * @param mixed            $filter
     *
     * @throws ReinitializeException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function process(array $configuration, ManagerInterface $manager, $filter = null)
    {
        if ($filter === null) {
            $this->processInner($configuration, $manager);
        } elseif ($this->canProcess($configuration, $filter)) {
            $this->processInner($configuration[$filter], $manager);
        }
    }

    /**
     * @param array  $configuration
     * @param string $name
     *
     * @return bool
     */
    public function canProcess(array $configuration, $name)
    {
        return isset($configuration[$name]) && \is_array($configuration[$name]);
    }

    /**
     * @param array            $configuration
     * @param ManagerInterface $manager
     *
     * @throws ReinitializeException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    protected function processInner(array $configuration, ManagerInterface $manager)
    {
        $this->emitSignal('preParseConfiguration', $configuration);
        try {
            $this->updateInterval($configuration, $manager)
                ->createImport($configuration)
                ->reinitializeScheduler($configuration);

            $this->emitSignal('postParseConfiguration', $configuration);
        } catch (ReinitializeException $exception) {
            $this->emitSignal('postParseConfiguration', $configuration);
            throw  $exception;
        }
    }

    /**
     * @param string $name
     * @param array  $configuration
     *
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    protected function emitSignal($name, array &$configuration)
    {
        $this->signalSlotDispatcher->dispatch(
            __CLASS__,
            $name,
            [$this, $configuration]
        );
    }

    /**
     * @param array            $configuration
     * @param ManagerInterface $manager
     * @return $this
     */
    protected function updateInterval(array $configuration, ManagerInterface $manager)
    {
        if (isset($configuration['updateInterval'])) {
            $manager->setUpdateInterval((int)$configuration['updateInterval']);
        }

        return $this;
    }

    /**
     * @param array $configuration
     * @return $this
     * @throws ReinitializeException
     */
    protected function reinitializeScheduler(array $configuration)
    {
        if (isset($configuration['reinitializeScheduler'])) {
            throw new ReinitializeException();
        }

        return $this;
    }

    /**
     * @param array $configuration
     * @return $this
     */
    protected function createImport(array $configuration)
    {
        if (!isset($configuration['createImport']) && !\is_array($configuration['createImport'])) {
            return $this;
        }

        foreach ($configuration['createImport'] as $create) {
            $strategy = $this->strategyRepository->findByUid((int)$create['importId']);

            if ($strategy instanceof Strategy) {
                $filepath = isset($create['filepath']) ? $create['filepath'] : '';
                $this->importService->addToQueue($filepath, $strategy, $create);
            }
        }

        return $this;
    }
}
