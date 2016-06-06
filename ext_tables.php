<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

/** @var string $_EXTKEY */

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule('HDNET.' . $_EXTKEY, 'file', 'tx_importr_mod', '', array(
	'Importr' => 'index,import,preview,create',
), array(
	'access' => 'user,group',
	'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
	'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xml'
));
