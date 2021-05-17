<?php

declare(strict_types=1);

namespace HDNET\Importr\Domain\Model;

use Symfony\Component\Yaml\Yaml;
use TYPO3\CMS\Core\LinkHandling\Exception\UnknownUrnException;
use TYPO3\CMS\Core\LinkHandling\LinkService;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
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
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $configuration;

    /**
     * @var string
     */
    protected $configurationFile;

    /**
     * @var string
     */
    protected $resources;

    /**
     * @var string
     */
    protected $resourcesFile;

    /**
     * @var string
     */
    protected $targets;

    /**
     * @var string
     */
    protected $targetsFile;

    /**
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
        if ($config = GeneralUtility::getUrl($this->getRealFilePath($this->configurationFile))) {
            return $config;
        }

        return $this->configuration;
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        $configuration = Yaml::parse($this->getRawConfiguration());
        $configuration['updateInterval'] = (isset($configuration['updateInterval']) && \is_numeric($configuration['updateInterval'])) ? $configuration['updateInterval'] : 100;
        return $configuration;
    }

    /**
     * @return string
     */
    public function getRawResources()
    {
        if ($resources = GeneralUtility::getUrl($this->getRealFilePath($this->resourcesFile))) {
            return $resources;
        }

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
        if ($targets = GeneralUtility::getUrl($this->getRealFilePath($this->targetsFile))) {
            return $targets;
        }

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
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param string $configuration
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param string $resources
     */
    public function setResources($resources)
    {
        $this->resources = $resources;
    }

    /**
     * @param string $targets
     */
    public function setTargets($targets)
    {
        $this->targets = $targets;
    }

    /**
     * @param string $path
     * @return string
     */
    private function getRealFilePath($path)
    {
        if (!$path) {
            return '';
        }

        try {
            $service = new LinkService();
            $data = $service->resolveByStringRepresentation($path);
            if ($data['file'] instanceof FileInterface) {
                return $data['file']->getForLocalProcessing(false);
            } else {
                return GeneralUtility::getFileAbsFileName($path);
            }
        } catch (UnknownUrnException $e) {
            return GeneralUtility::getFileAbsFileName($path);
        }
    }
}
