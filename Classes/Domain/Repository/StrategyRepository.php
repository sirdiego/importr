<?php

/**
 * StrategyRepository
 */

namespace HDNET\Importr\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * StrategyRepository
 */
class StrategyRepository extends Repository
{

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array
     */
    public function findAllUser()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()
            ->setRespectStoragePage(false);
        $query->matching($query->equals('general', 1));
        $query->setOrderings(['title' => Query::ORDER_ASCENDING]);
        return $query->execute();
    }
}
