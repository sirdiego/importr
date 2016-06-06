<?php
namespace HDNET\Importr\Service\Resources;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Description of Csv
 *
 * Tx_Importr_Service_Resources_Csv:
 *  [length: 1000]
 *  [delimiter: ,]
 *  [enclosure: "]
 *  [escape: \]
 *
 * @author timlochmueller
 */
class Csv extends AbstractResource implements ResourceInterface {

	/**
	 * @var string
	 */
	protected $filepathExpression = "/.csv$/";

	/**
	 * @var array
	 */
	protected $content = array();

	/**
	 * @var string
	 */
	protected $filepath;

	/**
	 * @return mixed
	 */
	public function getConfiguration() {
		$configuration = parent::getConfiguration();
		$configuration['length'] = (isset($configuration['length']) && is_numeric($configuration['length'])) ? $configuration['length'] : 1000;
		$configuration['delimiter'] = isset($configuration['delimiter']) ? $configuration['delimiter'] : ';';
		$configuration['enclosure'] = isset($configuration['enclosure']) ? $configuration['enclosure'] : '"';
		$configuration['escape'] = isset($configuration['escape']) ? $configuration['escape'] : '\\';
		$configuration['skipRows'] = isset($configuration['skipRows']) ? $configuration['skipRows'] : '0';
		return $configuration;
	}

	/**
	 * @param \HDNET\Importr\Domain\Model\Strategy $strategy
	 * @param string                               $filepath
	 */
	public function start(\HDNET\Importr\Domain\Model\Strategy $strategy, $filepath) {
		$this->filepath = $filepath;
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
		ini_set('auto_detect_line_endings', TRUE);
		if (($handle = fopen(GeneralUtility::getFileAbsFileName($this->filepath), "r")) !== FALSE) {
			$row = 0;
			while (($buffer = fgetcsv($handle, $configuration['length'], $configuration['delimiter'], $configuration['enclosure'], $configuration['escape'])) !== FALSE) {
				if ($row < $configuration['skipRows']) {
					$row++;
					continue;
				}

				$this->content[] = $buffer;
				$row++;
			}
			fclose($handle);
		}
		ini_set('auto_detect_line_endings', FALSE);
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