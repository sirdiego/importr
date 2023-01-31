<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

/** @var string $_EXTKEY */

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule('Importr', 'file', 'tx_importr_mod', '', [
    \HDNET\Importr\Controller\ImportrController::class => 'index,import,preview,create,reset',
], [
    'access' => 'user,group',
    'icon' => 'EXT:importr/ext_icon.svg',
    'labels' => 'LLL:EXT:importr/Resources/Private/Language/locallang_mod.xlf',
]);
