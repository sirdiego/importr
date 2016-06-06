<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

$GLOBALS['TCA']['tx_importr_domain_model_import'] = array(
	'ctrl' => $GLOBALS['TCA']['tx_importr_domain_model_import']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'strategy,filepath,starttime,endtime,pointer,amount',
	),
	'types' => array(
		'1' => array('showitem' => 'strategy,filepath,starttime,endtime,pointer,amount'),
	),
	'columns' => array(
		'starttime' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'pointer' => array(
			'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:pointer',
			'config' => array(
				'type' => 'none',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'amount' => array(
			'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:amount',
			'config' => array(
				'type' => 'none',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'errors' => array(
			'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:error',
			'config' => array(
				'type' => 'none',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'inserted' => array(
			'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:error',
			'config' => array(
				'type' => 'none',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'updated' => array(
			'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:error',
			'config' => array(
				'type' => 'none',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'ignored' => array(
			'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:error',
			'config' => array(
				'type' => 'none',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'unknowns' => array(
			'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:error',
			'config' => array(
				'type' => 'none',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'filepath' => array(
			'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:filepath',
			'config' => array(
				'type' => 'input',
				'size' => 50,
				'eval' => 'trim,required',
				'wizards' => Array(
					'_PADDING' => 2,
					'link' => Array(
						'type' => 'popup',
						'title' => 'Link',
						'icon' => 'link_popup.gif',
						'script' => 'browse_links.php?mode=wizard',
						'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1',
						'params' => Array(
							'blindLinkOptions' => 'page,url,mail,spec,folder',
							'allowedExtensions' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['textfile_ext'],
						)
					),
				),
			),
		),
		'strategy' => array(
			'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:strategy',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_importr_domain_model_strategy',
				'maxitems' => 1,
				'size' => 1,
			),
		),
	),
);