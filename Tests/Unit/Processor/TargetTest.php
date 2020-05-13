<?php

declare(strict_types=1);
namespace HDNET\Importr\Tests\Unit\Processor;

use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Processor\Target;
use HDNET\Importr\Service\ImportServiceInterface;
use HDNET\Importr\Service\Targets\TargetInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * TargetTest
 */
class TargetTest extends UnitTestCase
{
    /**
     * @var Target
     */
    protected $fixture;

    public function setUp(): void
    {
        /** @var MockObject|EventDispatcherInterface $dispatcher */
        $dispatcher = $this->getMockBuilder(EventDispatcherInterface::class)->getMock();
        $dispatcher->expects(self::any())->method('dispatch')->willReturn([[], []]);
        /** @var MockObject|ImportServiceInterface $importService */
        $importService = $this->getMockBuilder(ImportServiceInterface::class)->getMock();
        $this->fixture = new Target($importService, $dispatcher);
    }

    /**
     * @test
     */
    public function process()
    {
        $entry = [];
        $result = TargetInterface::RESULT_INSERT;
        /** @var MockObject|TargetInterface $target */
        $target = $this->getMockBuilder(TargetInterface::class)->getMock();
        $target->expects(self::once())
            ->method('processEntry')
            ->with(self::equalTo($entry))
            ->willReturn($result);
        $target->expects(self::any())->method('getConfiguration')->willReturn([]);
        /** @var MockObject|Import $import */
        $import = $this->getMockBuilder(Import::class)->getMock();
        $import->expects(self::once())->method('increaseCount')->with(self::equalTo($result));
        $pointer = 1;
        $this->fixture->process($target, $entry, $import, $pointer);
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function processWithException()
    {
        $entry = [];
        /** @var MockObject|TargetInterface $target */
        $target = $this->getMockBuilder(TargetInterface::class)->getMock();
        $target->expects(self::once())
            ->method('processEntry')
            ->with(self::equalTo($entry))
            ->will(self::throwException(new \Exception()));
        $target->expects(self::any())->method('getConfiguration')->willReturn([]);
        /** @var MockObject|Import $import */
        $import = $this->getMockBuilder(Import::class)->getMock();
        $import->expects(self::once())->method('increaseCount')->with(self::equalTo(TargetInterface::RESULT_ERROR));
        $pointer = 1;
        $this->fixture->process($target, $entry, $import, $pointer);
    }
}
