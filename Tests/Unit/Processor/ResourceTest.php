<?php

declare(strict_types=1);
namespace HDNET\Importr\Tests\Unit\Processor;

use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Processor\Configuration;
use HDNET\Importr\Processor\Resource;
use HDNET\Importr\Processor\Target;
use HDNET\Importr\Service\ImportServiceInterface;
use HDNET\Importr\Service\ManagerInterface;
use HDNET\Importr\Service\Resources\ResourceInterface;
use HDNET\Importr\Service\Targets\TargetInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class ResourceTest extends UnitTestCase
{

    /**
     * @var resource
     */
    protected $fixture;

    /**
     * @var Target
     */
    protected $target;

    /**
     * @var ImportServiceInterface
     */
    protected $service;

    public function setUp():void
    {
        $configuration = $this->getMockBuilder(Configuration::class)->disableOriginalConstructor()->getMock();
        $target = $this->getMockBuilder(Target::class)->disableOriginalConstructor()->getMock();
        $this->target = $target;
        $service = $this->getMockBuilder(ImportServiceInterface::class)->getMock();
        $this->service = $service;
        $this->fixture = new Resource($configuration, $target, $service);
    }

    /**
     * @test
     */
    public function do_not_process_on_filepath_missmatch()
    {
        $import = $this->getAccessibleMock(Import::class);
        $import->expects(self::once())->method('getFilepath')->willReturn('./import.xlsx');
        $resource = $this->getMockBuilder(ResourceInterface::class)->getMock();
        $resource->expects(self::once())->method('getFilepathExpression')->willReturn('/\.csv$/');
        $manager = $this->getMockBuilder(ManagerInterface::class)->getMock();
        $result = $this->fixture->process($import, [], [], $resource, $manager);
        self::assertFalse($result);
    }

    /**
     * @test
     */
    public function calls_target_processor()
    {
        $this->target->expects(self::once())->method('process');

        $import = $this->getAccessibleMock(Import::class);
        $import->expects(self::once())->method('getFilepath')->willReturn('./import.csv');
        $import->expects(self::once())->method('getPointer')->willReturn(0);
        $import->expects(self::exactly(2))->method('getAmount')->willReturn(1);
        $resource = $this->getMockBuilder(ResourceInterface::class)->getMock();
        $resource->expects(self::once())->method('getFilepathExpression')->willReturn('/\.csv$/');
        $manager = $this->getMockBuilder(ManagerInterface::class)->getMock();
        $manager->expects(self::any())->method('getUpdateInterval')->willReturn(42);
        $target = $this->getMockBuilder(TargetInterface::class)->getMock();

        $result = $this->fixture->process($import, [$target], [], $resource, $manager);
    }

    /**
     * @test
     */
    public function updates_current_import_status()
    {
        $import = $this->getAccessibleMock(Import::class);
        $import->expects(self::once())->method('getFilepath')->willReturn('./import.csv');
        $import->expects(self::once())->method('getPointer')->willReturn(1);
        $import->expects(self::exactly(2))->method('getAmount')->willReturn(2);

        $this->service->expects(self::exactly(3))->method('updateImport')->withConsecutive([$import], [$import, 2], [$import, 2]);

        $resource = $this->getMockBuilder(ResourceInterface::class)->getMock();
        $resource->expects(self::once())->method('getFilepathExpression')->willReturn('/\.csv$/');
        $manager = $this->getMockBuilder(ManagerInterface::class)->getMock();
        $manager->expects(self::any())->method('getUpdateInterval')->willReturn(2);
        $target = $this->getMockBuilder(TargetInterface::class)->getMock();

        $result = $this->fixture->process($import, [$target], [], $resource, $manager);
    }
}
