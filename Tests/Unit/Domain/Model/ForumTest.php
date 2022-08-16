<?php

declare(strict_types=1);

namespace AL\Forum\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Andre Lanius <a-lanius@web.de>
 */
class ForumTest extends UnitTestCase
{
    /**
     * @var \AL\Forum\Domain\Model\Forum|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \AL\Forum\Domain\Model\Forum::class,
            ['dummy']
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getForumReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getForum()
        );
    }

    /**
     * @test
     */
    public function setForumForStringSetsForum(): void
    {
        $this->subject->setForum('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('forum'));
    }

    /**
     * @test
     */
    public function getCategoryReturnsInitialValueForCategory(): void
    {
        self::assertEquals(
            null,
            $this->subject->getCategory()
        );
    }

    /**
     * @test
     */
    public function setCategoryForCategorySetsCategory(): void
    {
        $categoryFixture = new \AL\Forum\Domain\Model\Category();
        $this->subject->setCategory($categoryFixture);

        self::assertEquals($categoryFixture, $this->subject->_get('category'));
    }
}
