<?php

declare(strict_types=1);

namespace AL\Forum\Controller;


use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use TYPO3\CMS\Extbase\Reflection\ClassReflection;
use TYPO3\CMS\Extbase\Reflection\ReflectionService;


use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
#use TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication;
use Vendor\Ext\Exception\NotAuthorizedException;
use TYPO3\CMS\Core\Authentication\AuthenticationService;

use TYPO3\CMS\Core\Session\UserSessionManager;

use AL\Forum\Controller\MyAuthenticatorService;  




/**
 * This file is part of the "Forum" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2022 Andre Lanius <a-lanius@web.de>, AL Webdesign
 */

/**
 * ForumController
 */
class LoginController extends ActionController
{
    
    /**
     * userRepository
     *
     * @var \AL\Forum\Domain\Repository\UserRepository
     */
    protected $userRepository = null;
    
    
    /**
     * @param \AL\Forum\Domain\Repository\ForumRepository $categoryRepository
     */
    public function injectUserRepository(\AL\Forum\Domain\Repository\UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    


    /**
     * threadRepository
     *
     * @var \AL\Forum\Domain\Repository\ThreadRepository
     */
    protected $threadRepository = null;
    
    
    /**
     * @param \AL\Forum\Domain\Repository\ThreadRepository $threadRepository
     */
    public function injectThreadRepository(\AL\Forum\Domain\Repository\ThreadRepository $threadRepository)
    {
        $this->threadRepository = $threadRepository;
    }
    
    


    /**
     * topicRepository
     *
     * @var \AL\Forum\Domain\Repository\TopicRepository
     */
    protected $topicRepository = null;
    
    
    /**
     * @param \AL\Forum\Domain\Repository\TopicRepository $topicRepository
     */
    public function injectTopicRepository(\AL\Forum\Domain\Repository\TopicRepository $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }
    
 
    /**
     * action list
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function loginAction(): \Psr\Http\Message\ResponseInterface
    {
        /*
            $_POST['logintype'] = 'login';
            $_POST['user'] = "sturmi";
            $_POST['pass'] = $this->encryptPassword('sssahh1488');
            $authService = GeneralUtility::makeInstance(FrontendUserAuthentication::class);
            $authService->start();
        */
            

         //   \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($GLOBALS['TSFE']->fe_user);


        /* All get params to the forum showAction */
        $get = $this->request->getArguments();
        
        // Wenn das Login-Formular abgesendet wurde ...
        if($get['submit']) {

            // User Eingaben in Variablen speichern
            $username = htmlspecialchars($get['username']);
            $password = $get['password'];
            
            $user_result = $this->userRepository->checkUserDataForLogin($username);

  
            if(count($user_result) > 0) {
            $password_from_database = $user_result[0]['password'];
            
            /* Prüfen ob Passwörter übereinstimmen */
            $mode = 'FE';
            $success = GeneralUtility::makeInstance(PasswordHashFactory::class)
            ->get($password_from_database, $mode) 
            ->checkPassword($password, $password_from_database);
            
            // Wenn Eingaben erfolgreich, dann User einloggen ...
            if($success == TRUE) {
  
                $GLOBALS['TSFE']->fe_user->checkPid = 0;
                $GLOBALS['TSFE']->fe_user->dontSetCookie = FALSE;
                $GLOBALS['TSFE']->fe_user->user = $GLOBALS['TSFE']->fe_user->fetchUserSession();
                $GLOBALS['TSFE']->loginUser = 1;
                $GLOBALS['TSFE']->fe_user->start();
                $GLOBALS['TSFE']->fe_user->createUserSession(['uid' => $user_result[0]['uid']]);
                $GLOBALS['TSFE']->initUserGroups();
                $GLOBALS['TSFE']->fe_user->loginSessionStarted = TRUE;
                $GLOBALS['TSFE']->fe_user->storeSessionData();

                // Redirect zum Forum
                $uriBuilder2 = $this->uriBuilder; 
                $uri2 = $uriBuilder2
                ->setTargetPageUid(7)
                ->build(); 
                $this->redirectToUri($uri2); 

            }else{
                $this->view->assign('error', '1');
            
            }
            
            }
            
        }
 

        // Count Threads + Topics
        $threads = $this->threadRepository->findUserAllThreadsCount($GLOBALS['TSFE']->fe_user->user['uid']);
        $topic = $this->topicRepository->findUserAllTopicsCount($GLOBALS['TSFE']->fe_user->user['uid']);
        $all_counts = $threads[0]['counts']+$topic[0]['counts'];
        
        $this->view->assign('username', $GLOBALS['TSFE']->fe_user->user['username']);
        $this->view->assign('user_id', $GLOBALS['TSFE']->fe_user->user['uid']);
        $this->view->assign('count_threads', $all_counts);

        if($GLOBALS['TSFE']->fe_user->user['uid']) {
            $this->view->assign('userLogin', 1);
        }else{
            $this->view->assign('userLogin', 0);
        }

        return $this->htmlResponse(); 
    }
    
    
    
    
    public function logoutAction() {
        
        // User ausloggen
        $GLOBALS['TSFE']->fe_user->removeSessionData();
        $GLOBALS['TSFE']->fe_user->setAndSaveSessionData('user', null);
        unset($GLOBALS['TSFE']->fe_user);
        
        // Redirect zum Forum
        $uriBuilder = $this->uriBuilder; 
        $uri = $uriBuilder
          ->setTargetPageUid(intval($this->settings['forumPluginUid']))
          ->build(); 
        $this->redirectToUri($uri); 
    }
    
    
    
    

    public function encryptPassword($passwort){
        $password = $passwort;
        $hashInstance = GeneralUtility::makeInstance(PasswordHashFactory::class)->getDefaultHashInstance('FE');
        $hashedPassword = $hashInstance->getHashedPassword($password);
        return $hashedPassword;
    }

    
    
    
 /*  
public static function loginUser($user1,$user2)
{
    $_POST['logintype'] = 'login';
    $_POST['user'] = $user1;
    $_POST['pass'] = $user2;
    $authService = GeneralUtility::makeInstance(FrontendUserAuthentication::class);
    $authService->start();
}
 */

    
    
}
