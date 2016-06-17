<?php

namespace HDNET\Importr\Service;

use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Domain\Model\Strategy;
use HDNET\Importr\Domain\Repository\ImportRepository;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;

/**
 * ImportService
 */
class ImportService implements ImportServiceInterface
{
    /**
     * @var PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @var ImportRepository
     */
    protected $importRepository;

    /**
     * ImportService constructor.
     * @param PersistenceManagerInterface $persistenceManager
     * @param ObjectManagerInterface $objectManager
     * @param ImportRepository $importRepository
     */
    public function __construct(PersistenceManagerInterface $persistenceManager, ObjectManagerInterface $objectManager, ImportRepository $importRepository)
    {
        $this->persistenceManager = $persistenceManager;
        $this->objectManager = $objectManager;
        $this->importRepository = $importRepository;
    }

    /**
     * @param \HDNET\Importr\Domain\Model\Import $import
     * @param bool|int $pointer
     */
    public function updateImport(Import $import, $pointer = false)
    {
        if (is_int($pointer)) {
            $import->setPointer($pointer);
        }
        $this->importRepository->update($import);
        $this->persistenceManager->persistAll();
    }

    /**
     * @param string $filepath
     * @param \HDNET\Importr\Domain\Model\Strategy $strategy
     * @param array $configuration
     */
    public function addToQueue($filepath, Strategy $strategy, array $configuration = [])
    {
        $import = $this->objectManager->get(Import::class);
        $start = 'now';
        if (isset($configuration['start'])) {
            $start = $configuration['start'];
        }
        try {
            $startTime = new \DateTime($start);
        } catch (\Exception $e) {
            $startTime = new \DateTime();
        }
        $import->setStarttime($startTime);
        $import->setFilepath($filepath);
        $import->setStrategy($strategy);
        $this->importRepository->add($import);
        $this->persistenceManager->persistAll();
    }
}
