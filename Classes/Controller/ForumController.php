<?php

declare(strict_types=1);

namespace AL\Forum\Controller;


use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;


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
class ForumController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
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
     * topicRepository
     *
     * @var \AL\Forum\Domain\Repository\TopicRepository
     */
    protected $topicRepository = null;
    
    
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
    public function listAction(): \Psr\Http\Message\ResponseInterface
    {
        $array = array();
        $category = $this->categoryRepository->findAll();
        $forum = $this->forumRepository->findAll();
        
        $i = 0;
        foreach ($category as &$cat) {
            $array[$i]['category'] = $cat['category'];
            $array[$i]['cat_uid'] = $cat['uid'];
            $array[$i]['forum'] = $this->forumRepository->findByUidAll($cat['uid']);
            /* Foren durchlaufen */
            $u=0;
            foreach($array[$i]['forum'] as &$forum) {
                $array[$i]['forum'][$u]['thread_counter'] = $this->threadRepository->findThreadCount($forum['uid']); 
                $array[$i]['forum'][$u]['topic_counter'] = $this->topicRepository->findTopicCount($forum['uid']);
                $array[$i]['forum'][$u]['last_thread'] = $this->threadRepository->findLastThread($forum['uid']);
                $array[$i]['forum'][$u]['last_thread_user'] = $this->threadRepository->findLastThreadUser($array[$i]['forum'][$u]['last_thread'][0]['user_id']);
                $u++;
            }

            $i++; 
        }
        
        $this->view->assign('foren', $array); 
         
        
        return $this->htmlResponse(); 
    }

    
    
    /**
     * action show
     *
     * @param \AL\Forum\Domain\Model\Forum $forum
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function showAction(\AL\Forum\Domain\Model\Forum $forum): \Psr\Http\Message\ResponseInterface
    {
        
        // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->request->getArguments());
        
        /* All get params to the forum showAction */
        $get = $this->request->getArguments();

        $category = $this->categoryRepository->findByUid($get['category']); 
        $forum = $this->forumRepository->findByUid($get['forum']);
        
        
        $this->view->assign('categoryName', $category);
        $this->view->assign('forumName', $forum);
        
        return $this->htmlResponse();
    }
}
