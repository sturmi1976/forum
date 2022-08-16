<?php

declare(strict_types=1);

namespace AL\Forum\Domain\Repository;


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
class RegisterRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    
    public function insertNewUser($data, $pw) {      
        $query = $this->createQuery();
        $query->statement('INSERT INTO fe_users (username, password, username_path) VALUES('.$data['username'].','.$pw.', '.$data['username_path'].')');
        $result = $query->execute(true);
        return $result; 
    }
    

}
