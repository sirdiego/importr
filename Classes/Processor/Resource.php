<?php

namespace HDNET\Importr\Processor;

use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Service\ImportServiceInterface;
use HDNET\Importr\Service\ManagerInterface;
use HDNET\Importr\Service\Resources\ResourceInterface;

/**
 * Resource
 */
class Resource
{
    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var Target
     */
    protected $target;

    /**
     * @var ImportServiceInterface
     */
    protected $importService;

    /**
     * Resource constructor.
     *
     * @param Configuration          $configuration
     * @param Target                 $target
     * @param ImportServiceInterface $importService
     */
    public function __construct(Configuration $configuration, Target $target, ImportServiceInterface $importService)
    {
        $this->configuration = $configuration;
        $this->target = $target;
        $this->importService = $importService;
    }

    /**
     * @param Import            $import
     * @param array             $targets
     * @param array             $configuration
     * @param ResourceInterface $resource
     * @param ManagerInterface  $manager
     * @return bool
     * @throws \HDNET\Importr\Exception\ReinitializeException
     */
    public function process(Import $import, array $targets, array $configuration, ResourceInterface $resource, ManagerInterface $manager)
    {
        // Resourcen Object anhand der Datei auswählen
        if (!preg_match($resource->getFilepathExpression(), $import->getFilepath())) {
            return false;
        }

        $this->configuration->process($configuration, $manager, 'before');
        // Resource "benutzen"
        $resource->parseResource();
        // Basis Import Aktualsieren (DB)
        $import->setAmount($resource->getAmount());
        $import->setStarttime(new \DateTime('now'));
        $this->importService->updateImport($import);
        // Durchlauf starten
        for ($pointer = $import->getPointer(); $pointer < $import->getAmount(); $pointer++) {
            $this->configuration->process($configuration, $manager, 'each');
            $this->processOneLine($resource, $pointer, $targets, $import);
            if (($pointer + 1) % $manager->getUpdateInterval() == 0) {
                $this->importService->updateImport($import, $pointer + 1);
            }
        }
        $import->setEndtime(new \DateTime('now'));
        $this->importService->updateImport($import, $pointer);
        $this->configuration->process($configuration, $manager, 'after');

        return true;
    }

    /**
     * @param ResourceInterface $resource
     * @param int               $pointer
     * @param array             $targets
     * @param Import            $import
     */
    protected function processOneLine(ResourceInterface $resource, $pointer, array $targets, Import $import)
    {
        $entry = $resource->getEntry($pointer);
        foreach ($targets as $target) {
            $this->target->process($target, $entry, $import, $pointer);
        }
    }
}
