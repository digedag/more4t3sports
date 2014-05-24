<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');


// Die TCE-Hooks
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_more4t3sports_hooks_TCEHook.php:tx_more4t3sports_hooks_TCEHook';
//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getMainFieldsClass'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_cfcleague_hooks_tcehook.php:tx_cfcleague_hooks_tcehook';
//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_cfcleague_hooks_cmhooks.php:tx_cfcleague_hooks_cmhooks';

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['t3sportsbet']['srv_Bet_analysebets_finished_hook'][] = 'EXT:' . $_EXTKEY . '/hooks/class.tx_more4t3sports_hooks_T3sportsBet.php:&tx_more4t3sports_hooks_T3sportsBet->analyseBets';


