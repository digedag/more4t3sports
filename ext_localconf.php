<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

require_once(t3lib_extMgm::extPath('rn_base') . 'class.tx_rnbase.php');
tx_rnbase::load('tx_rnbase_util_SearchBase');

// -------------------------
// ---- SERVICES -----------
// -------------------------
tx_rnbase::load('tx_more4t3sports_srv_Registry');

t3lib_extMgm::addService($_EXTKEY,  't3sports_srv' /* sv type */,  'tx_more4t3sports_srv_Socials' /* sv key */,
	array(
		'title' => 'Social networks', 'description' => 'Handles communications with social networks',
		'subtype' => 'socials',
		'available' => TRUE, 'priority' => 50, 'quality' => 50,
		'os' => '', 'exec' => '',
		'classFile' => t3lib_extMgm::extPath($_EXTKEY).'srv/class.tx_more4t3sports_srv_Socials.php',
		'className' => 'tx_more4t3sports_srv_Socials',
	)
);


// -------------------------
// ------- HOOKS -----------
// -------------------------
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_more4t3sports_hooks_TCEHook.php:tx_more4t3sports_hooks_TCEHook';
//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getMainFieldsClass'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_cfcleague_hooks_tcehook.php:tx_cfcleague_hooks_tcehook';
//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_cfcleague_hooks_cmhooks.php:tx_cfcleague_hooks_cmhooks';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportsbet']['srv_Bet_analysebets_finished_hook'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_more4t3sports_hooks_T3sportsBet.php:&tx_more4t3sports_hooks_T3sportsBet->analyseBets';





?>