<?php
namespace AL\Forum\Seo;
use TYPO3\CMS\Core\PageTitle\AbstractPageTitleProvider;

class ForumTitleProvider extends AbstractPageTitleProvider {
    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }
}