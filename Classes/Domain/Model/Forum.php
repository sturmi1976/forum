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
class Forum extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * forum
     *
     * @var string
     */
    protected $forum = '';

    /**
     * category
     *
     * @var \AL\Forum\Domain\Model\Category
     */
    protected $category = null;

    /**
     * Returns the forum
     *
     * @return string
     */
    public function getForum()
    {
        return $this->forum;
    }

    /**
     * Sets the forum
     *
     * @param string $forum
     * @return void
     */
    public function setForum(string $forum)
    {
        $this->forum = $forum;
    }

    /**
     * Returns the category
     *
     * @return \AL\Forum\Domain\Model\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Sets the category
     *
     * @param \AL\Forum\Domain\Model\Category $category
     * @return void
     */
    public function setCategory(\AL\Forum\Domain\Model\Category $category)
    {
        $this->category = $category;
    }
}
