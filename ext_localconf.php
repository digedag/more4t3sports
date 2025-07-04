<?php

if (!(defined('TYPO3') || defined('TYPO3_MODE'))) {
    exit('Access denied.');
}

$_EXTKEY = 'more4t3sports';

// -------------------------
// ---- SERVICES -----------
// -------------------------
if (Sys25\RnBase\Utility\Extensions::isLoaded('t3socials')) {
    Sys25\RnBase\Utility\Extensions::addService($_EXTKEY, 't3sports_srv' /* sv type */ , 'tx_more4t3sports_srv_Socials' /* sv key */ ,
        [
            'title' => 'Social networks', 'description' => 'Handles communications with social networks',
            'subtype' => 'socials',
            'available' => true, 'priority' => 50, 'quality' => 50,
            'os' => '', 'exec' => '',
            'className' => 'tx_more4t3sports_srv_Socials',
        ]
    );

    // *** **************** *** *
    // *** Register Trigger *** *
    // *** **************** *** *
    // Diese Trigger registrieren, damit sie im TCE-Formular auswählbar sind.
    // Diese Events werden nicht über den Autosend von T3socials ausgeführt.
    tx_t3socials_trigger_Config::registerTrigger(
        tx_rnbase::makeInstance('tx_t3socials_models_TriggerConfig', ['trigger_id' => 'matchstatus'])
    );
    tx_t3socials_trigger_Config::registerTrigger(
        tx_rnbase::makeInstance('tx_t3socials_models_TriggerConfig', ['trigger_id' => 'betgameUpdated'])
    );
    tx_t3socials_trigger_Config::registerTrigger(
        tx_rnbase::makeInstance('tx_t3socials_models_TriggerConfig', ['trigger_id' => 'liveticker'])
    );

    // -------------------------
    // ------- HOOKS -----------
    // -------------------------
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'Sys25\More4T3sports\Hook\TCEHook';
    // $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getMainFieldsClass'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_cfcleague_hooks_tcehook.php:tx_cfcleague_hooks_tcehook';
    // $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_cfcleague_hooks_cmhooks.php:tx_cfcleague_hooks_cmhooks';
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportsbet']['srv_Bet_analysebets_finished_hook'][] = 'Sys25\More4T3sports\Hook\T3sportsBet->analyseBets';
}

// -------------------------
// ------- HOOKS -----------
// -------------------------
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cfc_league_fe']['matchMarker_initRecord'][] = 'Sys25\More4T3sports\Hook\MatchMarkerHook->addNewsRecords';

if (!Sys25\RnBase\Utility\TYPO3::isTYPO115OrHigher() && Sys25\RnBase\Utility\Extensions::isLoaded('news')) {
    /** @var TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher */
    $signalSlotDispatcher = tx_rnbase::makeInstance(TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
    $signalSlotDispatcher->connect(
        'GeorgRinger\News\Controller\NewsController', 'detailAction',
        Sys25\More4T3sports\Listener\NewsListener::class, 'lookupNewsRecord'
    );
}
