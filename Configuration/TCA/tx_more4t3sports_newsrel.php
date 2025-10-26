<?php

if (!(defined('TYPO3') || defined('TYPO3_MODE'))) {
    exit('Access denied.');
}

$ll = 'LLL:EXT:more4t3sports/Resources/Private/Language/locallang_db.xlf:';

$tx_more4t3sports_newsrel = [
    'ctrl' => [
        'title' => $ll.'tx_more4t3sports_newsrel',
        'label' => 'uid',
        'label_alt' => 'category',
        'label_alt_force' => 1,
        'searchFields' => '',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
        ],
        'iconfile' => 'EXT:more4t3sports/Resources/Public/Icons/Extension.svg',
    ],
    'feInterface' => [
        'fe_admin_fieldList' => 'category, news',
    ],
    'columns' => [
        'category' => [
            'exclude' => 1,
            'label' => $ll.'tx_more4t3sports_newsrel_category',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_category',
                'foreign_table_where' => '
                    AND sys_category.parent = ###PAGE_TSCONFIG_ID###
                    ORDER BY sys_category.title
                ',
                'maxitems' => 1,
                'minitems' => 0,

                // 'type' => 'select',
                // 'renderType' => 'selectTree',
                // 'foreign_table' => 'sys_category',
                // 'treeConfig' => [
                //     'parentField' => 'parent',
                //     'appearance' => [
                //         'expandAll' => true,
                //         'showHeader' => true,
                //     ],
                // ],
                // 'maxitems' => 2,
                // 'minitems' => 0,
                // 'multiple' => 0,

                // 'type' => 'category',
                // 'relationship' => 'oneToOne',
                // 'treeConfig' => [
                //     'appearance' => [
                //         'expandAll' => true,
                //         'showHeader' => true,
                //     ],
                // ],
            ],
        ],
        'news' => [
            'exclude' => 1,
            'label' => $ll.'tx_more4t3sports_newsrel_news',
            'config' => [
                'type' => 'group',
                'allowed' => 'tx_news_domain_model_news',
                'readonly' => '1',
                'maxitems' => 1,
                'size' => '1',
            ],
        ],
        'uri' => [
            'exclude' => false,
            'label' => $ll.'tx_more4t3sports_newsrel_uri',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'placeholder' => $ll.'tx_more4t3sports_newsrel_uri_placeholder',
                'size' => 30,
                'eval' => 'trim',
                'softref' => 'typolink',
            ],
        ],
        'parentid' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'parenttable' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => 'category,news,uri',
        ],
    ],
    'palettes' => [
        '1' => [
            'showitem' => '',
        ],
    ],
];

return $tx_more4t3sports_newsrel;
