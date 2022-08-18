<?php

declare(strict_types=1);

namespace AL\Forum\Controller;


use AL\Forum\Seo\ForumTitleProvider;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;


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
class ThreadController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    
    
    
    /**
     * categoryRepository
     *
     * @var \AL\Forum\Domain\Repository\CategoryRepository
     */
    protected $categoryRepository = null;

    /**
     * forumRepository
     *
     * @var \AL\Forum\Domain\Repository\ForumRepository
     */
    protected $forumRepository = null;
    
    /**
     * threadRepository
     *
     * @var \AL\Forum\Domain\Repository\ThreadRepository
     */
    protected $threadRepository = null;



    /**
     * userRepository
     *
     * @var \AL\Forum\Domain\Repository\UserRepository
     */
    protected $userRepository = null;



    /**
     * @param \AL\Forum\Domain\Repository\UserRepository $userRepository
     */
    public function injectUserRepository(\AL\Forum\Domain\Repository\UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    
    /**
     * @param \AL\Forum\Domain\Repository\ForumRepository $categoryRepository
     */
    public function injectCategoryRepository(\AL\Forum\Domain\Repository\CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    

    /**
     * @param \AL\Forum\Domain\Repository\ForumRepository $forumRepository
     */
    public function injectForumRepository(\AL\Forum\Domain\Repository\ForumRepository $forumRepository)
    {
        $this->forumRepository = $forumRepository;
    }
    
    
    /**
     * @param \AL\Forum\Domain\Repository\ThreadRepository $threadRepository
     */
    public function injectThreadRepository(\AL\Forum\Domain\Repository\ThreadRepository $threadRepository)
    {
        $this->threadRepository = $threadRepository;
    }

    /**
     * action list
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function listAction(): \Psr\Http\Message\ResponseInterface
    { 
      
        /* All get params to the forum showAction */
        $get = $this->request->getArguments();
        
        if($get['seite']) {
            $page = $get['seite']*$this->settings['pager']['maxItemsPerPageThreadList']-$this->settings['pager']['maxItemsPerPageThreadList'];
        }else{
            $page = 0;
        }
        
        
        if(!$get['seite'] || $get['seite']==1) { 
            $page=0;
        }
        
        
        /* Gesamte Anzahl an Threads im aktuellen Forum */
        $counts = $this->threadRepository->findThreadCount($get['forum']);  
        $anzahl_datensaetze = $counts[0]['counts'];
        
        /* Seiten insgesamt für Blätternavigation */
        $SitesComplete = ceil($anzahl_datensaetze / $this->settings['pager']['maxItemsPerPageThreadList']);
        
        
        $threads = $this->threadRepository->findByForum($get['forum'], $page, $this->settings['pager']['maxItemsPerPageThreadList']);  
        
        /* Category-Title and forum-title for title-tag */
        $forum_data = $this->forumRepository->findByUidForumData($get['forum']);

        $titleProvider = GeneralUtility::makeInstance(ForumTitleProvider::class);
        $titleProvider->setTitle($forum_data[0]['forum'].' '.$this->settings['titleTagPrefix']); 


        $this->view->assign('forum_data', $forum_data); 
        $this->view->assign('threads', $threads); 

        

        $i=0;
        foreach($threads as $post) {

            /* User Data */
            $user = $this->threadRepository->findUserByThreadId($post['uid']);

            /* User Group */
           // $userFullData = $this->userRepository->findUserByUid($user[0]['usergroup']);
            $group = $this->userRepository->findUserGroupById($user[0]['usergroup']);
            
            /* Profilbild des Users holen */
            $image_uid = $this->userRepository->findUserImage($post['user_id']);
            if($image_uid) {
            $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
            $file = $resourceFactory->getFileObject($image_uid[0]['uid_local']);
            }

            $article[$i] = $post;
            $article[$i]['userData'] = $user;
            $article[$i]['userGroup'] = $group;
            $article[$i]['fileData'] = $file;
          $i++;
        }

        // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($article);

        $this->view->assign('article', $article);  


 
        /* Paging nur anzeigen, wenn mehr als 1 Seite vorhanden ist ... */
        if($SitesComplete > 1) {
            $this->view->assign('paging', $this->blaetterfunktion($get['seite'], $SitesComplete));
        }
        
        $this->view->assign('counter', $anzahl_datensaetze);
        
        return $this->htmlResponse();  
    }

    /**
     * action show
     *
     * @param \AL\Forum\Domain\Model\Thread $thread
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function showAction(\AL\Forum\Domain\Model\Threads $thread): \Psr\Http\Message\ResponseInterface
    {
        
        /* All get params to the forum showAction */
        $get = $this->request->getArguments();
        
        $thread_data = $this->threadRepository->findThreadDataByUid($get['thread']);
        
        $titleProvider = GeneralUtility::makeInstance(ForumTitleProvider::class);
        $titleProvider->setTitle(htmlspecialchars($thread_data[0]['title']).' '.$this->settings['titleTagPrefix']); 

        $title = htmlspecialchars($thread_data[0]['title']);
        $title = strip_tags($title);
        $desc = htmlspecialchars($thread_data[0]['text']);
        $desc = strip_tags($desc);
        $description = \TYPO3\CMS\Core\Utility\GeneralUtility::fixed_lgd_cs($desc,'160');
        $dateTime = date("Y-m-d", $thread_data[0]['crdate']);
        $time = date("H:i:s", $thread_data[0]['crdate']);

        // User Data
        $user = $this->userRepository->findUserByUid($thread_data[0]['user_id']);

        $GLOBALS['TSFE']->additionalHeaderData['tx_forum_forum_thread'] = '
        <script type="application/ld+json">
 
        </script>
        ';

        
        
        $this->view->assign('thread_data', $thread_data); 
         
        return $this->htmlResponse();
    }
    
    
    
    
    
    
    
    
    
    
    public function blaetterfunktion($seite,$maxseite,$url="",$anzahl=4,$get_name="seite")
   {
        
    /* All get params to the forum showAction */
    $get = $this->request->getArguments();
        
    $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
    $uriBuilder = $objectManager->get(\TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder::class);

        $anhang = "?";
   if(substr($url,-1,1) == "&") {
      $url = substr_replace($url,"",-1,1);
      }
   else if(substr($url,-1,1) == "?") {
      $anhang = "?";
      $url = substr_replace($url,"",-1,1);
      }

   if($anzahl%2 != 0) $anzahl++; //Wenn $anzahl ungeraden, dann $anzahl++

   $a = $seite-($anzahl/2);
   $b = 0;
   $blaetter = array();
   while($b <= $anzahl)
      {
      if($a > 0 AND $a <= $maxseite)
         {
         $blaetter[] = $a;
         $b++;
         }
      else if($a > $maxseite AND ($a-$anzahl-2)>=0)
         {
         $blaetter = array();
         $a -= ($anzahl+2);
         $b = 0;
         }
      else if($a > $maxseite AND ($a-$anzahl-2)<0)
         {
         break;
         }

      $a++;
      }
   $return = "";
   if(!in_array(1,$blaetter) AND count($blaetter) > 1)
      {
       

$uri = $uriBuilder
  ->reset()
  ->setTargetPageUid($GLOBALS['TSFE']->id)
  ->setArguments(['tx_forum_forum[category]' => $get['category'], 'tx_forum_forum[forum]' => $get['forum'], 'tx_forum_forum[seite]'=>1])
  ->build();

      if(!in_array(2,$blaetter)) $return .= "&nbsp;<a href=\"".$uri."\">1</a>&nbsp;...";
      else $return .= "&nbsp;<a href=\"".$uri."\">1</a>&nbsp;";
      }
 
   foreach($blaetter AS $blatt)
      {
      
       $uri = $uriBuilder
  ->reset()
  ->setTargetPageUid($GLOBALS['TSFE']->id)
  ->setArguments(['tx_forum_forum[category]' => $get['category'], 'tx_forum_forum[forum]' => $get['forum'], 'tx_forum_forum[seite]'=>$blatt])
  ->build();
       
      if($blatt == $seite) $return .= "&nbsp;<b>$blatt</b>&nbsp;";
      else $return .= "&nbsp;<a href=\"".$uri."\">$blatt</a>&nbsp;";
      }

   if(!in_array($maxseite,$blaetter) AND count($blaetter) > 1)
      {
       
       $uri = $uriBuilder
  ->reset()
  ->setTargetPageUid($GLOBALS['TSFE']->id)
  ->setArguments(['tx_forum_forum[category]' => $get['category'], 'tx_forum_forum[forum]' => $get['forum'], 'tx_forum_forum[seite]'=>$maxseite])
  ->build();
       
      if(!in_array(($maxseite-1),$blaetter)) $return .= "...&nbsp;<a href=\"".$uri."\">letzte</a>&nbsp;";
      else $return .= "&nbsp;<a href=\"".$uri."\">$maxseite</a>&nbsp;";
      }

   if(empty($return))
      return  "&nbsp;<b>1</b>&nbsp;";
   else
      return $return;
   }  
    
    
    
    
    
    
    
    
}
