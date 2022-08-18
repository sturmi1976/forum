<?php

declare(strict_types=1);

namespace AL\Forum\Domain\Repository;



use AL\Forum\Domain\Model\User;

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Connection;


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
class UserRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    
    public function findAll() {      
        $query = $this->createQuery();
        $query->statement('SELECT * FROM tx_forum_domain_model_threads WHERE sys_language_uid="'.$_GET['L'].'"');
        $result = $query->execute(true);
        return $result;
    }
    
    
    public function findUserByUid($uid) {      
        $query = $this->createQuery();
        $query->statement('SELECT * FROM fe_users WHERE uid="'.$uid.'" LIMIT 1');
        $result = $query->execute(true);
        return $result;
    }
    
    
    public function findUserImage($uid) {      
        $query = $this->createQuery();
        $query->statement('SELECT * FROM sys_file_reference WHERE tablenames="tx_forum_domain_model_profilbilder" AND fieldname="profilbild" AND deleted="0" AND uid="'.$uid.'" LIMIT 1');
        $result = $query->execute(true);
        return $result; 
    }
    
    
    public function findUserGroup($user_group_uid) {
        $query = $this->createQuery();
        $query->statement('SELECT * FROM fe_groups WHERE uid="'.$user_group_uid.'" LIMIT 1');
        $result = $query->execute(true);
        return $result;
    }
    
    
    public function findCountThreads($user_id) {
        $query = $this->createQuery();
        $query->statement('SELECT COUNT(uid) AS counts FROM tx_forum_domain_model_threads WHERE user_id="'.$user_id.'"');
        $result = $query->execute(true);
        return $result;
    }
    
    
    public function findUserByUsername($username) {
        $query = $this->createQuery();
        $query->statement('SELECT COUNT(uid) AS counts FROM fe_users WHERE username="'.$username.'" LIMIT 1');
        $result = $query->execute(true);
        return $result;
    }


    public function findUserGroupById($uid) {
        $query = $this->createQuery();
        $query->statement('SELECT * FROM fe_groups WHERE uid="'.$uid.'" LIMIT 1');
        $result = $query->execute(true);
        return $result;
    }
    
    
    public function insertNewUser($insert) {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_users');
        $affectedRows = $queryBuilder
            ->insert('fe_users')
            ->values([
                'username' => $insert['username'],
                'password' => $insert['password'],
                'usergroup' => $insert['usergroup'],
                'email' => $insert['email'],
                'crdate' => $insert['crdate'],
                'tstamp' => $insert['tstamp'],
                'pid' => $insert['pid'],
                'cruser_id' => $insert['cruser_id'],
                'disable' => 1,
                'username_path' => $insert['username_path'],
                'user_hash_activated' => $insert['user_hash_activated']
        ])
        ->executeStatement();
        
        $Uid = $queryBuilder->getConnection()->lastInsertId();
        
        return $Uid;
    }
    
    
    
    
    
    public function userActivated($user_id, $hash) {
        $query = $this->createQuery();
        $query->statement('SELECT * FROM fe_users WHERE uid="'.$user_id.'" AND user_hash_activated="'.$hash.'" LIMIT 1');
        $result = $query->execute(true);
        return $result;
    }
    
    
    
    public function userActivated2($user_id) {
        /*
        $query = $this->createQuery();
        $query->statement('UPDATE fe_users SET disable="0" WHERE uid="'.$user_id.'"');
        $result = $query->execute(true);
        return $result;
        */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('fe_users');
        $queryBuilder
            ->update('fe_users', 'user')
            ->where(
                $queryBuilder->expr()->eq('user.uid', $queryBuilder->createNamedParameter($user_id))
            )
            ->set('user.disable', '0')   
            ->executeStatement();
        
    }
    
    
    
    public function insertIntoProfilbilder($insert_array) {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_forum_domain_model_profilbilder');
        $affectedRows = $queryBuilder
            ->insert('tx_forum_domain_model_profilbilder')
            ->values([
                'user_id' => $insert_array['user_id'],
                'pid' => $insert_array['pid'],
                'profilbild' => $insert_array['profilbild'],
                'crdate' => $insert_array['crdate'],
                'tstamp' => $insert_array['tstamp'],
            ])
        ->executeStatement();
        
        $Uid = $queryBuilder->getConnection()->lastInsertId();
        
        return $Uid;
    }
    
    
    
    
    
    public function insertIntoProfilbilder2($insert_array2) {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_file_reference');
        $affectedRows = $queryBuilder
            ->insert('sys_file_reference')
            ->values([
                'pid' => $insert_array2['pid'],
                'tstamp' => $insert_array2['tstamp'],
                'crdate' => $insert_array2['crdate'],
                'cruser_id' => $insert_array2['cruser_id'],
                'uid_local' => $insert_array2['uid_local'],
                'uid_foreign' => $insert_array2['uid_foreign'],
                'tablenames' => $insert_array2['tablenames'],
                'fieldname' => $insert_array2['fieldname'],
                'sorting_foreign' => $insert_array2['sorting_foreign'],
                'table_local' => $insert_array2['table_local'],
            ])
        ->executeStatement(); 
        
        $Uid = $queryBuilder->getConnection()->lastInsertId();
        
        return $Uid;
    }
    
    
    /* Prüfen ob Username und Passwort übereinstimmen, um User einzuloggen */
    public function checkUserDataForLogin($username) {
        $query = $this->createQuery();
        $query->statement('SELECT * FROM fe_users WHERE username="'.$username.'" LIMIT 1');
        $result = $query->execute(true);
        return $result;
    }
    
    
    
    
}
