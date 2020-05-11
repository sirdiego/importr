<?php

declare(strict_types=1);

namespace HDNET\Importr\Service\Resources;

use HDNET\Importr\Domain\Model\Strategy;

/**
 * Description of ResourceInterface
 *
 * @author timlochmueller
 */
interface ResourceInterface
{

    /**
     * @param $strategy Strategy
     * @param $filepath array
     */
    public function start(Strategy $strategy, $filepath);

    /**
     * @return string
     */
    public function getFilepathExpression();

    public function parseResource();

    /**
     * @return int
     */
    public function getAmount();

    /**
     * @param int $pointer
     */
    public function getEntry($pointer);

    public function end();

    /**
     * @return array
     */
    public function getConfiguration();

    /**
     * @param array $configuration
     */
    public function setConfiguration(array $configuration);

    /**
     * @param mixed $key
     * @param mixed $value
     */
    public function addConfiguration($key, $value);
}
