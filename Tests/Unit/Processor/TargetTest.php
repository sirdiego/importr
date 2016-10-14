<?php
namespace HDNET\Importr\Tests\Unit\Processor;

use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Processor\Target;
use HDNET\Importr\Service\ImportServiceInterface;
use HDNET\Importr\Service\Targets\TargetInterface;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

/**
 * TargetTest
 */
class TargetTest extends UnitTestCase
{
    /**
     * @var Target
     */
    protected $fixture;

    /**
     *
     */
    public function setUp()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Dispatcher $dispatcher */
        $dispatcher = $this->getMockBuilder(Dispatcher::class)->getMock();
        $dispatcher->expects($this->any())->method('dispatch')->will($this->returnValue([[], []]));
        /** @var \PHPUnit_Framework_MockObject_MockObject|ImportServiceInterface $importService */
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
        /** @var \PHPUnit_Framework_MockObject_MockObject|TargetInterface $target */
        $target = $this->getMockBuilder(TargetInterface::class)->getMock();
        $target->expects($this->once())
            ->method('processEntry')
            ->with($this->equalTo($entry))
            ->will($this->returnValue($result));
        $target->expects($this->any())->method('getConfiguration')->will($this->returnValue([]));
        /** @var \PHPUnit_Framework_MockObject_MockObject|Import $import */
        $import = $this->getMockBuilder(Import::class)->getMock();
        $import->expects($this->once())->method('increaseCount')->with($this->equalTo($result));
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
        /** @var \PHPUnit_Framework_MockObject_MockObject|TargetInterface $target */
        $target = $this->getMockBuilder(TargetInterface::class)->getMock();
        $target->expects($this->once())
            ->method('processEntry')
            ->with($this->equalTo($entry))
            ->will($this->throwException(new \Exception()));
        $target->expects($this->any())->method('getConfiguration')->will($this->returnValue([]));
        /** @var \PHPUnit_Framework_MockObject_MockObject|Import $import */
        $import = $this->getMockBuilder(Import::class)->getMock();
        $import->expects($this->once())->method('increaseCount')->with($this->equalTo(TargetInterface::RESULT_ERROR));
        $pointer = 1;
        $this->fixture->process($target, $entry, $import, $pointer);
    }
}
