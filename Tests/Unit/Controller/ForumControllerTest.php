<?php

declare(strict_types=1);

namespace AL\Forum\Tests\Unit\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\View\ViewInterface;

/**
 * Test case
 *
 * @author Andre Lanius <a-lanius@web.de>
 */
class ForumControllerTest extends UnitTestCase
{
    /**
     * @var \AL\Forum\Controller\ForumController|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder($this->buildAccessibleProxy(\AL\Forum\Controller\ForumController::class))
            ->onlyMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllForumsFromRepositoryAndAssignsThemToView(): void
    {
        $allForums = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $forumRepository = $this->getMockBuilder(\AL\Forum\Domain\Repository\ForumRepository::class)
            ->onlyMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $forumRepository->expects(self::once())->method('findAll')->will(self::returnValue($allForums));
        $this->subject->_set('forumRepository', $forumRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('forums', $allForums);
        $this->subject->_set('view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenForumToView(): void
    {
        $forum = new \AL\Forum\Domain\Model\Forum();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('forum', $forum);

        $this->subject->showAction($forum);
    }
}
