<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

tx_rnbase::load('tx_rnbase_util_Extensions');

$columns = array(
	'newspreview' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:more4t3sports/Resources/Private/Language/locallang_db.xml:tx_cfcleague_games_newspreview',
			'config' => Array (
					'type' => 'group',
					'internal_type' => 'db',
					'allowed' => 'tt_news',
					'size' => 1,
					'minitems' => 0,
					'maxitems' => 1,
			)
	),
	'newsreport' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:more4t3sports/Resources/Private/Language/locallang_db.xml:tx_cfcleague_games_newsreport',
			'config' => Array (
					'type' => 'group',
					'internal_type' => 'db',
					'allowed' => 'tt_news',
					'size' => 1,
					'minitems' => 0,
					'maxitems' => 1,
			)
	),
);

tx_rnbase_util_Extensions::addTCAcolumns('tx_cfcleague_games',$columns,1);
tx_rnbase_util_Extensions::addToAllTCAtypes('tx_cfcleague_games','newspreview, newsreport','','after:game_report_author');

