<?php
namespace HDNET\Importr\Tests\Unit\Feature;

use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Domain\Model\Strategy;
use HDNET\Importr\Feature\RenameFile;
use HDNET\Importr\Service\FileService;
use HDNET\Importr\Service\Manager;
use HDNET\Importr\Service\ManagerInterface;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

/**
 * Class RenameFileTest
 */
class RenameFileTest extends UnitTestCase
{
    /**
     * @var RenameFile
     */
    protected $fixture;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|FileService
     */
    protected $fileService;

    /**
     * @var vfsStreamDirectory
     */
    protected $root;

    public function setUp()
    {
        $this->root = vfsStream::setup('import');
        /** @var \PHPUnit_Framework_MockObject_MockObject|FileService $fileService */
        $fileService = $this->getMock(FileService::class);
        $this->fileService = $fileService;
        $this->fixture = new RenameFile($fileService);
    }

    /**
     * @test
     */
    public function does_execute_rename_file_when_configured()
    {
        $manager = $this->getMock(ManagerInterface::class);
        $strategy = $this->getMock(Strategy::class);
        $strategy->expects($this->once())->method('getConfiguration')->will($this->returnValue(['after' => ['rename' => true]]));
        $import = $this->getMock(Import::class);
        $import->expects($this->once())->method('getStrategy')->will($this->returnValue($strategy));

        $file = vfsStream::newFile('import.csv')->at($this->root)->setContent("test;test");
        $oldFileName = $file->getName();

        $this->fileService->expects($this->any())->method('getFileAbsFileName')->will($this->returnCallback(function () use ($file) {
            return $file->url();
        }));

        $this->fixture->execute($manager, $import);

        $children = $this->root->getChildren();
        $this->assertEquals(1, sizeof($children));
        $this->assertRegExp('/^[0-9]{14}_' . $oldFileName . '$/', $children[0]->getName());
    }

    /**
     * @test
     */
    public function check_if_single_slot_gets_registered()
    {
        $this->fixture->enable();
        $dispatcher = GeneralUtility::makeInstance(Dispatcher::class);
        $slots = $dispatcher->getSlots(Manager::class, 'afterImport');
        $expectedSlots = [
            [
                'class' => get_class($this->fixture),
                'method' => 'execute',
                'object' => null,
                'passSignalInformation' => true,
            ],
        ];
        $this->assertEquals($expectedSlots, $slots);
    }
}
