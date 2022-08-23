<?php

defined('TYPO3_MODE') or die();

// Configure new fields:
$fields = [
    'color' => [
        'label' => 'Farbe',
        'exclude' => 1,
        'config' => [
            'type' => 'input',
            'renderType' => 'colorpicker',
            'size' => 10,
        ],
    ]
];


// Add new fields to pages:
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_groups', $fields);

// Make fields visible in the TCEforms:
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'fe_groups', // Table name
    '--palette--;;tx_forum_forum',
    // Field list to add
    '', // List of specific types to add the field list to. (If empty, all type entries are affected)
    'after:title' // Insert fields before (default) or after one, or replace a field
);

// Add the new palette:
$GLOBALS['TCA']['fe_groups']['palettes']['tx_forum_forum'] = [ 
    'showitem' => 'color'
];