<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:forum/Resources/Private/Language/locallang_db.xlf:tx_forum_domain_model_forum',
        'label' => 'forum',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'forum',
        'iconfile' => 'EXT:forum/Resources/Public/Icons/tx_forum_domain_model_forum.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'forum, description, category, url_segment, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_forum_domain_model_forum',
                'foreign_table_where' => 'AND {#tx_forum_domain_model_forum}.{#pid}=###CURRENT_PID### AND {#tx_forum_domain_model_forum}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],

        'forum' => [
            'exclude' => true,
            'label' => 'LLL:EXT:forum/Resources/Private/Language/locallang_db.xlf:tx_forum_domain_model_forum.forum',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'description' => [
            'label' => 'Beschreibung',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
            ],
        ],
        'url_segment' => [
            'exclude' => true,
            'label' => 'URL / Slug',
            'config' => [
                'type' => 'slug',
                'size' => 30,
                'max' => 255,
                'eval' => 'uniqueInSite',
                'fallbackCharacter' => '-',
                'generatorOptions' => [
                    'fields' => ['forum'],
                    'replacements' => [
                        '/' => '-'
                    ],
                ],
            ],
        ],
        'category' => [
            'exclude' => true,
            'label' => 'LLL:EXT:forum/Resources/Private/Language/locallang_db.xlf:tx_forum_domain_model_forum.category',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_forum_domain_model_category',
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
            ],

        ],
    
    ],
];
