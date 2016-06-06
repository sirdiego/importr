<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

if (TYPO3_MODE === 'BE') {
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'HDNET\\Importr\\Command\\ImportCommandController';
}
/**
 * Two possible (and implemented signals). You can use them
 * in your own Extension, just by coping the lines.
 *
 * Dont forget to
 *
 * By default they are not connected to the slots, because they
 * can be a security risk.
 *
 * t3lib_div::makeInstance('Tx_Extbase_SignalSlot_Dispatcher')->connect('Tx_Importr_Service_Manager', 'preParseConfiguration', 'Tx_Importr_Service_SignalService', 'truncateTable');
 * t3lib_div::makeInstance('Tx_Extbase_SignalSlot_Dispatcher')->connect('Tx_Importr_Service_Manager', 'pastImport', 'Tx_Importr_Service_SignalService', 'renameFile');
 */
