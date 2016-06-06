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
$GLOBALS['TCA']['tx_importr_domain_model_import'] = array(
	'ctrl'      => array(
		'title'             => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:tx_importr_domain_model_import',
		'label'             => 'starttime',
		'label_alt'         => 'filepath',
		'label_alt_force'   => 1,
		'searchFields'      => 'filepath',
		'rootLevel'         => 1,
		'default_sortby'    => 'ORDER BY starttime',
		'delete'            => 'deleted',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/Tca/Import.php',
		'iconfile'          => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/Import.png',
	),
	'interface' => array(
		'showRecordFieldList' => ''
	)
);
$GLOBALS['TCA']['tx_importr_domain_model_strategy'] = array(
	'ctrl'      => array(
		'title'             => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:tx_importr_domain_model_strategy',
		'label'             => 'title',
		'searchFields'      => 'title',
		'rootLevel'         => 1,
		'delete'            => 'deleted',
		'default_sortby'    => 'ORDER BY title',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/Tca/Strategy.php',
		'iconfile'          => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/Strategy.png',
	),
	'interface' => array(
		'showRecordFieldList' => ''
	)
);
