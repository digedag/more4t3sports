<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

tx_rnbase::load('tx_rnbase_util_TYPO3');
if(!tx_rnbase_util_TYPO3::isTYPO62OrHigher()) {
	require tx_rnbase_util_Extensions::extPath($_EXTKEY).'Configuration/TCA/Overrides/tx_cfcleague_games.php';
}


tx_rnbase_util_Extensions::addStaticFile($_EXTKEY,'Configuration/Typoscript/News/', 'T3sports with tt_news');
