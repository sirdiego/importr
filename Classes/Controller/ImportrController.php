<?php

declare(strict_types=1);

namespace HDNET\Importr\Controller;

use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Domain\Model\Strategy;
use HDNET\Importr\Domain\Repository\ImportRepository;
use HDNET\Importr\Domain\Repository\StrategyRepository;
use HDNET\Importr\Service\ImportServiceInterface;
use HDNET\Importr\Service\Manager;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Description of ImportrController
 *
 * @author timlochmueller
 */
class ImportrController extends ActionController
{
    /**
     * @var \TYPO3\CMS\Core\Resource\ResourceFactory
     */
    protected $resourceFactory;

    /**
     * @var \HDNET\Importr\Domain\Repository\StrategyRepository
     */
    protected $strategyRepository;

    /**
     * @var \HDNET\Importr\Domain\Repository\ImportRepository
     */
    protected $importRepository;

    /**
     * @var \HDNET\Importr\Service\Manager
     */
    protected $importManager;

    /**
     * @var \HDNET\Importr\Service\ImportServiceInterface
     */
    protected $importService;

    public function __construct(
        ResourceFactory $resourceFactory,
        StrategyRepository $strategyRepository,
        ImportRepository $importRepository,
        Manager $importManager,
        ImportServiceInterface $importService
    ) {
        $this->resourceFactory = $resourceFactory;
        $this->strategyRepository = $strategyRepository;
        $this->importRepository = $importRepository;
        $this->importManager = $importManager;
        $this->importService = $importService;
    }

    public function indexAction()
    {
        $combinedIdentifier = GeneralUtility::_GP('id');
        if (isset($combinedIdentifier) && \is_string($combinedIdentifier)) {
            $folder = $this->resourceFactory->getFolderObjectFromCombinedIdentifier($combinedIdentifier);
            $files = [];
            foreach ($folder->getFiles() as $file) {
                $files[$file->getStorage()
                    ->getUid() . ':' . $file->getIdentifier()] = $file->getName();
            }
            $this->view->assign('folder', $files);
        }
        $this->view->assign('imports', $this->importRepository->findUserQueue());
    }

    /**
     * @param string $identifier
     */
    public function importAction($identifier)
    {
        $file = $this->resourceFactory->getObjectFromCombinedIdentifier($identifier);
        $this->view->assign('file', $file);
        $this->view->assign('strategies', $this->strategyRepository->findAllUser());
    }

    /**
     * @param string $identifier
     * @param \HDNET\Importr\Domain\Model\Strategy $strategy
     */
    public function previewAction($identifier, Strategy $strategy)
    {
        $file = $this->resourceFactory->getObjectFromCombinedIdentifier($identifier);
        $this->view->assign('filepath', $file->getPublicUrl());
        $this->view->assign('strategy', $strategy);

        $previewData = $this->importManager->getPreview($strategy, $file->getPublicUrl());
        $this->view->assign('preview', $previewData);
    }

    /**
     * @param string $filepath
     * @param \HDNET\Importr\Domain\Model\Strategy $strategy
     */
    public function createAction($filepath, Strategy $strategy)
    {
        $this->importService->addToQueue($filepath, $strategy);
        $text = 'The Import file %s width the strategy %s was successfully added to the queue';
        $message = GeneralUtility::makeInstance(
            FlashMessage::class,
            \sprintf($text, $filepath, $strategy->getTitle()),
            'Import is in Queue',
            FlashMessage::INFO,
            true
        );

        $flashMessageService = $this->objectManager->get(
            FlashMessageService::class
        );
        $messageQueue = $flashMessageService->getMessageQueueByIdentifier();
        $messageQueue->addMessage($message);

        $this->redirect('index');
    }

    /**
     * @param Import $import
     */
    public function resetAction(Import $import)
    {
        $import->reset();
        $this->importRepository->update($import);
        $this->redirect('index');
    }
}
