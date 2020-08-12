<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

// -------------------------
// ---- SERVICES -----------
// -------------------------
if(tx_rnbase_util_TYPO3::isExtLoaded('t3socials')) {
	tx_rnbase_util_Extensions::addService($_EXTKEY,  't3sports_srv' /* sv type */,  'tx_more4t3sports_srv_Socials' /* sv key */,
		array(
			'title' => 'Social networks', 'description' => 'Handles communications with social networks',
			'subtype' => 'socials',
			'available' => TRUE, 'priority' => 50, 'quality' => 50,
			'os' => '', 'exec' => '',
				'classFile' => tx_rnbase_util_Extensions::extPath($_EXTKEY).'srv/class.tx_more4t3sports_srv_Socials.php',
			'className' => 'tx_more4t3sports_srv_Socials',
		)
	);

	// *** **************** *** *
	// *** Register Trigger *** *
	// *** **************** *** *
	tx_rnbase::load('tx_t3socials_trigger_Config');
	// Diese Trigger registrieren, damit sie im TCE-Formular auswählbar sind.
	// Diese Events werden nicht über den Autosend von T3socials ausgeführt.
	tx_t3socials_trigger_Config::registerTrigger(
	tx_rnbase::makeInstance('tx_t3socials_models_TriggerConfig', array('trigger_id'=>'matchstatus'))
	);
	tx_t3socials_trigger_Config::registerTrigger(
	tx_rnbase::makeInstance('tx_t3socials_models_TriggerConfig', array('trigger_id'=>'betgameUpdated'))
	);
	tx_t3socials_trigger_Config::registerTrigger(
	tx_rnbase::makeInstance('tx_t3socials_models_TriggerConfig', array('trigger_id'=>'liveticker'))
	);

	// -------------------------
	// ------- HOOKS -----------
	// -------------------------
	$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_more4t3sports_hooks_TCEHook.php:tx_more4t3sports_hooks_TCEHook';
	//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getMainFieldsClass'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_cfcleague_hooks_tcehook.php:tx_cfcleague_hooks_tcehook';
	//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_cfcleague_hooks_cmhooks.php:tx_cfcleague_hooks_cmhooks';
	$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportsbet']['srv_Bet_analysebets_finished_hook'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_more4t3sports_hooks_T3sportsBet.php:&tx_more4t3sports_hooks_T3sportsBet->analyseBets';



}


// -------------------------
// ------- HOOKS -----------
// -------------------------
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['cfc_league_fe']['matchMarker_initRecord'][] = 'Tx_More4t3sports_Hook_MatchMarker->addNewsRecords';

/** @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $signalSlotDispatcher */
$signalSlotDispatcher = \tx_rnbase::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);
$signalSlotDispatcher->connect(
    'GeorgRinger\News\Controller\NewsController', 'detailAction',
    \Sys25\More4T3sports\Listener\NewsListener::class, 'lookupNewsRecord'
);
