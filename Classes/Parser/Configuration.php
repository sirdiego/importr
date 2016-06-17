<?php

namespace HDNET\Importr\Parser;

use HDNET\Importr\Domain\Model\Strategy;
use HDNET\Importr\Exception\ReinitializeException;
use HDNET\Importr\Service\ManagerInterface;
use TYPO3\CMS\Extbase\Persistence\RepositoryInterface;
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
     * @var RepositoryInterface
     */
    protected $strategyRepository;

    /**
     * Configuration constructor.
     * @param Dispatcher $signalSlotDispatcher
     * @param RepositoryInterface $strategyRepository
     */
    public function __construct(Dispatcher $signalSlotDispatcher, RepositoryInterface $strategyRepository)
    {
        $this->signalSlotDispatcher = $signalSlotDispatcher;
        $this->strategyRepository = $strategyRepository;
    }

    /**
     * @param array $configuration
     * @param ManagerInterface $manager
     *
     * @throws ReinitializeException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function parse(array $configuration, ManagerInterface $manager)
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
                    $manager->addToQueue($filepath, $strategy, $create);
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
