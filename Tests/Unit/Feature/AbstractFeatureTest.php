<?php
namespace HDNET\Importr\Tests\Unit\Feature;

use HDNET\Importr\Feature\AbstractFeature;
use HDNET\Importr\Service\Manager;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

class AbstractFeatureTest extends UnitTestCase {

    /**
     * @test
     */
    public function check_if_single_slot_gets_registered() {
        $feature = $this->getMockForAbstractClass(AbstractFeature::class);
        $feature::enable('test');

        $dispatcher = GeneralUtility::makeInstance(Dispatcher::class);
        $slots = $dispatcher->getSlots(Manager::class, 'test');
        $expectedSlots = [
            [
                'class' => get_class($feature),
                'method' => 'execute',
                'object' => null,
                'passSignalInformation' => true,
            ],
        ];
        $this->assertEquals($expectedSlots, $slots);
    }
}
