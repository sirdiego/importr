<?php

declare(strict_types=1);

namespace HDNET\Importr\Tests\Unit\Processor;

use HDNET\Importr\Domain\Model\Strategy;
use HDNET\Importr\Domain\Repository\StrategyRepository;
use HDNET\Importr\Processor\Configuration;
use HDNET\Importr\Service\ImportServiceInterface;
use HDNET\Importr\Service\ManagerInterface;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

/**
 * ConfigurationTest
 */
class ConfigurationTest extends UnitTestCase
{
    /**
     * @var Configuration
     */
    protected $fixture;

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    public function setUp()
    {
        $this->fixture = $this->getConfiguration();
    }

    /**
     * @param bool $shouldAddToQueueBeCalled
     *
     * @return Configuration
     */
    protected function getConfiguration($shouldAddToQueueBeCalled = false)
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Dispatcher $dispatcher */
        $dispatcher = $this->getMockBuilder(Dispatcher::class)->getMock();
        $this->dispatcher = $dispatcher;
        /** @var \PHPUnit_Framework_MockObject_MockObject|StrategyRepository $repository */
        $repository = $this->getMockBuilder(StrategyRepository::class)->disableOriginalConstructor()->getMock();
        $repository->method('findByUid')->willReturn(new Strategy());
        /** @var \PHPUnit_Framework_MockObject_MockObject|ImportServiceInterface $service */
        $service = $this->getMockBuilder(ImportServiceInterface::class)->getMock();
        if ($shouldAddToQueueBeCalled) {
            $service->expects(self::once())->method('addToQueue');
        }

        return new Configuration($dispatcher, $repository, $service);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ManagerInterface
     */
    protected function getManagerMock()
    {
        return $this->getMockBuilder(ManagerInterface::class)->getMock();
    }

    /**
     * @test
     */
    public function updateInterval()
    {
        $manager = $this->getManagerMock();
        $manager->expects(self::once())
            ->method('setUpdateInterval')
            ->with(self::equalTo(1));

        $configuration = [
            'updateInterval' => 1,
        ];

        $this->fixture->process($configuration, $manager);
    }

    /**
     * @test
     * @expectedException \HDNET\Importr\Exception\ReinitializeException
     */
    public function reinitialize()
    {
        $manager = $this->getManagerMock();

        $configuration = [
            'reinitializeScheduler' => 1,
        ];

        $this->fixture->process($configuration, $manager);
    }

    /**
     * @test
     */
    public function createImport()
    {
        $manager = $this->getManagerMock();

        $configuration = [
            'createImport' => [
                ['importId' => 1]
            ],
        ];

        $this
            ->dispatcher
            ->expects(self::exactly(2))
            ->method('dispatch')
            ->withConsecutive(
                ['HDNET\Importr\Processor\Configuration', 'preParseConfiguration'],
                ['HDNET\Importr\Processor\Configuration', 'postParseConfiguration']
            );

        $this
            ->fixture
            ->process($configuration, $manager);
    }

    /**
     * @test
     */
    public function can_process_checks()
    {
        $result = $this->fixture->canProcess(['each' => []], 'each');
        self::assertTrue($result);
        $result = $this->fixture->canProcess(['each' => []], 'after');
        self::assertFalse($result);
    }

    /**
     * @test
     */
    public function processes_with_filter()
    {
        $manager = $this->getMockBuilder(ManagerInterface::class)->getMock();
        $manager->expects(self::once())
            ->method('setUpdateInterval')
            ->with(self::equalTo(42));
        $result = $this->fixture->process(['each' => ['updateInterval' => 42]], $manager, 'each');
    }

    /**
     * @test
     */
    public function does_not_processes_with_invalid_filter()
    {
        $manager = $this->getMockBuilder(ManagerInterface::class)->getMock();
        $this->dispatcher->expects(self::never())->method('dispatch');
        $result = $this->fixture->process(['each' => ['updateInterval' => 42]], $manager, 'lorem_ipsum');
    }
}
