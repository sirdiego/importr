<?php
namespace HDNET\Importr\Service;

use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Domain\Model\Strategy;
use HDNET\Importr\Exception\ReinitializeException;

/**
 * Service Manager
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @author  Tim Lochmüller <tim.lochmueller@hdnet.de>
 */
class Manager implements ManagerInterface
{

    /**
     * @var \HDNET\Importr\Domain\Repository\ImportRepository
     * @inject
     */
    protected $importRepository;

    /**
     * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
     * @inject
     */
    protected $signalSlotDispatcher;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager;

    /**
     * @var \HDNET\Importr\Processor\Configuration
     * @inject
     */
    protected $configuration;

    /**
     * @var \HDNET\Importr\Processor\Resource
     * @inject
     */
    protected $resource;

    /**
     * Update Interval
     *
     * @var integer
     */
    protected $updateInterval = 1;

    /**
     * run the Importr
     */
    public function runImports()
    {
        try {
            $imports = $this->importRepository->findWorkQueue();
            foreach ($imports as $import) {
                $this->runImport($import);
            }
        } catch (ReinitializeException $exc) {
            $this->runImports();
        }
    }

    /**
     * Get the preview
     *
     * @param string                               $filepath
     * @param \HDNET\Importr\Domain\Model\Strategy $strategy
     *
     * @return array
     */
    public function getPreview(Strategy $strategy, $filepath)
    {
        $data = [];
        $resources = $this->initializeResources($strategy, $filepath);
        foreach ($resources as $resource) {
            /**
 * @var \HDNET\Importr\Service\Resources\ResourceInterface $resource
*/
            // Resourcen Object anhand der Datei auswählen
            if (preg_match($resource->getFilepathExpression(), $filepath)) {
                // Resource "benutzen"
                $resource->parseResource();
                // Durchlauf starten
                for ($pointer = 0; $pointer <= 20; $pointer++) {
                    if ($resource->getEntry($pointer)) {
                        $data[] = $resource->getEntry($pointer);
                    }
                }
                break;
            }
        }
        return $data;
    }

    /**
     * Magic Runner
     *
     * @param \HDNET\Importr\Domain\Model\Import $import
     */
    protected function runImport(Import $import)
    {
        $this->signalSlotDispatcher->dispatch(
            __CLASS__,
            'preImport',
            [
            $this,
            $import
            ]
        );
        $resources = $this->initializeResourcesByImport($import);
        $targets = $this->initializeTargets($import);
        $strategyConfiguration = $import->getStrategy()
            ->getConfiguration();

        foreach ($resources as $resource) {
            if ($this->resource->process($import, $targets, $strategyConfiguration, $resource, $this)) {
                break;
            }
        }

        $this->signalSlotDispatcher->dispatch(
            __CLASS__,
            'postImport',
            [
            $this,
            $import
            ]
        );
    }

    /**
     * @param \HDNET\Importr\Domain\Model\Import $import
     *
     * @return array
     */
    protected function initializeResourcesByImport(Import $import)
    {
        return $this->initializeResources($import->getStrategy(), $import->getFilepath());
    }

    /**
     * @param \HDNET\Importr\Domain\Model\Strategy $strategy
     * @param string                               $filepath
     *
     * @return array
     */
    protected function initializeResources(Strategy $strategy, $filepath)
    {
        $resources = [];
        $resourceConfiguration = $strategy->getResources();
        foreach ($resourceConfiguration as $resource => $configuration) {
            $object = $this->objectManager->get($resource);
            $object->start($strategy, $filepath);
            $object->setConfiguration($configuration);
            $resources[$resource] = $object;
        }
        return $resources;
    }

    /**
     * @param \HDNET\Importr\Domain\Model\Import $import
     *
     * @return array
     */
    protected function initializeTargets(Import $import)
    {
        $targets = [];
        $targetConfiguration = $import->getStrategy()
            ->getTargets();
        foreach ($targetConfiguration as $target => $configuration) {
            $object = $this->objectManager->get($target);
            $object->setConfiguration($configuration);
            $object->getConfiguration();
            $object->start($import->getStrategy());
            $targets[$target] = $object;
        }
        return $targets;
    }

    /**
     * @param int $interval
     */
    public function setUpdateInterval($interval)
    {
        $this->updateInterval = $interval;
    }

    /**
     * @return int
     */
    public function getUpdateInterval()
    {
        return $this->updateInterval;
    }
}
