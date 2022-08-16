<?php
defined('TYPO3') || die();

(static function() {
    
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'Forum',
        'web',
        'backendforum',
        '',
        [
            \AL\Forum\Controller\ForumController::class => 'list, show',
        ],
        [
            'access' => 'user,group',
            'icon'   => 'EXT:forum/Resources/Public/Icons/user_mod_backendforum.svg',
            'labels' => 'LLL:EXT:forum/Resources/Private/Language/locallang_backendforum.xlf',
        ]
    );
    
    
   
    

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_forum_domain_model_category', 'EXT:forum/Resources/Private/Language/locallang_csh_tx_forum_domain_model_category.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_forum_domain_model_category');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_forum_domain_model_forum', 'EXT:forum/Resources/Private/Language/locallang_csh_tx_forum_domain_model_forum.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_forum_domain_model_forum');
})();
