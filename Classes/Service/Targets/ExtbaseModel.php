<?php
namespace HDNET\Importr\Service\Targets;

use HDNET\Importr\Domain\Model\Strategy;
use HDNET\Importr\Utility;

/**
 * Description of ExtbaseModel
 *
 * @author tim
 */
class ExtbaseModel extends AbstractTarget implements TargetInterface {

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
	protected $epm;

	/**
	 * @return array
	 */
	public function getConfiguration() {
		$configuration = parent::getConfiguration();
		$configuration['pid'] = (isset($configuration['pid']) && is_numeric($configuration['pid'])) ? $configuration['pid'] : 0;
		return $configuration;
	}

	/**
	 * @param \HDNET\Importr\Domain\Model\Strategy $strategy
	 *
	 * @return void
	 */
	public function start(Strategy $strategy) {
		$this->epm = Utility::createObject('Tx_Extbase_Persistence_Manager');
		$this->strategy = $strategy;
	}

	/**
	 * @param array $entry
	 *
	 * @return int|void
	 */
	public function processEntry(array $entry) {
		$configuration = $this->getConfiguration();
		$this->initializeRepository($configuration);
		$model = $this->mapModel($this->getModel(), $configuration['mapping'], $entry);
		$this->repository->add($model);
		$this->epm->persistAll();
		if (isset($configuration['language'])) {
			foreach ($configuration['language'] as $languageKey => $mapping) {
				$modelLang = $this->mapModel($this->getModel(), $mapping, $entry);
				/** @noinspection PhpUndefinedMethodInspection */
				$modelLang->setSysLanguageUid($languageKey);
				/** @noinspection PhpUndefinedMethodInspection */
				$modelLang->setL10nParent($model);
				$this->repository->add($modelLang);
			}
		}
		$this->epm->persistAll();

		return TargetInterface::RESULT_INSERT;
	}

	public function end() {
	}

	/**
	 * @param \TYPO3\CMS\Extbase\DomainObject\AbstractEntity $model
	 * @param array                                          $mapping
	 * @param                                                $entry
	 *
	 * @return \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
	 */
	protected function mapModel($model, $mapping, $entry) {
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
	protected function getModel() {
		$configuration = $this->getConfiguration();
		/** @var \TYPO3\CMS\Extbase\DomainObject\AbstractEntity $model */
		$model = new $configuration['model'];
		$model->setPid($configuration['pid']);
		return $model;
	}

	/**
	 * @param array $configuration
	 */
	protected function initializeRepository($configuration) {
		$this->repository = Utility::createObject($configuration['repository']);
	}
}
