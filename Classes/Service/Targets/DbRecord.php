<?php

declare(strict_types=1);
namespace HDNET\Importr\Service\Targets;

use HDNET\Importr\Domain\Model\Strategy;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Description of Tx_Importr_Service_Targets_DbRecord
 *
 * @author timlochmueller
 */
class DbRecord extends AbstractTarget implements TargetInterface
{

    /**
     * @param \HDNET\Importr\Domain\Model\Strategy $strategy
     */
    public function start(Strategy $strategy)
    {
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        $configuration = parent::getConfiguration();
        $configuration['pid'] = (isset($configuration['pid']) && \is_numeric($configuration['pid'])) ? $configuration['pid'] : 0;

        return $configuration;
    }

    /**
     * @param array $entry
     *
     * @return int
     */
    public function processEntry(array $entry)
    {
        $configuration = $this->getConfiguration();
        $mapping = $configuration['mapping'];

        $insertFields = [];
        foreach ($mapping as $key => $value) {
            $insertFields[$value] = $entry[$key];
        }

        $insertFields['pid'] = $configuration['pid'];

        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable($configuration['table']);
        $connection->insert($configuration['table'], $insertFields);

        return TargetInterface::RESULT_INSERT;
    }

    public function end()
    {
    }
}
