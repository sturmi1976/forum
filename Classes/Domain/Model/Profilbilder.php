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
class Profilbilder extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * profilbild
     * 
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    protected $profilbild = NULL;
 
    function getProfilbild() {
        return $this->profilbild;
    }
 
    function setProfilbild(\TYPO3\CMS\Extbase\Domain\Model\FileReference $profilbild) {
        $this->profilbild = $profilbild; 
    }
    
    

   
            
            
}
