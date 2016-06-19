<?php
namespace HDNET\Importr\Feature;

use HDNET\Importr\Service\ManagerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RenameFile extends AbstractFeature
{

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
     * @param ManagerInterface $manager
     * @param \HDNET\Importr\Domain\Model\Import $import
     */
    public function execute(ManagerInterface $manager, Import $import)
    {
        $configuration = $import->getStrategy()
            ->getConfiguration();
        if (isset($configuration['after']['rename'])) {
            $oldFileName = GeneralUtility::getFileAbsFileName($import->getFilepath());
            $info = pathinfo($oldFileName);
            $newFileName = $info['dirname'] . DIRECTORY_SEPARATOR . date('YmdHis') . '_' . $info['basename'];
            rename($oldFileName, $newFileName);
        }
    }
}
