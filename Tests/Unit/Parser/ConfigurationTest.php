<?php
namespace HDNET\Importr\Tests\Unit\Parser;

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
     *
     */
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
        /** @var \PHPUnit_Framework_MockObject_MockObject|StrategyRepository $repository */
        $repository = $this->getMockBuilder(StrategyRepository::class)->disableOriginalConstructor()->getMock();
        $repository->method('findByUid')->willReturn(new Strategy());
        /** @var \PHPUnit_Framework_MockObject_MockObject|ImportServiceInterface $service */
        $service = $this->getMockBuilder(ImportServiceInterface::class)->getMock();
        if ($shouldAddToQueueBeCalled) {
            $service->expects($this->once())->method('addToQueue');
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
        $manager->expects($this->once())
            ->method('setUpdateInterval')
            ->with($this->equalTo(1));

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

        $this->getConfiguration()->process($configuration, $manager);
    }
}
