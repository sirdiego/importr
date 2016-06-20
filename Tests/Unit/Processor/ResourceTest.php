<?php
namespace HDNET\Importr\Tests\Unit\Processor;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Processor\Resource;
use HDNET\Importr\Processor\Target;
use HDNET\Importr\Processor\Configuration;
use HDNET\Importr\Service\ManagerInterface;
use HDNET\Importr\Service\ImportServiceInterface;
use HDNET\Importr\Service\Targets\TargetInterface;
use HDNET\Importr\Service\Resources\ResourceInterface;

class ResourceTest extends UnitTestCase {

    /**
     * @var Resource
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

    public function setUp()
    {
        $configuration = $this->getMockBuilder(Configuration::class)->disableOriginalConstructor()->getMock();
        $target = $this->getMockBuilder(Target::class)->disableOriginalConstructor()->getMock();
        $this->target = $target;
        $service = $this->getMock(ImportServiceInterface::class);
        $this->service = $service;
        $this->fixture = new Resource($configuration, $target, $service);
    }

    /**
     * @test
     */
    public function do_not_process_on_filepath_missmatch()
    {
        $import = $this->getMock(Import::class);
        $import->expects($this->once())->method('getFilepath')->will($this->returnValue('./import.xlsx'));
        $resource = $this->getMock(ResourceInterface::class);
        $resource->expects($this->once())->method('getFilepathExpression')->will($this->returnValue('/\.csv$/'));
        $manager = $this->getMock(ManagerInterface::class);
        $result = $this->fixture->process($import, [], [], $resource, $manager);
        $this->assertEquals(false, $result);
    }

    /**
     * @test
     */
    public function calls_target_processor()
    {
        $this->target->expects($this->once())->method('process');

        $import = $this->getMock(Import::class);
        $import->expects($this->once())->method('getFilepath')->will($this->returnValue('./import.csv'));
        $import->expects($this->once())->method('getPointer')->will($this->returnValue(0));
        $import->expects($this->exactly(2))->method('getAmount')->will($this->returnValue(1));
        $resource = $this->getMock(ResourceInterface::class);
        $resource->expects($this->once())->method('getFilepathExpression')->will($this->returnValue('/\.csv$/'));
        $manager = $this->getMock(ManagerInterface::class);
        $manager->expects($this->any())->method('getUpdateInterval')->will($this->returnValue(42));
        $target = $this->getMock(TargetInterface::class);

        $result = $this->fixture->process($import, [$target], [], $resource, $manager);
    }

    /**
     * @test
     */
    public function updates_current_import_status()
    {
        $import = $this->getMock(Import::class);
        $import->expects($this->once())->method('getFilepath')->will($this->returnValue('./import.csv'));
        $import->expects($this->once())->method('getPointer')->will($this->returnValue(1));
        $import->expects($this->exactly(2))->method('getAmount')->will($this->returnValue(2));

        $this->service->expects($this->exactly(3))->method('updateImport')->withConsecutive([$import], [$import, 2], [$import, 2]);

        $resource = $this->getMock(ResourceInterface::class);
        $resource->expects($this->once())->method('getFilepathExpression')->will($this->returnValue('/\.csv$/'));
        $manager = $this->getMock(ManagerInterface::class);
        $manager->expects($this->any())->method('getUpdateInterval')->will($this->returnValue(2));
        $target = $this->getMock(TargetInterface::class);

        $result = $this->fixture->process($import, [$target], [], $resource, $manager);
    }
}
