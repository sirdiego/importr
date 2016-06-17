<?php
namespace HDNET\Importr\Service;

use HDNET\Importr\Domain\Model\Strategy;

/**
 * ManagerInterface
 */
interface ManagerInterface
{
    /**
     * @param int $interval
     * @return void
     */
    public function setUpdateInterval($interval);

    /**
     * @param $filepath
     * @param Strategy $strategy
     * @param array $configuration
     *
     * @return void
     */
    public function addToQueue($filepath, Strategy $strategy, array $configuration);
}
