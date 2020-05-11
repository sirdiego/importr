<?php

declare(strict_types=1);
namespace HDNET\Importr\Tests\Unit\Feature;

use HDNET\Importr\Feature\FeatureRegistry;
use HDNET\Importr\Service\Manager;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

class FeatureRegistryTest extends UnitTestCase
{

    /**
     * @test
     */
    public function check_if_single_slot_gets_registered()
    {
        FeatureRegistry::enable('test');

        $dispatcher = GeneralUtility::makeInstance(Dispatcher::class);
        $slots = $dispatcher->getSlots(Manager::class, 'test');
        $expectedSlots = [
            [
                'class' => \get_class($this),
                'method' => 'execute',
                'object' => null,
                'passSignalInformation' => true,
            ],
        ];
        self::assertEquals($expectedSlots, $slots);
    }
}
