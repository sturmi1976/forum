<?php
declare(strict_types=1);

namespace AL\Forum\Domain\Model; 




class FileReference extends \TYPO3\CMS\Extbase\Domain\Model\FileReference {

/**
 * Uid of the referenced sys_file. Needed for extbase to serialize the
 * reference correctly.
 *
 * @var int
 */
protected $uidLocal;

/**
 * @var string 
 */
protected $tablenames = 'tx_forum_domain_model_profilbilder';

/**
 * @param \TYPO3\CMS\Core\Resource\ResourceInterface $originalResource
 */
public function setOriginalResource(\TYPO3\CMS\Core\Resource\ResourceInterface $originalResource) {
    $this->originalResource = $originalResource;
    $this->uidLocal         = (int)$originalResource->getUid();
}

/**
 * @return \TYPO3\CMS\Core\Resource\FileReference
 */
public function getOriginalResource() {
    if($this->originalResource === NULL) {
        /*
        $this->originalResource = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance()->getFileReferenceObject(
            $this->getUid()
        );
         */
        $resourceFactory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Resource\ResourceFactory::class);
        $this->originalResource = $resourceFactory->getFileReferenceObject(
              $this->getUid()  
        );
 
    }

    return $this->originalResource;
}

/**
 * @return string
 */
public function getTablenames() {
    return $this->tablenames;
}

/**
 * @param string $tablenames
 */
public function setTablenames($tablenames) {
    $this->tablenames = $tablenames;
}

}