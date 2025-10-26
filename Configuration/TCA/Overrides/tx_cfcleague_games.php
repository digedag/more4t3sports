<?php

if (!(defined('TYPO3') || defined('TYPO3_MODE'))) {
    exit('Access denied.');
}

if (!Sys25\RnBase\Utility\Extensions::isLoaded('news')) {
    return;
}

$tableName = 'tx_news_domain_model_news';
$columns = [
    'newspreview' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:more4t3sports/Resources/Private/Language/locallang_db.xlf:tx_cfcleague_games_newspreview',
        'config' => [
            'type' => 'group',
            'internal_type' => 'db',
            'allowed' => $tableName,
            'size' => 1,
            'minitems' => 0,
            'maxitems' => 1,
        ],
    ],
    'newsreport' => [
        'exclude' => 1,
        'label' => 'LLL:EXT:more4t3sports/Resources/Private/Language/locallang_db.xlf:tx_cfcleague_games_newsreport',
        'config' => [
            'type' => 'group',
            'internal_type' => 'db',
            'allowed' => $tableName,
            'size' => 1,
            'minitems' => 0,
            'maxitems' => 1,
        ],
    ],

    'newsrels' => [
        'label' => 'LLL:EXT:more4t3sports/Resources/Private/Language/locallang_db.xlf:tx_cfcleague_games_newsrels',
        'description' => 'Related items',
        'config' => [
            'type' => 'inline',
            'foreign_table' => 'tx_more4t3sports_newsrel',
            'foreign_field' => 'parentid',
            'foreign_table_field' => 'parenttable',
            'foreign_sortby' => 'sorting',
            'appearance' => [
                'useSortable' => 1,
                'showNewRecordLink' => true,
                'newRecordLinkAddTitle' => true,
                'newRecordLinkTitle' => 'Neue News-Verknüpfung',
            ],
        ],
    ],
];

Sys25\RnBase\Utility\Extensions::addTCAcolumns('tx_cfcleague_games', $columns, 1);
Sys25\RnBase\Utility\Extensions::addToAllTCAtypes('tx_cfcleague_games', 'newspreview, newsreport', '', 'after:game_report_author');
Sys25\RnBase\Utility\Extensions::addToAllTCAtypes(
    'tx_cfcleague_games',
    '--div--;News-Relationen, newsrels',
    '',
    ''
);
