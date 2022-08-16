<?php

declare(strict_types=1);

namespace AL\Forum\Domain\Model;


/**
 * This file is part of the "Forum" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2022 Andre Lanius <a-lanius@web.de>, AL Webdesign
 */

/**
 * Forum
 */
class User extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * username
     *
     * @var \AL\Forum\Domain\Model\User
     */
    protected $username = '';

    /**
     * company
     *
     * @var \AL\Forum\Domain\Model\User
     */
    protected $company = null; 
    
    
    
    

    /**
     * Returns the username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Sets the username
     *
     * @param string $username
     * @return void
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

   
            
            
}
