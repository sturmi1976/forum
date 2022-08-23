<?php
defined('TYPO3') || die(); 
//     




(static function() {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'AL.Forum',
        'Forum',
        [
            \AL\Forum\Controller\ForumController::class => 'list, show',
            \AL\Forum\Controller\ThreadController::class => 'list, show',
            \AL\Forum\Controller\UserController::class => 'list, show',
        ],
        // non-cacheable actions
        [
            \AL\Forum\Controller\ForumController::class => 'list, show', 
            \AL\Forum\Controller\ThreadController::class => 'klick, list, show',
        ]
    );
    
     
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'AL.Forum',
        'Register',
        [
            \AL\Forum\Controller\RegisterController::class => 'show, activated, success',
        ],
        // non-cacheable actions
        [
             \AL\Forum\Controller\RegisterController::class => 'show, activated, success',
        ]
    );
    
    
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'AL.Forum',
        'Login',
        [
            \AL\Forum\Controller\LoginController::class => 'login, logout',
            \AL\Forum\Controller\ForumController::class => 'list',
        ],
        // non-cacheable actions 
        [
             \AL\Forum\Controller\LoginController::class => 'login, logout',
            \AL\Forum\Controller\ForumController::class => 'list',
        ]
    );
    
    
    // New Tab
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod.wizards.newContentElement.wizardItems {
            forumTab.header = Forum/Portal
        }'
    );


    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.forumTab {
                elements {
                    forum {
                        iconIdentifier = forum-plugin-forum
                        title = LLL:EXT:forum/Resources/Private/Language/locallang_db.xlf:tx_forum_forum.name
                        description = LLL:EXT:forum/Resources/Private/Language/locallang_db.xlf:tx_forum_forum.description
                        tt_content_defValues {
                            CType = list
                            list_type = forum_forum
                        }
                    }
                    register {
                        iconIdentifier = forum-plugin-forum
                        title = Registrierung
                        description = Formular zur Registrierung der FE User.
                        tt_content_defValues {
                            CType = list
                            list_type = forum_register
                        }
                    }
                    login {
                        iconIdentifier = forum-plugin-forum
                        title = Login Form
                        description = Formular um den FE User einzuloggen.
                        tt_content_defValues {
                            CType = list
                            list_type = forum_login
                        }
                    }
                }
                show = * 
            }
       }'
    );
})();


