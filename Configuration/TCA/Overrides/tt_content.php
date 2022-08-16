<?php
defined('TYPO3') || die();

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Forum',
    'Forum',
    'Forum'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'AL.Forum',
    'Register',
    'Forum: Register'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'AL.Forum',
    'Login',
    'Forum: Login Form'
);