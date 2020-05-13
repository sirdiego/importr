<?php

declare(strict_types=1);
/**
 * HashPasswordTest.php
 */
namespace HDNET\Importr\Tests\Unit\Feature;

use HDNET\Importr\Feature\HashPassword;
use HDNET\Importr\Processor\Target;
use HDNET\Importr\Service\PasswordHashService;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

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
     * @var PasswordHashService|MockObject|AccessibleObjectInterface
     */
    protected $service;

    public function setUp(): void
    {
        $this->service = $this->getAccessibleMock(PasswordHashService::class);
        $this->service->expects(self::any())->method('hash')->willReturn('password');
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
                'class' => \get_class($this->fixture),
                'method' => 'execute',
                'object' => null,
                'passSignalInformation' => true,
            ],
        ];
        self::assertEquals($expectedSlots, $slots);
    }
}
