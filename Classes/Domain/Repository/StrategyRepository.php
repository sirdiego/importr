<?php
namespace HDNET\Importr\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\Repository;

class StrategyRepository extends Repository {

	/**
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array
	 */
	public function findAllUser() {
		$query = $this->createQuery();
		$query->getQuerySettings()
		      ->setRespectStoragePage(FALSE);
		$query->matching($query->equals('general', 1));
		$query->setOrderings(array('title' => Query::ORDER_ASCENDING));
		return $query->execute();
	}

}