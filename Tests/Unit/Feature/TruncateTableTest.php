<?php

declare(strict_types=1);
/**
 * TruncateTableTest.php
 */
namespace HDNET\Importr\Tests\Unit\Feature;

use HDNET\Importr\Feature\TruncateTable;
use HDNET\Importr\Migration\DatabaseConnectionMigrationInterface;
use HDNET\Importr\Processor\Configuration;
use HDNET\Importr\Service\DatabaseService;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Class TruncateTableTest
 */
class TruncateTableTest extends UnitTestCase
{
    /**
     * @var TruncateTable
     */
    protected $fixture;

    /**
     * @var DatabaseConnectionMigrationInterface|MockObject
     */
    protected $connection;

    public function setUp(): void
    {
        $connection = $this->getMockBuilder(DatabaseConnectionMigrationInterface::class)->getMock();
        $this->connection = $connection;
        $databaseService = $this->getMockBuilder(DatabaseService::class)->getMock();
        $databaseService->expects(self::any())->method('getDatabaseConnection')->willReturn($connection);
        $this->fixture = new TruncateTable($databaseService);
    }

    /**
     * @test
     */
    public function do_not_truncate_when_not_configured()
    {
        $processor = $this->getMockBuilder(Configuration::class)->disableOriginalConstructor()->getMock();
        $this->connection->expects(self::never())->method('exec_TRUNCATEquery');

        $this->fixture->execute($processor, []);
    }

    /**
     * @test
     */
    public function truncate_when_configured()
    {
        $processor = $this->getMockBuilder(Configuration::class)->disableOriginalConstructor()->getMock();

        $this->connection->expects(self::once())->method('exec_TRUNCATEquery');

        $this->fixture->execute($processor, ['truncate' => true]);
    }

    /**
     * @test
     */
    public function truncate_multiple_tables_when_configured()
    {
        $configuration = [
            'truncate' => [
                'test',
                'test2',
                'test3'
            ]
        ];

        $processor = $this->getMockBuilder(Configuration::class)->disableOriginalConstructor()->getMock();
        $this->connection->expects(self::exactly(3))->method('exec_TRUNCATEquery')->withConsecutive(['test'], ['test2'], ['test3']);

        $this->fixture->execute($processor, $configuration);
    }

    /**
     * @test
     */
    public function check_if_single_slot_gets_registered()
    {
        $this->fixture->enable();
        $dispatcher = GeneralUtility::makeInstance(Dispatcher::class);
        $slots = $dispatcher->getSlots(Configuration::class, 'preParseConfiguration');
        $expectedSlots = [
            [
                'class' => \get_class($this->fixture),
                'method' => 'execute',
                'object' => null,
                'passSignalInformation' => true,
            ],
        ];
        self::assertEquals($expectedSlots, $slots);
    }
}
