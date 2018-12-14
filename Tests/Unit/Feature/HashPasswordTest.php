<?php
/**
 * HashPasswordTest.php
 */

namespace HDNET\Importr\Tests\Unit\Feature;

use HDNET\Importr\Feature\HashPassword;
use HDNET\Importr\Processor\Target;
use HDNET\Importr\Service\PasswordHashService;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

/**
 * Class HashPasswordTest
 */
class HashPasswordTest extends UnitTestCase
{
    /**
     * @var HashPassword
     */
    protected $fixture;

    /**
     * @var PasswordHashService|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $service;

    public function setUp()
    {
        $this->service = $this->getAccessibleMock(PasswordHashService::class);
        $this->service->expects($this->any())->method('hash')->will($this->returnValue('password'));
        $this->fixture = new HashPassword($this->service);
    }

    /**
     * @test
     */
    public function check_if_single_slot_gets_registered()
    {
        $this->fixture->enable();
        $dispatcher = GeneralUtility::makeInstance(Dispatcher::class);
        $slots = $dispatcher->getSlots(Target::class, 'preProcess');
        $expectedSlots = [
            [
                'class' => get_class($this->fixture),
                'method' => 'execute',
                'object' => null,
                'passSignalInformation' => true,
            ],
        ];
        $this->assertEquals($expectedSlots, $slots);
    }
}
