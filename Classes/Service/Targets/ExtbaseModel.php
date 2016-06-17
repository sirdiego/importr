<?php
namespace HDNET\Importr\Service\Targets;

use HDNET\Importr\Domain\Model\Strategy;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;

/**
 * Description of ExtbaseModel
 *
 * @author tim
 */
class ExtbaseModel extends AbstractTarget implements TargetInterface
{

    /**
     * @var Strategy
     */
    protected $strategy;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\RepositoryInterface
     */
    protected $repository;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * ExtbaseModel constructor.
     * @param \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface $persistenceManager
     * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
     */
    public function __construct(PersistenceManagerInterface $persistenceManager, ObjectManagerInterface $objectManager)
    {
        $this->persistenceManager = $persistenceManager;
        $this->objectManager = $objectManager;
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        $configuration = parent::getConfiguration();
        $configuration['pid'] = (isset($configuration['pid']) && is_numeric($configuration['pid'])) ? $configuration['pid'] : 0;
        return $configuration;
    }

    /**
     * @param \HDNET\Importr\Domain\Model\Strategy $strategy
     *
     * @return void
     */
    public function start(Strategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @param array $entry
     *
     * @return int|void
     */
    public function processEntry(array $entry)
    {
        $configuration = $this->getConfiguration();
        $this->repository = $this->objectManager->get($configuration['repository']);
        $model = $this->mapModel($this->getModel(), $configuration['mapping'], $entry);
        if (isset($configuration['language']) && is_array($configuration['language'])) {
            $this->processLanguageEntries($configuration['language'], $model, $entry);
        }
        $this->repository->add($model);
        $this->persistenceManager->persistAll();

        $this->persistenceManager->persistAll();

        return TargetInterface::RESULT_INSERT;
    }

    /**
     * @param array $configuration
     * @param AbstractEntity $model
     * @param $entry
     */
    protected function processLanguageEntries(array $configuration, $model, $entry)
    {
        foreach ($configuration as $languageKey => $mapping) {
            $modelLang = $this->mapModel($this->getModel(), $mapping, $entry);

            if (method_exists($modelLang, 'setSysLanguageUid') && method_exists($modelLang, 'setL10nParent')) {
                $modelLang->setSysLanguageUid($languageKey);
                $modelLang->setL10nParent($model);

                $this->repository->add($modelLang);
            }
        }
    }

    public function end()
    {
    }

    /**
     * @param \TYPO3\CMS\Extbase\DomainObject\AbstractEntity $model
     * @param array $mapping
     * @param                                                $entry
     *
     * @return \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
     */
    protected function mapModel($model, $mapping, $entry)
    {
        if (is_array($mapping)) {
            foreach ($mapping as $key => $value) {
                $model->_setProperty($value, $entry[$key]);
            }
        }
        return $model;
    }

    /**
     * get a model in the right location
     *
     * @return \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
     */
    protected function getModel()
    {
        $configuration = $this->getConfiguration();
        /** @var \TYPO3\CMS\Extbase\DomainObject\AbstractEntity $model */
        $model = new $configuration['model'];
        $model->setPid($configuration['pid']);
        return $model;
    }
}
