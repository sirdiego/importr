<?php

namespace HDNET\Importr\Tests\Resource\Csv;

use HDNET\Importr\Service\Resources\AbstractResource;
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * AbstractResourceTest
 */
class AbstractResourceTest extends UnitTestCase
{
    /**
     * @var AbstractResource|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $fixture;

    public function setUp()
    {
        $this->fixture = $this->getMockForAbstractClass(AbstractResource::class);
    }

    /**
     * @test
     */
    public function configuration()
    {
        $this->fixture->setConfiguration(['test' => 'test']);
        $this->assertEquals(['test' => 'test'], $this->fixture->getConfiguration());
        $this->fixture->addConfiguration('test2', 'test2');
        $this->assertEquals(['test' => 'test', 'test2' => 'test2'], $this->fixture->getConfiguration());
    }
}
