<?php
namespace HDNET\Importr\Domain\Model;

use HDNET\Importr\Service\Targets\TargetInterface;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Description of Strategy
 *
 * @author timlochmueller
 */
class Import extends AbstractEntity {

	/**
	 * @var \HDNET\Importr\Domain\Model\Strategy
	 */
	protected $strategy;

	/**
	 * @var string
	 */
	protected $filepath;

	/**
	 * @var \DateTime
	 */
	protected $starttime;

	/**
	 * @var \DateTime
	 */
	protected $endtime;

	/**
	 * @var integer
	 */
	protected $pointer;

	/**
	 * @var integer
	 */
	protected $amount;

	/**
	 * @var integer
	 */
	protected $inserted;

	/**
	 * @var integer
	 */
	protected $updated;

	/**
	 * @var integer
	 */
	protected $ignored;

	/**
	 * @var integer
	 */
	protected $unknowns;

	/**
	 * @var integer
	 */
	protected $errors;

	/**
	 * @return \HDNET\Importr\Domain\Model\Strategy
	 */
	public function getStrategy() {
		return $this->strategy;
	}

	/**
	 * @return string
	 */
	public function getFilepath() {
		return $this->filepath;
	}

	/**
	 * @return \DateTime
	 */
	public function getStarttime() {
		return $this->starttime;
	}

	/**
	 * @return \DateTime
	 */
	public function getEndtime() {
		return $this->endtime;
	}

	/**
	 * @return integer
	 */
	public function getPointer() {
		return $this->pointer;
	}

	/**
	 * @return integer
	 */
	public function getAmount() {
		return $this->amount;
	}

	/**
	 * @param \HDNET\Importr\Domain\Model\Strategy $strategy
	 */
	public function setStrategy(Strategy $strategy) {
		$this->strategy = $strategy;
	}

	/**
	 * @param string $filepath
	 */
	public function setFilepath($filepath) {
		$this->filepath = $filepath;
	}

	/**
	 * @param \DateTime $starttime
	 */
	public function setStarttime($starttime) {
		$this->starttime = $starttime;
	}

	/**
	 * @param \DateTime $endtime
	 */
	public function setEndtime($endtime) {
		$this->endtime = $endtime;
	}

	/**
	 * @param integer $pointer
	 */
	public function setPointer($pointer) {
		$this->pointer = $pointer;
	}

	/**
	 * @param integer $amount
	 */
	public function setAmount($amount) {
		$this->amount = $amount;
	}

	/**
	 * @return float|int
	 */
	public function getPercentage() {
		if ($this->getAmount() == 0) {
			return 100;
		}
		return $this->getPointer() / $this->getAmount() * 100;
	}

	/**
	 * @param int $errors
	 */
	public function setErrors($errors) {
		$this->errors = $errors;
	}

	/**
	 * @return int
	 */
	public function getErrors() {
		return (int)$this->errors;
	}

	/**
	 * @return float|int
	 */
	public function getErrorsPercentage() {
		if ($this->getAmount() == 0) {
			return 0;
		}
		return $this->getErrors() / $this->getAmount() * 100;
	}

	/**
	 * @param int $ignored
	 */
	public function setIgnored($ignored) {
		$this->ignored = $ignored;
	}

	/**
	 * @return int
	 */
	public function getIgnored() {
		return (int)$this->ignored;
	}

	/**
	 * @return float|int
	 */
	public function getIgnoredPercentage() {
		if ($this->getAmount() == 0) {
			return 0;
		}
		return $this->getIgnored() / $this->getAmount() * 100;
	}

	/**
	 * @param int $inserted
	 */
	public function setInserted($inserted) {
		$this->inserted = $inserted;
	}

	/**
	 * @return int
	 */
	public function getInserted() {
		return (int)$this->inserted;
	}

	/**
	 * @param int $unknowns
	 */
	public function setUnknowns($unknowns) {
		$this->unknowns = $unknowns;
	}

	/**
	 * @return int
	 */
	public function getUnknowns() {
		return (int)$this->unknowns;
	}

	/**
	 * @return float|int
	 */
	public function getUnknownsPercentage() {
		if ($this->getAmount() == 0) {
			return 0;
		}
		return $this->getUnknowns() / $this->getAmount() * 100;
	}

	/**
	 * @return float|int
	 */
	public function getInsertedPercentage() {
		if ($this->getAmount() == 0) {
			return 0;
		}
		return $this->getInserted() / $this->getAmount() * 100;
	}

	/**
	 * @param int $updated
	 */
	public function setUpdated($updated) {
		$this->updated = $updated;
	}

	/**
	 * @return int
	 */
	public function getUpdated() {
		return (int)$this->updated;
	}

	/**
	 * @return float|int
	 */
	public function getUpdatedPercentage() {
		if ($this->getAmount() == 0) {
			return 0;
		}
		return $this->getUpdated() / $this->getAmount() * 100;
	}

	/**
	 * @param int $type
	 */
	public function increaseCount($type) {
		switch ($type) {
			case TargetInterface::RESULT_INSERT:
				$this->setInserted($this->getInserted() + 1);
				break;
			case TargetInterface::RESULT_UPDATE:
				$this->setUpdated($this->getUpdated() + 1);
				break;
			case TargetInterface::RESULT_IGNORED:
				$this->setIgnored($this->getIgnored() + 1);
				break;
			case TargetInterface::RESULT_UNSURE:
				$this->setUnknowns($this->getUnknowns() + 1);
				break;
			case TargetInterface::RESULT_ERROR:
				$this->setErrors($this->getErrors() + 1);
				break;
		}
	}
}