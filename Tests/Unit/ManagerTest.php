<?php
namespace HDNET\Importr\Tests\Unit;

use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Domain\Model\Strategy;
use HDNET\Importr\Domain\Repository\ImportRepository;
use HDNET\Importr\Service\Manager;
use HDNET\Importr\Service\Resources\ResourceInterface;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use ReflectionClass;

class ManagerTest extends UnitTestCase {

    /**
     * @test
     */
    public function are_import_runs_executed() {
        $manager = $this->getMockBuilder(Manager::class)->setMethods(['runImport'])->getMock();

        $imports = [new Import, new Import];
        $manager->expects($this->exactly(2))->method('runImport')->withConsecutive(
            [$this->equalTo($imports[0])],
            [$this->equalTo($imports[1])]
        );

        $repository = $this->getMockBuilder(ImportRepository::class)->disableOriginalConstructor()->getMock();
        $repository->expects($this->once())->method('findWorkQueue')->willReturn($imports);

        $this->setProtectedProperty($manager, 'importRepository', $repository);

        $manager->runImports();
    }

    /**
     * @test
     */
    public function is_update_interval_updateable() {
        $manager = new Manager;
        $manager->setUpdateInterval(42);
        $this->assertEquals(42, $manager->getUpdateInterval());
    }

    /**
     * @test
     */
    public function is_preview_generated_correct()
    {
        $manager = new Manager;
        $resource = $this->getMockBuilder(ResourceInterface::class)->getMock();
        $resource->expects($this->any())->method('getEntry')->will($this->returnValue('test'));
        $resource->expects($this->once())->method('getFilepathExpression')->will($this->returnValue('/\.csv$/'));

        $objectManager = $this->getMockBuilder(ObjectManagerInterface::class)->getMock();
        $objectManager->expects($this->once())->method('get')->will($this->returnCallback(function () use ($resource) {
            return $resource;
        }));
        $this->setProtectedProperty($manager, 'objectManager', $objectManager);

        $strategy = $this->getAccessibleMock(Strategy::class);
        $filepath = './import.csv';

        $strategy->expects($this->once())->method('getResources')->will($this->returnValue([
            ResourceInterface::class => []
        ]));

        $data = $manager->getPreview($strategy, $filepath);

        $this->assertEquals(array_fill(0, 21, 'test'), $data);
    }

    protected function setProtectedProperty($object, $name, $value)
    {
        $reflection = new ReflectionClass($object);
        $property_reflection = $reflection->getProperty($name);
        $property_reflection->setAccessible(true);
        $property_reflection->setValue($object, $value);
    }
}
