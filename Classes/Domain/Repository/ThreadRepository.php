<?php

declare(strict_types=1);

namespace AL\Forum\Domain\Repository;



use AL\Forum\Domain\Model\Thread;

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

/**
 * This file is part of the "Forum" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2022 Andre Lanius <a-lanius@web.de>, AL Webdesign
 */

/**
 * The repository for Forums
 */
class ThreadRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    
    public function findThreadDataByUid($uid) {      
        $query = $this->createQuery();
        $query->statement('SELECT * FROM tx_forum_domain_model_threads WHERE uid="'.$uid.'"');
        $result = $query->execute(true);
        return $result;
    }
    
    public function findAll() {      
        $query = $this->createQuery();
        $query->statement('SELECT * FROM tx_forum_domain_model_threads WHERE sys_language_uid="'.$_GET['L'].'"');
        $result = $query->execute(true);
        return $result;
    }
    
    
    public function findByUidAll($uid) {
        $query = $this->createQuery();
        $query->statement('SELECT * FROM tx_forum_domain_model_threads WHERE category="'.$uid.'" AND sys_language_uid="'.$_GET['L'].'"');
        $result = $query->execute(true);
        return $result; 
    }
    
    public function findByForum($forumUid, $page, $maxItemsPerPageThreadList) {
        $query = $this->createQuery();
        $query->statement('SELECT * FROM tx_forum_domain_model_threads WHERE forum="'.$forumUid.'" AND sys_language_uid="'.$_GET['L'].'" ORDER BY tstamp DESC LIMIT '.$page.','.$maxItemsPerPageThreadList.'');
        $result = $query->execute(true);
        return $result; 
    }
    
    
    public function findThreadCount($forumUid) {
        $query = $this->createQuery();
        $query->statement('SELECT COUNT(uid) AS counts FROM tx_forum_domain_model_threads WHERE forum="'.$forumUid.'" AND sys_language_uid="'.$_GET['L'].'"');
        $result = $query->execute(true);
        return $result; 
    }
    
    
    public function findLastThread($forumUid) {
        $query = $this->createQuery();
        $query->statement('SELECT * FROM tx_forum_domain_model_threads WHERE forum="'.$forumUid.'" AND sys_language_uid="'.$_GET['L'].'" ORDER BY uid DESC LIMIT 1');
        $result = $query->execute(true);
        return $result; 
    }
    
    
    public function findLastThreadUser($user_id) {
        $query = $this->createQuery();
        $query->statement('SELECT * FROM fe_users WHERE uid="'.$user_id.'" LIMIT 1');
        $result = $query->execute(true);
        return $result; 
    }


    public function findUserAllThreadsCount($user_id) {
        $query = $this->createQuery();
        $query->statement('SELECT COUNT(uid) AS counts FROM tx_forum_domain_model_threads WHERE user_id="'.$user_id.'"');
        $result = $query->execute(true); 
        return $result; 
    }


    public function findUserByThreadId($thread_id) {
        $query = $this->createQuery();
        $query->statement('SELECT tx_forum_domain_model_threads.uid, fe_users.username, fe_users.usergroup FROM tx_forum_domain_model_threads INNER JOIN fe_users ON tx_forum_domain_model_threads.user_id=fe_users.uid WHERE tx_forum_domain_model_threads.uid='.$thread_id.'');
        $result = $query->execute(true); 
        return $result;
    }


    public function updateKlicksByThread($thread_id) {
        // Counter Klicks
        $res = $this->findThreadDataByUid($thread_id);
        $count_klicks = intval($res[0]['klicks']+1);

        GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tx_forum_domain_model_threads')
            ->update(
                'tx_forum_domain_model_threads',
                [ 'klicks' => $count_klicks ], // set
                [ 'uid' => $thread_id ] // where 
            );
    }


    
}
