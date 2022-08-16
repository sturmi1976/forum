<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Forum',
    'description' => 'Foren Extension',
    'category' => 'plugin',
    'author' => 'Andre Lanius',
    'author_email' => 'a-lanius@web.de',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '1.2.0',
    'constraints' => [ 
        'depends' => [
            'typo3' => '11.5.0-12.5.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'seo' => '',
            'dashboard' => '',
            'typo3db_legacy' => ''
        ], 
    ],
];
