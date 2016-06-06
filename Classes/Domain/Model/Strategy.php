<?php
namespace HDNET\Importr\Domain\Model;

use HDNET\Importr\Service\Yaml;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Documentation for configuration
 *
 * For configuration:
 * overwriteFilepath: XXXX
 *
 * For Resources and Targets:
 * CLASSNAME:
 *   CLASSNAME-CONFIGURATIONS (see Class)
 *
 *
 * @author timlochmueller
 */
class Strategy extends AbstractEntity {

	/**
	 *
	 * @var string
	 */
	protected $title;

	/**
	 *
	 * @var string
	 */
	protected $configuration;

	/**
	 *
	 * @var string
	 */
	protected $resources;

	/**
	 *
	 * @var string
	 */
	protected $targets;

	/**
	 *
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param bool $returnAsArray
	 *
	 * @return string
	 */
	public function getConfiguration($returnAsArray = FALSE) {
		if ($returnAsArray) {
			$configuration = Yaml::parse($this->getConfiguration());
			$configuration['updateInterval'] = (isset($configuration['updateInterval']) && is_numeric($configuration['updateInterval'])) ? $configuration['updateInterval'] : 100;
			return $configuration;
		}
		return $this->configuration;
	}

	/**
	 * @param bool $returnAsArray
	 *
	 * @return string|array
	 */
	public function getResources($returnAsArray = FALSE) {
		if ($returnAsArray) {
			return Yaml::parse($this->getResources());
		}
		return $this->resources;
	}

	/**
	 * @param bool $returnAsArray
	 *
	 * @return string|array
	 */
	public function getTargets($returnAsArray = FALSE) {
		if ($returnAsArray) {
			return Yaml::parse($this->getTargets());
		}
		return $this->targets;
	}

	/**
	 *
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 *
	 * @param string $configuration
	 */
	public function setConfiguration($configuration) {
		$this->configuration = $configuration;
	}

	/**
	 *
	 * @param string $resources
	 */
	public function setResources($resources) {
		$this->resources = $resources;
	}

	/**
	 *
	 * @param string $targets
	 */
	public function setTargets($targets) {
		$this->targets = $targets;
	}

}