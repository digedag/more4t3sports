<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

tx_rnbase::load('tx_rnbase_util_Extensions');
tx_rnbase::load('tx_rnbase_util_TYPO3');
if(!tx_rnbase_util_TYPO3::isExtLoaded('tt_news') && !tx_rnbase_util_TYPO3::isExtLoaded('news')) {
    return ;
}

$tableName = tx_rnbase_util_TYPO3::isExtLoaded('news') ? 'tx_news_domain_model_news' : 'tt_news';
$columns = [
	'newspreview' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:more4t3sports/Resources/Private/Language/locallang_db.xml:tx_cfcleague_games_newspreview',
			'config' => [
					'type' => 'group',
					'internal_type' => 'db',
					'allowed' => $tableName,
					'size' => 1,
					'minitems' => 0,
					'maxitems' => 1,
			]
	],
	'newsreport' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:more4t3sports/Resources/Private/Language/locallang_db.xml:tx_cfcleague_games_newsreport',
			'config' => [
					'type' => 'group',
					'internal_type' => 'db',
					'allowed' => $tableName,
					'size' => 1,
					'minitems' => 0,
					'maxitems' => 1,
			]
	],
];

tx_rnbase_util_Extensions::addTCAcolumns('tx_cfcleague_games',$columns,1);
tx_rnbase_util_Extensions::addToAllTCAtypes('tx_cfcleague_games','newspreview, newsreport','','after:game_report_author');

