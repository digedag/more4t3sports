<?php

//#######################################################################
// Extension Manager/Repository config file for ext "rn_base".
//
// Auto generated 14-01-2012 13:44
//
// Manual updates:
// Only the data in the array - everything else is removed by next
// writing. "version" and "dependencies" must not be touched!
//#######################################################################

$EM_CONF[$_EXTKEY] = [
    'title' => 'More for T3sports',
    'description' => 'Useful extensions for T3sports.',
    'category' => 'misc',
    'version' => '0.7.0',
    'dependencies' => 'cms',
    'module' => '',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearcacheonload' => 0,
    'author' => 'Rene Nitzsche',
    'author_email' => 'rene@system25.de',
    'author_company' => 'System 25',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-11.5.99',
            'php' => '7.1.0-8.2.99',
            'rn_base' => '1.16.0-0.0.0',
            'cfc_league' => '1.10.0-0.0.0',
        ],
        'conflicts' => [],
        'suggests' => [
            't3socials' => '1.0.5-0.0.0',
            'tt_news' => '3.6.0-0.0.0',
            'news' => '7.2.0-0.0.0',
        ],
    ],
];
