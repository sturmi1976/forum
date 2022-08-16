<?php

declare(strict_types=1);

namespace AL\Forum\Controller;


/**
 * This file is part of the "Forum" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2022 Andre Lanius <a-lanius@web.de>, AL Webdesign
 */

/**
 * CategoryController
 */
class CategoryController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * categoryRepository
     *
     * @var \AL\Forum\Domain\Repository\CategoryRepository
     */
    protected $categoryRepository = null;

    /**
     * @param \AL\Forum\Domain\Repository\CategoryRepository $categoryRepository
     */
    public function injectCategoryRepository(\AL\Forum\Domain\Repository\CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * action list
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function listAction(): \Psr\Http\Message\ResponseInterface
    {
        return "TESTER";
        $categories = $this->categoryRepository->findAll();
        $this->view->assign('categories', $categories);
        return $this->htmlResponse();
    }
}
