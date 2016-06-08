<?php
namespace HDNET\Importr\Command;

use HDNET\Importr\Service\Manager;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageQueue;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\CommandController;

/**
 * Import CommandController for initializing the Tx_Importr_Service_Manager
 *
 * @package     Extension\importr
 * @subpackage  Command
 * @license     http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @author      Tim Lochmüller <tim.lochmueller@hdnet.de>
 * @author      Tim Spiekerkötter <tim.spiekerkoetter@hdnet.de>
 * @version     $Id:$
 */

/**
 * Import CommandController for initializing the Tx_Importr_Service_Manager
 *
 * @package    Extension\importr
 * @subpackage Command
 */
class ImportCommandController extends CommandController {

	/**
	 * @var \TYPO3\CMS\Extbase\Mvc\Cli\CommandManager
	 * @inject
	 */
	protected $commandManager;

	/**
	 * @var array
	 */
	protected $commandsByExtensionsAndControllers = [];

	/**
	 * initializes the import service manager
	 *
	 * @param string $mail Set an email address for error reporting
	 *
	 * @return boolean
	 */
	public function initializeServiceManagerCommand($mail = NULL) {
		$message = $this->objectManager->get(FlashMessage::class, '', 'Initializing ServiceManager', FlashMessage::INFO);
		/** @noinspection PhpUndefinedMethodInspection */
		FlashMessageQueue::addMessage($message);
		$manager = $this->objectManager->get(Manager::class);
		try {
			// let the manager run the imports now
			$manager->runImports();
		} catch (\Exception $e) {
			$message = $this->objectManager->get(FlashMessage::class, '', 'An Error occured: ' . $e->getCode() . ': ' . $e->getMessage(), FlashMessage::ERROR);
			/** @noinspection PhpUndefinedMethodInspection */
			FlashMessageQueue::addMessage($message);
			// if mail is configured send an email
			if ($mail !== NULL && GeneralUtility::validEmail($mail)) {
				// @TODO: send mail
			}
			return FALSE;
		}
		return TRUE;
	}
}
