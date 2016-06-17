<?php
namespace HDNET\Importr\Service;

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
     * @return int
     */
    public function getUpdateInterval();
}
