<?php

declare(strict_types=1);

namespace AL\Forum\Controller;


// use AL\Forum\Seo\ForumTitleProvider;
use TYPO3\CMS\Core\Resource\Exception\ExistingTargetFileNameException;
use TYPO3\CMS\Core\Resource\File as FalFile;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Error\Error;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Saltedpasswords\Utility\SaltedPasswordsUtility;
use TYPO3\CMS\Saltedpasswords\Salt\SaltFactory;
use TYPO3\CMS\Core\Crypto\PasswordHashing\PasswordHashFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Extbase\Property\Exception\TypeConverterException;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface;
use TYPO3\CMS\Extbase\Property\TypeConverter\AbstractTypeConverter;
use TYPO3\CMS\Core\Utility\File\BasicFileUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Core\Resource\StorageRepository;
use TYPO3\CMS\Core\Database\ConnectionPool;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

use AL\Forum\Functions\TestFunctions;

use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\Mailer;


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
class RegisterController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    
    /**
     * @var \TYPO3\CMS\Core\Resource\ResourceStorage
    */
    protected $defaultStorage;
    
    
     /**
     * @param \AL\Forum\Domain\Repository\UserRepository $userRepository
     */
    public function injectUserRepository(\AL\Forum\Domain\Repository\UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    

    

    /**
     * action show
     *
     * @param \AL\Forum\Domain\Model\Thread $thread
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function showAction()
    {
        

        // User einloggen
/*
$GLOBALS['TSFE']->fe_user->checkPid = 0;
$GLOBALS['TSFE']->fe_user->dontSetCookie = FALSE;
$GLOBALS['TSFE']->fe_user->user = $GLOBALS['TSFE']->fe_user->fetchUserSession();
$GLOBALS['TSFE']->loginUser = 1;
$GLOBALS['TSFE']->fe_user->start();
$GLOBALS['TSFE']->fe_user->createUserSession(['uid' => 44]);
$GLOBALS['TSFE']->initUserGroups();
$GLOBALS['TSFE']->fe_user->loginSessionStarted = TRUE;


// User ausloggen
$GLOBALS['TSFE']->fe_user->removeSessionData();
*/

        
        /* All get params to the forum showAction */
        $get = $this->request->getArguments();


        
        
      // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->settings['userLocation']);
         
        if($get['submit']) {
            
            
        //$testing = GeneralUtility::makeInstance(TestFunctions::class);
        //$this->view->assign('testing', $testing->test());
            
            
            
            /*
             * Prüfen ob alle Pflichfelder ausgefüllt wurden
             */
            #$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'fe_users', 'username="'.$get['username'].'"', '', '', '1');
            #$number = $GLOBALS['TYPO3_DB']->sql_num_rows($res);  
            
            /* Prüfen ob der User schon existiert - return 0 oder 1 */
            $findUser = $this->userRepository->findUserByUsername(htmlspecialchars($get['username']));
            $user_exist = $findUser[0]['counts'];
            
            
            
            $error_count = 0;
            $error = array();
            if(strlen($get['username']) <= 3) {
                $error_count++;
                $error[] = "Der Username darf nicht weniger als 4 Zeichen haben.";
            }
            
            if($user_exist > 0) {
                $error_count++;
                $error[] = "Der Username existiert bereits schon.";
            }
            
            if(strlen($get['password']) <= 6 || strlen($get['password2']) <= 6) {
                $error_count++;
                $error[] = "Das eingegebene Passwort ist zu kurz - mind. 7 Zeichen.";
            }
            
            if($get['email'] == "") {
                $error_count++;
                $error[] = "Sie müssen eine E-Mail Adresse eingeben.";
            }
            
            if(GeneralUtility::validEmail($get['email']) == false) {
                $error_count++;
                $error[] = "Bitte geben Sie eine gültige E-Mail Adresse ein.";
            }
            
            if($get['password'] !== $get['password2']) {
                $error_count++;
                $error[] = "Die Passwörter stimmen nicht überein.";
            }
            
            if($error_count > 0) {
                $this->view->assign('error', $error); 
            }

            
            
            if($error_count == 0) {
            $username = htmlspecialchars($get['username']);
            $password = $this->encryptPassword($get['password']);
            
            /* User Pathsegment */
            $vokale = array("-", "/", "$", "§", "%", " ", '#', '+', '&', '(', ')', '{', '}', '!', '"');
            $username_path = str_replace($vokale, "-", strtolower($username));
            $username_path = str_replace("ä", "ae", $username_path);
            $username_path = str_replace("ü", "ue", $username_path);
            $username_path = str_replace("ö", "oe", $username_path);
            $username_path = str_replace("ß", "ss", $username_path);
            
            // User hash
            $user_hash = md5($username . $get['password']);
            
            $insert_into = array(
                'username' => $username,
                'password' => $password,
                'email' => $get['email'],
                'usergroup' => 1,
                'crdate' => time(),
                'tstamp' => time(),
                'pid' => $this->settings['userLocation'],
                'cruser_id' => 1,
                'disable' => 1,
                'username_path' => $username_path,
                'user_hash_activated' => $user_hash
            );
          
            
            
            /* Neuen User in die Datenbank eintragen und User ID als Rückgabewert */
            $user_id = $this->userRepository->insertNewUser($insert_into);  
      
            
            
            // Image upload
            $file = $get['file'];
            $file_explode = explode("/", $file['type']);
            
            
            /*
             * Funktionsaufrufe zum Bildupload (Profilbild)
             */
            if($file['name']) {
                $fileupload = $this->uploadFile($get['file'], $get['username'], $file_explode[1], $user_id);
            }
            
            
            
            /* Link Generierung */
            $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
            $uriBuilder = $objectManager->get(\TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder::class);
            $uri = $uriBuilder
              ->reset()
              ->setTargetPageUid(intval($this->settings['registerPageUid']))
              ->setArguments(['tx_forum_register[user_id]'=>$user_id, 'tx_forum_register[hash]'=>$user_hash, 'tx_forum_register[controller]'=>'Register', 'tx_forum_register[action]'=>'activated'])
              ->build();

            
            /* E-Mail am User senden nach erfolgreicher Registrierung */
            $email = GeneralUtility::makeInstance(FluidEmail::class);
            $email
                ->to($get['email'])
                ->from(new Address('a-lanius@web.de', 'Andre Lanius'))
                ->subject($this->settings['emailRegisterSubject'])
                ->format('both') // send HTML and plaintext mail
                ->setTemplate('Register')
                ->assign('username', htmlspecialchars($get['username']))
                ->assign('hash', $user_hash)
                ->assign('user_id', $user_id)
                ->assign('domain', $GLOBALS['_SERVER']['DDEV_PRIMARY_URL'])
                ->assign('link', $uri);
            GeneralUtility::makeInstance(Mailer::class)->send($email);
            
            
            
            $this->redirect('success', 'Register'); 

            
            
            }
            
           
        }
         
        return $this->htmlResponse();
    }
    
    
    
    
    
    /*
     * Funktion die den User freischaltet, nachdem er den Bestätigungslink aus der Mail angeklickt hat.
     */
    public function activatedAction() 
    {
        // All GET Params
        $get = $this->request->getArguments();
        
        $user = $this->userRepository->userActivated($get['user_id'], $get['hash']);
        
        if(count($user) > 0) {
            $user_activated = $this->userRepository->userActivated2($get['user_id']);
            
            // Forum Plugin Uid
            $forumPluginUid = intval($this->settings['forumPluginUid']);
            $objectManager2 = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
            $uriBuilder2 = $objectManager2->get(\TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder::class);
            $uri2 = $uriBuilder2
              ->reset()
              ->setTargetPageUid($forumPluginUid)
              ->build();
            
            $this->view->assign('domain', $GLOBALS['_SERVER']['DDEV_PRIMARY_URL']);
            $this->view->assign('link', $uri2);
            
        }
        
       
        
        return $this->htmlResponse();
        
    }
    
    
    
    
    
    
    public function successAction() {
        return $this->htmlResponse();
    }
    
    
    
    
    /* Funktion für die Passwort-Verschlüsselung */
    public function encryptPassword($passwort){
        $password = $passwort;
        $hashInstance = GeneralUtility::makeInstance(PasswordHashFactory::class)->getDefaultHashInstance('FE');
        $hashedPassword = $hashInstance->getHashedPassword($password);
        return $hashedPassword;
    } 
    
    
    
    

 public function uploadFile($file, $username, $type, $user_id)
 {
     $storageRepository = GeneralUtility::makeInstance(StorageRepository::class);
    $storage = $storageRepository->getDefaultStorage();
    $newFile = $storage->addFile(
      $file['tmp_name'],
      $storage->getRootLevelFolder(),
      $file['name']
    );

    $newFileReference = new \AL\Forum\Domain\Model\FileReference();

    $newFileReference->setOriginalResource($newFile);
    
    //$insert_into_profilbilder = $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_forum_domain_model_profilbilder', array('user_id' => $user_id, 'pid' => 10, 'profilbild' => 1, 'crdate' => time(), 'tstamp' => time()));
    //$last_id = $GLOBALS['TYPO3_DB']->sql_insert_id(); 
    
    $insert_array1 = array(
        'user_id' => $user_id,
        'pid' => $this->settings['userLocation'],
        'profilbild' => 1,
        'crdate' => time(),
        'tstamp' => time()
    );
    $insert_into_profilbilder = $this->userRepository->insertIntoProfilbilder($insert_array1);
    
    
    $in_array = array(
        'pid' => $this->settings['userLocation'],
        'tstamp' => time(),
        'crdate' => time(),
        'cruser_id' => 1,
        'uid_local' => $newFile->getProperties()['file'],
        'uid_foreign' => $insert_into_profilbilder,
        'tablenames' => 'tx_forum_domain_model_profilbilder',
        'fieldname' => 'profilbild',
        'sorting_foreign' => 1,
        'table_local' => 'sys_file'
    );
    
    
    $this->userRepository->insertIntoProfilbilder2($in_array);

    
    return $newFile; 
    
 }
 
 
 
 


    
    
    
}
