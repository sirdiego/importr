<?php
namespace HDNET\Importr\Domain\Model;

use HDNET\Importr\Service\Yaml;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Documentation for configuration
 *
 * For configuration:
 * overwriteFilepath: XXXX
 *
 * For Resources and Targets:
 * CLASSNAME:
 *   CLASSNAME-CONFIGURATIONS (see Class)
 *
 * @author timlochmueller
 */
class Strategy extends AbstractEntity
{

    /**
     *
     * @var string
     */
    protected $title;

    /**
     *
     * @var string
     */
    protected $configuration;

    /**
     *
     * @var string
     */
    protected $resources;

    /**
     *
     * @var string
     */
    protected $targets;

    /**
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getRawConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        $configuration = Yaml::parse($this->getRawConfiguration());
        $configuration['updateInterval'] = (isset($configuration['updateInterval']) && is_numeric($configuration['updateInterval'])) ? $configuration['updateInterval'] : 100;
        return $configuration;
    }

    /**
     * @return string
     */
    public function getRawResources()
    {
        return $this->resources;
    }

    /**
     * @return array
     */
    public function getResources()
    {
        return Yaml::parse($this->getRawResources());
    }

    /**
     * @return string
     */
    public function getRawTargets()
    {
        return $this->targets;
    }

    /**
     * @return array
     */
    public function getTargets()
    {
        return Yaml::parse($this->getRawTargets());
    }

    /**
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     *
     * @param string $configuration
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     *
     * @param string $resources
     */
    public function setResources($resources)
    {
        $this->resources = $resources;
    }

    /**
     *
     * @param string $targets
     */
    public function setTargets($targets)
    {
        $this->targets = $targets;
    }
}
