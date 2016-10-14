<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}


$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = \HDNET\Importr\Command\ImportCommandController::class;

/**
 * Two possible (and implemented signals). You can use them
 * in your own Extension, just by coping the lines.
 *
 * Dont forget to
 *
 * By default they are not connected to the slots, because they
 * can be a security risk.
 *
 * \HDNET\Importr\Feature\RenameFile::enable();
 * \HDNET\Importr\Feature\TruncateTable::enable();
 */
