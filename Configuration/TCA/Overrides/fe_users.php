<?php

// Configure new fields:
$fields = [
    'birth_day' => [
        'label' => 'Geburtstag',
        'exclude' => 1,
        'config' => [
            'type' => 'input',
            'renderType' => 'inputDateTime',
            'eval' => 'datetime',
            'max' => 255
        ],
    ],
    
    'username_path' => [
        'label' => 'Username Pathsegment',
        'exclude' => 1,
        'config' => [
            'type' => 'input',
            'generatorOptions' => [
              'fields' => ['username'],
              'replacements' => [
                  '/' => '-',
                  '.' => '',
                  '®' => '',
                  ',' => '',
                  '|' => '',
                  ' ' => '-',
              ],
          ],
        ],
    ],
    
    'profilbild' => [
        'label' => 'Profilbild',
        'exclude' => 1,
        'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
        'profilbild',
        [
            'appearance' => [
               'createNewRelationLinkTitle' => 'LLL:EXT:cms/locallang_ttc.xlf:images.addFileReference'
            ],
            // custom configuration for displaying fields in the overlay/reference table
            // to use the image overlay palette instead of the basic overlay palette
            'overrideChildTca' => [
                'types' => [
                    '0' => [
                        'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                        'showitem' => '
                            --palette--;LLL:EXT:lang/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                            --palette--;;filePalette'
                    ],
                ],
            ],
        ],
        $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
    ),
    ],

];

// Add new fields to pages:
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_users', $fields);

// Make fields visible in the TCEforms:
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'fe_users', // Table name
    '--palette--;;tx_forum_forum',
    // Field list to add
    '', // List of specific types to add the field list to. (If empty, all type entries are affected)
    'before:company' // Insert fields before (default) or after one, or replace a field
);

// Add the new palette:
$GLOBALS['TCA']['fe_users']['palettes']['tx_forum_forum'] = [ 
    'showitem' => 'birth_day, profilbild, username_path'
];