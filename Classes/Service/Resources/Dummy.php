<?php
namespace HDNET\Importr\Service\Resources;

/**
 * Description of Excel
 *
 * @author timlochmueller
 */
class Dummy extends AbstractResource implements ResourceInterface {

	/**
	 * @var string
	 */
	protected $filepathExpression = "/.*/";

	/**
	 * @var array
	 */
	protected $content = array();

	/**
	 * @var string
	 */
	protected $loremIpsum = 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.';

	/**
	 * @param bool $returnAsArray
	 *
	 * @return mixed
	 */
	public function getConfiguration() {
		$configuration = parent::getConfiguration();
		$configuration['records'] = isset($configuration['records']) ? (int)$configuration['records'] : 50;
		$configuration['itemsPerRecord'] = isset($configuration['itemsPerRecord']) ? (int)$configuration['itemsPerRecord'] : 5;
		return $configuration;
	}

	/**
	 *
	 * Get Random content
	 *
	 * @return int|string
	 */
	protected function getRandomContent() {
		if (rand(0, 1)) {
			return rand(0, 100) * 5;
		} else {
			$pos1 = rand(0, strlen($this->loremIpsum));
			$pos2 = rand(0, strlen($this->loremIpsum));
			if ($pos1 > $pos2) {
				return substr($this->loremIpsum, $pos2, $pos1);
			} else {
				return substr($this->loremIpsum, $pos1, $pos2);
			}
		}
	}

	/**
	 * @param \HDNET\Importr\Domain\Model\Strategy $strategy
	 * @param string                               $filepath
	 */
	public function start(\HDNET\Importr\Domain\Model\Strategy $strategy, $filepath) {

	}

	/**
	 * @return string
	 */
	public function getFilepathExpression() {
		return $this->filepathExpression;
	}

	/**
	 *
	 */
	public function parseResource() {
		$configuration = $this->getConfiguration();

		for ($i = 0; $i < $configuration['records']; $i++) {
			$record = array();

			for ($a = 0; $a < $configuration['itemsPerRecord']; $a++) {
				$record[] = $this->getRandomContent();
			}
			$this->content[] = $record;
		}
	}

	/**
	 * @return integer
	 */
	public function getAmount() {
		return count($this->content);
	}

	/**
	 * @param integer $pointer
	 *
	 * @return mixed
	 */
	public function getEntry($pointer) {
		return $this->content[$pointer];
	}

	/**
	 *
	 */
	public function end() {

	}

}