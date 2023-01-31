<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

return [
    'ctrl' => [
        'title' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xlf:tx_importr_domain_model_strategy',
        'label' => 'title',
        'searchFields' => 'title',
        'rootLevel' => 1,
        'delete' => 'deleted',
        'default_sortby' => 'ORDER BY title',
        'iconfile' => 'EXT:importr/Resources/Public/Icons/Strategy.png',
    ],
    'types' => [
        '1' => ['showitem' => 'title, general, configuration, configuration_file, resources, resources_file, targets, targets_file'],
    ],
    'columns' => [
        'title' => [
            'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xlf:title',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim,required'
            ],
        ],
        'configuration' => [
            'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xlf:configuration',
            'config' => [
                'type' => 'text',
                'rows' => 15,
                'cols' => 100,
            ],
        ],
        'configuration_file' => [
            'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xlf:configuration_file',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'max' => 256,
                'size' => 60,
                'softref' => 'typolink',
                'wizards' => [
                    'link' => [
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_link.gif',
                        'title' => 'File',
                        'type' => 'popup',
                        'module' => [
                            'name' => 'wizard_link',
                            'urlParameters' => [
                                'mode' => 'wizard',
                            ],
                        ],
                    ],
                    '_PADDING' => 2,
                ],
            ],
        ],
        'resources' => [
            'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xlf:resources',
            'config' => [
                'type' => 'text',
                'rows' => 15,
                'cols' => 100,
            ],
        ],
        'resources_file' => [
            'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xlf:resources_file',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'max' => 256,
                'size' => 60,
                'softref' => 'typolink',
                'wizards' => [
                    'link' => [
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_link.gif',
                        'title' => 'File',
                        'type' => 'popup',
                        'module' => [
                            'name' => 'wizard_link',
                            'urlParameters' => [
                                'mode' => 'wizard',
                            ],
                        ],
                    ],
                    '_PADDING' => 2,
                ],
            ],
        ],
        'targets' => [
            'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xlf:targets',
            'config' => [
                'type' => 'text',
                'rows' => 15,
                'cols' => 100,
            ],
        ],
        'targets_file' => [
            'label' => 'LLL:EXT:importr/Resources/Private/Language/locallang.xlf:targets_file',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'max' => 256,
                'size' => 60,
                'softref' => 'typolink',
                'wizards' => [
                    'link' => [
                        'icon' => 'EXT:backend/Resources/Public/Images/FormFieldWizard/wizard_link.gif',
                        'title' => 'File',
                        'type' => 'popup',
                        'module' => [
                            'name' => 'wizard_link',
                            'urlParameters' => [
                                'mode' => 'wizard',
                            ],
                        ],
                    ],
                    '_PADDING' => 2,
                ],
            ],
        ],
        'general' => [
            'label' => 'General strategy (selectable for the user)',
            'config' => [
                'type' => 'check',
            ],
        ],
    ],
];
