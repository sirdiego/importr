<?php

declare(strict_types=1);

namespace HDNET\Importr\Tests\Resource\Csv;

use HDNET\Importr\Service\Resources\AbstractResource;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * AbstractResourceTest
 */
class AbstractResourceTest extends UnitTestCase
{
    /**
     * @var AbstractResource|MockObject
     */
    protected $fixture;

    public function setUp():void
    {
        $this->fixture = $this->getAccessibleMockForAbstractClass(AbstractResource::class);
    }

    /**
     * @test
     */
    public function configuration()
    {
        $this->fixture->setConfiguration(['test' => 'test']);
        self::assertEquals(['test' => 'test'], $this->fixture->getConfiguration());
        $this->fixture->addConfiguration('test2', 'test2');
        self::assertEquals(['test' => 'test', 'test2' => 'test2'], $this->fixture->getConfiguration());
    }
}
