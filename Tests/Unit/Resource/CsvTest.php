<?php

declare(strict_types=1);

namespace HDNET\Importr\Tests\Resource\Csv;

use HDNET\Importr\Domain\Model\Strategy;
use HDNET\Importr\Service\Resources\Csv;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * CsvTest
 */
class CsvTest extends UnitTestCase
{
    /**
     * @var Csv
     */
    protected $fixture;

    /**
     * @var  vfsStreamDirectory
     */
    protected $root;

    public function setUp(): void
    {
        $this->root = vfsStream::setup('import');
        $this->fixture = $this->getAccessibleMock(Csv::class, ['getFilePath']);
        $this->fixture->expects(self::any())->method('getFilePath')->willReturn('vfs://import/import.csv');
    }

    /**
     * @test
     */
    public function import()
    {
        $path = vfsStream::newFile('import.csv')->at($this->root)->setContent('test;test')->url();

        $this->fixture->start(new Strategy(), $path);
        $this->fixture->parseResource();
        self::assertEquals(1, $this->fixture->getAmount());
        self::assertEquals(['test', 'test'], $this->fixture->getEntry(0));
    }

    /**
     * @test
     */
    public function importAndSkipOne()
    {
        $path = vfsStream::newFile('import.csv')->at($this->root)->setContent("test;test\ntest;test")->url();

        $this->fixture->addConfiguration('skipRows', 1);
        $this->fixture->start(new Strategy(), $path);
        $this->fixture->parseResource();
        self::assertEquals(1, $this->fixture->getAmount());
        self::assertEquals(['test', 'test'], $this->fixture->getEntry(0));
    }

    /**
     * @test
     */
    public function filePathExpressionIsCsv()
    {
        $expression = $this->fixture->getFilepathExpression();
        self::assertEquals('/.csv$/', $expression);
    }
}
