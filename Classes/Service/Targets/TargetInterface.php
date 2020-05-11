<?php

declare(strict_types=1);

namespace HDNET\Importr\Service\Targets;

use HDNET\Importr\Domain\Model\Strategy;

/**
 * Description of TargetInterface
 *
 * @author timlochmueller
 */
interface TargetInterface
{
    const RESULT_INSERT = 1;

    const RESULT_UPDATE = 2;

    const RESULT_IGNORED = 3;

    const RESULT_UNSURE = 4;

    const RESULT_ERROR = 5;

    /**
     * @param $strategy Strategy
     */
    public function start(Strategy $strategy);

    /**
     * @param $entry array
     *
     * @return int
     */
    public function processEntry(array $entry);

    /**
     * @return array
     */
    public function getConfiguration();

    public function end();
}
