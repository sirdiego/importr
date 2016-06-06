<?php
namespace HDNET\Importr\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\Repository;

class ImportRepository extends Repository {

	/**
	 * Find imports for the user queue
	 *
	 * @param int $days
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array
	 */
	public function findUserQueue($days = 7) {
		$query = $this->createQuery();

		$conditions = array(
			$query->greaterThan('starttime', time() - 60 * 60 * 24 * $days)
		);

		$query->matching($query->logicalAnd($conditions));

		$query->setOrderings(array('starttime' => Query::ORDER_DESCENDING));

		return $query->execute();
	}

	/**
	 * Find imports for the working factory
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array
	 */
	public function findWorkQueue() {
		$query = $this->createQuery();

		$conditions = array(
			$query->lessThanOrEqual('starttime', time()),
			$query->lessThan('endtime', 1),
		);

		$query->matching($query->logicalAnd($conditions));

		$query->setOrderings(array('starttime' => Query::ORDER_ASCENDING));

		return $query->execute();
	}

}