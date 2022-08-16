<?php

declare(strict_types=1);

namespace AL\Forum\Domain\Repository;



use AL\Forum\Domain\Model\Forum;

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
class ForumRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    
    public function findAll() {      
        $query = $this->createQuery();
        $query->statement('SELECT * FROM tx_forum_domain_model_forum WHERE sys_language_uid="'.$_GET['L'].'"');
        $result = $query->execute(true);
        return $result;
    }
    
    
    public function findByUidAll($uid) {
        $query = $this->createQuery();
        $query->statement('SELECT * FROM tx_forum_domain_model_forum WHERE category="'.$uid.'" AND sys_language_uid="'.$lang.'"');
        $result = $query->execute(true);
        return $result; 
    }
    
    public function findByUidForumData($uid) {
        $query = $this->createQuery();
        $query->statement('SELECT * FROM tx_forum_domain_model_forum WHERE uid="'.$uid.'" AND sys_language_uid="'.$lang.'" LIMIT 1');
        $result = $query->execute(true);
        return $result; 
    }
    
}
