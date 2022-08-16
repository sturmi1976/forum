<?php

declare(strict_types=1);

namespace AL\Forum\Domain\Repository;



use AL\Forum\Domain\Model\Topic;

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

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
class TopicRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    
    public function findAll() {      
        $query = $this->createQuery();
        $query->statement('SELECT * FROM tx_forum_domain_model_threads WHERE sys_language_uid="'.$_GET['L'].'"');
        $result = $query->execute(true);
        return $result;
    }
    
    
    
    public function findTopicCount($forumUid) {
        $query = $this->createQuery();
        $query->statement('SELECT COUNT(uid) AS counts FROM tx_forum_domain_model_topic WHERE forum="'.$forumUid.'" AND sys_language_uid="'.$_GET['L'].'"');
        $result = $query->execute(true);
        return $result; 
    }


    public function findUserAllTopicsCount($user_id) {
        $query = $this->createQuery();
        $query->statement('SELECT COUNT(uid) AS counts FROM tx_forum_domain_model_topic WHERE user_id="'.$user_id.'"');
        $result = $query->execute(true); 
        return $result; 
    }
    
}
