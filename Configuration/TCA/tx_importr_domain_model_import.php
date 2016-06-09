<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

return [
    'ctrl' => [
        'title' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:tx_importr_domain_model_import',
        'label' => 'starttime',
        'label_alt' => 'filepath',
        'label_alt_force' => 1,
        'searchFields' => 'filepath',
        'rootLevel' => 1,
        'default_sortby' => 'ORDER BY starttime',
        'delete' => 'deleted',
        'iconfile' => 'EXT:importr/Resources/Public/Icons/Import.png',
    ],
    'interface' => [
        'showRecordFieldList' => 'strategy,filepath,starttime,endtime,pointer,amount',
    ],
    'types' => [
        '1' => ['showitem' => 'strategy,filepath,starttime,endtime,pointer,amount'],
    ],
    'columns' => [
        'starttime' => [
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.starttime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'endtime' => [
            'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'max' => 20,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
            ],
        ],
        'pointer' => [
            'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:pointer',
            'config' => [
                'type' => 'none',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'amount' => [
            'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:amount',
            'config' => [
                'type' => 'none',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'errors' => [
            'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:error',
            'config' => [
                'type' => 'none',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'inserted' => [
            'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:error',
            'config' => [
                'type' => 'none',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'updated' => [
            'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:error',
            'config' => [
                'type' => 'none',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'ignored' => [
            'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:error',
            'config' => [
                'type' => 'none',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'unknowns' => [
            'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:error',
            'config' => [
                'type' => 'none',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'filepath' => [
            'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:filepath',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim,required',
                'wizards' => [
                    '_PADDING' => 2,
                    'link' => [
                        'type' => 'popup',
                        'title' => 'Link',
                        'icon' => 'link_popup.gif',
                        'script' => 'browse_links.php?mode=wizard',
                        'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1',
                        'params' => [
                            'blindLinkOptions' => 'page,url,mail,spec,folder',
                            'allowedExtensions' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['textfile_ext'],
                        ]
                    ],
                ],
            ],
        ],
        'strategy' => [
            'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xml:strategy',
            'config' => [
                'type' => 'select',
                'foreign_table' => 'tx_importr_domain_model_strategy',
                'maxitems' => 1,
                'size' => 1,
            ],
        ],
    ],
];
