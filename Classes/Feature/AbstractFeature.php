<?php
/**
 * AbstractFeature.php
 */
namespace HDNET\Importr\Feature;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

/**
 * Class AbstractFeature
 */
abstract class AbstractFeature
{
    /**
     * @param string|array $names
     */
    public static function enable($names, $class = 'HDNET\\Importr\\Service\\Manager')
    {
        $dispatcher = GeneralUtility::makeInstance(Dispatcher::class);
        if (!is_array($names)) {
            $names = [$names];
        }

        foreach ($names as $name) {
            $dispatcher->connect($class, $name, get_called_class(), 'execute');
        }
    }
}
