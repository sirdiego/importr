<?php
namespace HDNET\Importr\Tests\Unit\Processor;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Processor\Resource;
use HDNET\Importr\Processor\Target;
use HDNET\Importr\Processor\Configuration;
use HDNET\Importr\Service\ManagerInterface;
use HDNET\Importr\Service\ImportServiceInterface;
use HDNET\Importr\Service\Resources\ResourceInterface;

class ResourceTest extends UnitTestCase {

    /**
     * @var Resource
     */
    protected $fixture;

    public function setUp()
    {
        $configuration = $this->getMockBuilder(Configuration::class)->disableOriginalConstructor()->getMock();
        $target = $this->getMockBuilder(Target::class)->disableOriginalConstructor()->getMock();
        $service = $this->getMock(ImportServiceInterface::class);
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
}
