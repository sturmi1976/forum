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
class CategoryTest extends UnitTestCase
{
    /**
     * @var \AL\Forum\Domain\Model\Category|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \AL\Forum\Domain\Model\Category::class,
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
    public function getCategoryReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getCategory()
        );
    }

    /**
     * @test
     */
    public function setCategoryForStringSetsCategory(): void
    {
        $this->subject->setCategory('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('category'));
    }
}
