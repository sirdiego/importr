<?php

namespace HDNET\Importr\Tests\Resource\Csv;

use HDNET\Importr\Domain\Model\Strategy;
use HDNET\Importr\Service\Resources\Csv;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use TYPO3\CMS\Core\Tests\UnitTestCase;

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

    public function setUp()
    {
        $this->root = vfsStream::setup('import');
        $this->fixture = $this->getAccessibleMock(Csv::class, ['getFilePath']);
        $this->fixture->expects($this->any())->method('getFilePath')->will($this->returnValue('vfs://import/import.csv'));
    }

    /**
     * @test
     */
    public function import()
    {
        $path = vfsStream::newFile('import.csv')->at($this->root)->setContent("test;test")->url();

        $this->fixture->start(new Strategy(), $path);
        $this->fixture->parseResource();
        $this->assertEquals(1, $this->fixture->getAmount());
        $this->assertEquals(['test', 'test'], $this->fixture->getEntry(0));
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
        $this->assertEquals(1, $this->fixture->getAmount());
        $this->assertEquals(['test', 'test'], $this->fixture->getEntry(0));
    }

    /**
     * @test
     */
    public function filePathExpressionIsCsv()
    {
        $expression = $this->fixture->getFilepathExpression();
        $this->assertEquals('/.csv$/', $expression);
    }
}
