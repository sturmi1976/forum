<?php
namespace AL\Forum\DataProcessing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * ExtensionRecordToMenuProcessor
 */
class ExtensionRecordToMenuProcessor implements DataProcessorInterface {

    /**
     * The content object renderer
     *
     * @var ContentObjectRenderer
     */
    public $cObj;

    /**
     * The processor configuration
     *
     * @var array
     */
    protected $processorConfiguration;

    /**
     * Process
     *
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {

        $this->cObj = $cObj;
        $this->processorConfiguration = $processorConfiguration;

        if (!$this->processorConfiguration['addToMenus']) {
            return $processedData;
        }

        // Configuration for "product" argument
        if(GeneralUtility::_GET('tx_forum_forum')['forum']) {
           $recordTable = 'tx_forum_domain_model_forum';
           $recordUid = (int) GeneralUtility::_GET('tx_forum_forum')['forum'];
        }
        // Configuration for any other models (like above) here...

        if(GeneralUtility::_GET('tx_forum_forum')['forum']) {
        $record = $this->getExtensionRecord($recordTable, $recordUid);
        if ($record) {
            $menus = GeneralUtility::trimExplode(',', $this->processorConfiguration['addToMenus'], true);
            foreach ($menus as $menu) {
                if (isset($processedData[$menu])) {
                    $this->addExtensionRecordToMenu($record, $processedData[$menu]);
                }
            }
        }
        }
        return $processedData;

    }

    /**
     * Add the extension record to the menu items
     *
     * @param array $record
     * @param array $menu
     */
    protected function addExtensionRecordToMenu(array $record, array &$menu)
    {
        foreach ($menu as &$menuItem) {
            $menuItem['current'] = 0;
        }

        
        /* Link Generierung */
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
        $uriBuilder = $objectManager->get(\TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder::class);
        $uri = $uriBuilder
              ->setTargetPageUid(7)
              ->setArguments(['tx_forum_forum[forum]'=>GeneralUtility::_GET('tx_forum_forum')['forum'], 'tx_forum_forum[controller]'=>'Thread', 'tx_forum_forum[action]'=>'list'])
              ->build();

        $menu[] = [
            'data' => $record,
            'title' => $record['forum'],
            'active' => 1,
            'current' => 1,
            'link' => $uri 
        ];
    }

    /**
     * Get the extension record
     *
     * @param string $recordTable
     * @param int $recordUid
     * @return array
     */
    protected function getExtensionRecord(string $recordTable, int $recordUid)
    {
        if ($recordTable && $recordUid) {
            /** @var QueryBuilder $queryBuilder */
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable($recordTable);
            $row = $queryBuilder
                ->select('*')
                ->from($recordTable, 't')
                ->where($queryBuilder->expr()->eq('t.uid', $queryBuilder->createNamedParameter($recordUid, \PDO::PARAM_INT)))
                ->execute()
                ->fetch();

            if (is_array($row) && !empty($row)) {
                return $row;
            }
        }
        return [];
    }
}