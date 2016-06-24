<?php
/**
 * RenameFile.php
 */
namespace HDNET\Importr\Feature;

use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Service\FileService;
use HDNET\Importr\Service\Manager;
use HDNET\Importr\Service\ManagerInterface;

/**
 * Class RenameFile
 */
class RenameFile extends AbstractFeature
{
    /**
     * @var FileService
     */
    protected $fileService;

    /**
     * RenameFile constructor.
     * @param FileService $fileService
     */
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * @return void
     */
    public static function enable()
    {
        parent::enable('afterImport');
    }

    /**
     * To rename a file from the importr you
     * have to use the "rename: 1" statement in
     * your configuration. The file will be
     * prefixed with the current (human readable)
     * timestamp.
     *
     * Caution: after this method, the file is moved
     * you should only use this in the before
     * configuration if you are fully aware of it!
     *
     * @param  ManagerInterface $manager
     * @param  Import $import
     * @return void
     */
    public function execute(ManagerInterface $manager, Import $import)
    {
        $configuration = $import->getStrategy()
            ->getConfiguration();
        if (isset($configuration['after']['rename'])) {
            $oldFileName = $this->fileService->getFileAbsFileName($import->getFilepath());
            $info = pathinfo($oldFileName);
            $newFileName = $info['dirname'] . DIRECTORY_SEPARATOR . date('YmdHis') . '_' . $info['basename'];
            rename($oldFileName, $newFileName);
        }
    }
}
