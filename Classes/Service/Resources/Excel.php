<?php
namespace HDNET\Importr\Service\Resources;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Description of Excel
 *
 * @author timlochmueller
 */
class Excel extends AbstractResource implements ResourceInterface {

	/**
	 * @var string
	 */
	protected $filepathExpression = "/.xls$/";

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
		$configuration['skipRows'] = isset($configuration['skipRows']) ? (int)$configuration['skipRows'] : 0;
		$configuration['sheet'] = isset($configuration['sheet']) ? (int)$configuration['sheet'] : -1;
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

		if (!ExtensionManagementUtility::isLoaded('phpexcel_library')) {
			throw new \Exception('phpexcel_library is not loaded', 12367812368);
		}

		$filename = GeneralUtility::getFileAbsFileName($this->filepath);
		GeneralUtility::makeInstanceService('phpexcel');

		$objReader = \PHPExcel_IOFactory::createReaderForFile($filename);
		$objReader->setReadDataOnly(TRUE);
		$objPHPExcel = $objReader->load($filename);
		if ($configuration['sheet'] >= 0) {
			$objWorksheet = $objPHPExcel->getSheet($configuration['sheet']);
		} else {
			$objWorksheet = $objPHPExcel->getActiveSheet();
		}

		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();

		$highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

		for ($row = 1 + $configuration['skipRows']; $row <= $highestRow; ++$row) {
			$rowRecord = array();
			for ($col = 0; $col <= $highestColumnIndex; ++$col) {
				$rowRecord[] = trim($objWorksheet->getCellByColumnAndRow($col, $row)
				                                 ->getValue());
			}
			$this->content[] = $rowRecord;
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