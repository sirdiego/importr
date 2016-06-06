<?php
namespace HDNET\Importr\Service\Resources;

/**
 * Description of AbstractResource
 *
 * @author timlochmueller
 */
class AbstractResource {

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