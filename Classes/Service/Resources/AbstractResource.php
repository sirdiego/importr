<?php
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
    public function setConfiguration($configuration)
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
