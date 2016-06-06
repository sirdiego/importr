<?php
namespace HDNET\Importr\Service\Targets;

/**
 * Description of ExtbaseModel
 *
 * @author tim
 */
class Dummy extends AbstractTarget implements TargetInterface {

	/**
	 * @return array
	 */
	public function getConfiguration() {
		$configuration = parent::getConfiguration();
		$configuration['sleepSeconds'] = (isset($configuration['sleepSeconds'])) ? (int)$configuration['sleepSeconds'] : 1;
		$configuration['result'] = (isset($configuration['result'])) ? (int)$configuration['result'] : 'unsure';
		return $configuration;
	}

	/**
	 * @param \HDNET\Importr\Domain\Model\Strategy $strategy
	 *
	 * @return void
	 */
	public function start(\HDNET\Importr\Domain\Model\Strategy $strategy) {

	}

	/**
	 * @param array $entry
	 *
	 * @throws \Exception
	 * @return int|void
	 */
	public function processEntry(array $entry) {
		$configuration = $this->getConfiguration();
		if ($configuration['sleepSeconds'] > 0) {
			sleep($configuration['sleepSeconds']);
		}

		// Return
		$results = array(
			'ignored',
			'insert',
			'error',
			'unsure',
			'update'
		);
		if ($configuration['result'] == 'random') {
			$configuration['result'] = $results[rand(0, sizeof($results) - 1)];
		}

		switch ($configuration['result']) {
			case 'ignored':
				return TargetInterface::RESULT_IGNORED;
			case 'insert':
				return TargetInterface::RESULT_INSERT;
			case 'error':
				return TargetInterface::RESULT_ERROR;
			case 'unsure':
				return TargetInterface::RESULT_UNSURE;
			case 'update':
				return TargetInterface::RESULT_UPDATE;
			default:
				throw new \Exception('Invalid result param "' . $configuration['result'] . '". Have to be one of: ' . var_export($results, TRUE), 12617283);

		}

	}

	public function end() {
	}
}
