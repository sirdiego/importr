<?php
namespace HDNET\Importr\Service\Targets;

/**
 * Description of AbstractTarget
 *
 * @author timlochmueller
 */
class AbstractTarget {

	/**
	 * @var array
	 */
	protected $configuration;

	public function getConfiguration() {
		return $this->configuration;
	}

	public function setConfiguration($configuration) {
		$this->configuration = $configuration;
	}

	public function addConfiguration($key, $value) {
		$this->configuration[$key] = $value;
	}

}