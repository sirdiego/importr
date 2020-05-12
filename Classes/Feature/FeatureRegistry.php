<?php

declare(strict_types=1);
/**
 * FeatureRegistry.php
 */
namespace HDNET\Importr\Feature;

use HDNET\Importr\Service\Manager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

/**
 * Class FeatureRegistry
 */
class FeatureRegistry
{
    /**
     * @param string|array $names
     * @param string       $class
     */
    public static function enable($names, $class = Manager::class)
    {

        $dispatcher = GeneralUtility::makeInstance(Dispatcher::class);
        if (!\is_array($names)) {
            $names = [$names];
        }

        $trace = \debug_backtrace(false, 2);
        $caller = $trace[1]['class'];

        foreach ($names as $name) {
            $dispatcher->connect($class, $name, $caller, 'execute');
        }
    }
}
