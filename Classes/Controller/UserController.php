<?php

declare(strict_types=1);

namespace AL\Forum\Controller;


use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use AL\Forum\Seo\ForumTitleProvider;


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
class UserController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
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
     * action list
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function listAction(): \Psr\Http\Message\ResponseInterface
    {

        return $this->htmlResponse(); 
    }

    
    
    /**
     * action show
     *
     * @param \AL\Forum\Domain\Model\Forum $forum
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function showAction(): \Psr\Http\Message\ResponseInterface
    {
        
        /* All get params to the forum showAction */
        $get = $this->request->getArguments();
        
        /* Alle Daten des Users holen */
        $user_data = $this->userRepository->findUserByUid($get['user_id']);
        
        /* Title-Tag */
        $titleProvider = GeneralUtility::makeInstance(ForumTitleProvider::class);
        $titleProvider->setTitle('Userprofil: '.ucfirst($user_data[0]['username']).' '.$this->settings['titleTagPrefix']); 
        
        
        /* Profilbild des Users holen */
        $image_uid = $this->userRepository->findUserImage($get['user_id']);
        if($image_uid) {
        $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
        $file = $resourceFactory->getFileObject($image_uid[0]['uid_local']);
        }
        
        /* Usergroup / Rank */
        $user_group_data = $this->userRepository->findUserGroup($user_data[0]['usergroup']);
        $rank = $user_group_data[0]['title'];
        
        /* Count Threads by user */
        $anzahl_beitraege = $this->userRepository->findCountThreads($user_data[0]['uid']);
        
        $this->view->assign('user',$user_data);
        $this->view->assign('usergroup',$rank);
        $this->view->assign('bild',$file);
        $this->view->assign('years',$this->getAge(date('d',intval($user_data[0]['birth_day'])),date('m',intval($user_data[0]['birth_day'])),date('Y',intval($user_data[0]['birth_day']))));
        $this->view->assign('threads_count',$anzahl_beitraege[0]);
        
        return $this->htmlResponse(); 
    }
    
    
    
    
    
    

public function getAge($tag, $monat, $jahr) {
    
   $tag = $tag;
    $monat = $monat;
    $jahr = $jahr;
    $birth = mktime(0,0,0,intval($monat),intval($tag),intval($jahr));
    $now = time();
    $timealter = $now - $birth;
    $yearnow = date("L", $now);
    if($yearnow == 1)
    {
        $alter = intval($timealter /(3600*24*366));
    }
    else
    {
        $alter = intval($timealter /(3600*24*365));
    }
    
    return $alter;
}


 
    
    
}
