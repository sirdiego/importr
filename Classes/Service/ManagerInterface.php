<?php

declare(strict_types=1);
namespace HDNET\Importr\Service;

/**
 * ManagerInterface
 */
interface ManagerInterface
{
    /**
     * @param int $interval
     */
    public function setUpdateInterval($interval);

    /**
     * @return int
     */
    public function getUpdateInterval();
}
