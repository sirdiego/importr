<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

return array(
    'ctrl'      => array(
        'title'             => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:tx_importr_domain_model_strategy',
        'label'             => 'title',
        'searchFields'      => 'title',
        'rootLevel'         => 1,
        'delete'            => 'deleted',
        'default_sortby'    => 'ORDER BY title',
        'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('importr') . 'Configuration/Tca/Strategy.php',
        'iconfile'          => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('importr') . 'Resources/Public/Icons/Strategy.png',
    ),
	'interface' => array(
		'showRecordFieldList' => 'title, configuration, resources, targets',
	),
	'types' => array(
		'1' => array('showitem' => 'title, general, configuration, resources, targets'),
	),
	'columns' => array(
		'title' => array(
			'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:title',
			'config' => array(
				'type' => 'input',
				'size' => 50,
				'eval' => 'trim,required'
			),
		),
		'configuration' => array(
			'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:configuration',
			'config' => array(
				'type' => 'text',
				'rows' => 15,
				'cols' => 100,
			),
		),
		'resources' => array(
			'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:resources',
			'config' => array(
				'type' => 'text',
				'rows' => 15,
				'cols' => 100,
			),
		),
		'targets' => array(
			'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:targets',
			'config' => array(
				'type' => 'text',
				'rows' => 15,
				'cols' => 100,
			),
		),
		'general' => array(
			'label' => 'General strategy (selectable for the user)',
			'config' => array(
				'type' => 'check',
			),
		),
	),
);
