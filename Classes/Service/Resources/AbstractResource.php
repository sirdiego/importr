<?php

declare(strict_types=1);
namespace HDNET\Importr\Service\Resources;

/**
 * Description of AbstractResource
 *
 * @author timlochmueller
 */
class AbstractResource
{

    /**
     * @var array
     */
    protected $configuration;

    /**
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param array $configuration
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param $key
     * @param $value
     */
    public function addConfiguration($key, $value)
    {
        $this->configuration[$key] = $value;
    }
}
