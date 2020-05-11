<?php

declare(strict_types=1);
namespace HDNET\Importr\Tests\Unit\Feature;

use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Domain\Model\Strategy;
use HDNET\Importr\Feature\RenameFile;
use HDNET\Importr\Service\FileService;
use HDNET\Importr\Service\Manager;
use HDNET\Importr\Service\ManagerInterface;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
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
     * @var MockObject|AccessibleObjectInterface|FileService
     */
    protected $fileService;

    /**
     * @var vfsStreamDirectory
     */
    protected $root;

    public function setUp():void
    {
        $this->root = vfsStream::setup('import');
        /** @var MockObject|FileService $fileService */
        $fileService = $this->getAccessibleMock(FileService::class);
        $this->fileService = $fileService;
        $this->fixture = new RenameFile($fileService);
    }

    /**
     * @test
     */
    public function does_execute_rename_file_when_configured()
    {
        $manager = $this->getMockBuilder(ManagerInterface::class)->getMock();
        $strategy = $this->getAccessibleMock(Strategy::class);
        $strategy->expects(self::once())->method('getConfiguration')->willReturn(['after' => ['rename' => true]]);
        $import = $this->getAccessibleMock(Import::class);
        $import->expects(self::once())->method('getStrategy')->willReturn($strategy);

        $file = vfsStream::newFile('import.csv')->at($this->root)->setContent('test;test');
        $oldFileName = $file->getName();

        $this->fileService->expects(self::any())->method('getFileAbsFileName')->willReturnCallback(function () use ($file) {
            return $file->url();
        });

        $this->fixture->execute($manager, $import);

        $children = $this->root->getChildren();
        self::assertEquals(1, \count($children));
        self::assertMatchesRegularExpression('/^[0-9]{14}_' . $oldFileName . '$/', $children[0]->getName());
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
                'class' => \get_class($this->fixture),
                'method' => 'execute',
                'object' => null,
                'passSignalInformation' => true,
            ],
        ];
        self::assertEquals($expectedSlots, $slots);
    }
}
