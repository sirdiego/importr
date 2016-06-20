<?php
namespace HDNET\Importr\Feature;

use HDNET\Importr\Service\ManagerInterface;
use HDNET\Importr\Domain\Model\Import;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

abstract class AbstractFeature
{
    /**
     * @param ManagerInterface $manager
     * @param Import           $import
     * @return void
     */
    abstract public function execute(ManagerInterface $manager, Import $import);

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
