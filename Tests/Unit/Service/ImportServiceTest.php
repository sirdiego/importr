<?php
namespace HDNET\Importr\Tests\Unit\Service;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use HDNET\Importr\Service\ImportService;
use HDNET\Importr\Domain\Repository\ImportRepository;
use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Domain\Model\Strategy;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;

class ImportServiceTest extends UnitTestCase {

    /**
     * @var ImportService
     */
    protected $fixture;

    /**
     * @var ImportRepository
     */
    protected $repository;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    public function setUp()
    {
        $persistenceManager = $this->getMock(PersistenceManagerInterface::class);
        $objectManager = $this->getMock(ObjectManagerInterface::class);
        $repository = $this->getMockBuilder(ImportRepository::class)->disableOriginalConstructor()->getMock();
        $this->repository = $repository;
        $this->objectManager = $objectManager;

        $this->fixture = new ImportService($persistenceManager, $objectManager, $repository);
    }

    /**
     * @test
     */
    public function is_pointer_increased_on_update()
    {
        $import = $this->getMock(Import::class);
        $pointer = 5;

        $this->repository->expects($this->once())->method('update')->with($import);
        $import->expects($this->once())->method('setPointer')->with($pointer);

        $this->fixture->updateImport($import, $pointer);
    }

    /**
     * @test
     */
    public function is_import_added_to_queue()
    {
        $import = $this->getMock(Import::class);
        $strategy = $this->getMock(Strategy::class);
        $path = './import.csv';
        $time = '2016-06-19T13:49:39+00:00';

        $import->expects($this->once())->method('setStrategy')->with($strategy);
        $import->expects($this->once())->method('setFilepath')->with($path);
        $import->expects($this->once())->method('setStarttime')->with($this->callback(function ($date) use ($time) {
            return $date->format(\DateTime::ATOM) === $time;
        }));

        $this->objectManager->expects($this->once())->method('get')->with(Import::class)->will($this->returnCallback(function () use ($import) {
            return $import;
        }));

        $this->repository->expects($this->once())->method('add')->with($this->isInstanceOf(Import::class));

        $this->fixture->addToQueue($path, $strategy, ['start' => $time]);
    }

    /**
     * @test
     */
    public function is_datetime_created_with_invalid_text()
    {
        $import = $this->getMock(Import::class);
        $strategy = $this->getMock(Strategy::class);
        $path = './import.csv';

        $import->expects($this->once())->method('setStarttime')->with($this->isInstanceOf(\DateTime::class));

        $this->objectManager->expects($this->once())->method('get')->with(Import::class)->will($this->returnCallback(function () use ($import) {
            return $import;
        }));

        $this->fixture->addToQueue($path, $strategy, ['start' => 'Lorem ipsum']);
    }
}
