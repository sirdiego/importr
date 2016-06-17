<?php
namespace HDNET\Importr\Tests\Unit\Parser;

use HDNET\Importr\Domain\Model\Strategy;
use HDNET\Importr\Parser\Configuration;
use HDNET\Importr\Service\ManagerInterface;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Persistence\RepositoryInterface;
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
        /** @var \PHPUnit_Framework_MockObject_MockObject|Dispatcher $dispatcher */
        $dispatcher = $this->getMockBuilder(Dispatcher::class)->getMock();
        /** @var \PHPUnit_Framework_MockObject_MockObject|RepositoryInterface $repository */
        $repository = $this->getMockBuilder(RepositoryInterface::class)->getMock();
        $repository->method('findByUid')->willReturn(new Strategy());

        $this->fixture = new Configuration($dispatcher, $repository);
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

        $this->fixture->parse($configuration, $manager);
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

        $this->fixture->parse($configuration, $manager);
    }

    /**
     * @test
     */
    public function createImport()
    {
        $manager = $this->getManagerMock();
        $manager->expects($this->once())->method('addToQueue');

        $configuration = [
            'createImport' => [
                ['importId' => 1,]
            ],
        ];

        $this->fixture->parse($configuration, $manager);
    }
}
