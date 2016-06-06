<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

$GLOBALS['TCA']['tx_importr_domain_model_strategy'] = array(
	'ctrl' => $GLOBALS['TCA']['tx_importr_domain_model_strategy']['ctrl'],
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