<?php
namespace HDNET\Importr\Tests\Unit;

use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Domain\Repository\ImportRepository;
use HDNET\Importr\Service\Manager;
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

        $objectManager = $this->getMock(ObjectManagerInterface::class);
        $repository = $this->getMockBuilder(ImportRepository::class)->disableOriginalConstructor()->getMock();
        $repository->expects($this->once())->method('findWorkQueue')->willReturn($imports);

        $reflection = new ReflectionClass($manager);
        $property_reflection = $reflection->getProperty('importRepository');
        $property_reflection->setAccessible(true);
        $property_reflection->setValue($manager, $repository);

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
}
