<?php
/**
 * TruncateTableTest.php
 */

namespace HDNET\Importr\Tests\Unit\Feature;

use HDNET\Importr\Feature\TruncateTable;
use HDNET\Importr\Processor\Configuration;
use HDNET\Importr\Service\DatabaseService;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

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
     * @var DatabaseConnection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $connection;

    public function setUp()
    {
        $connection = $this->getMock(DatabaseConnection::class);
        $this->connection = $connection;
        $databaseService = $this->getMock(DatabaseService::class);
        $databaseService->expects($this->any())->method('getDatabaseConnection')->will($this->returnValue($connection));
        $this->fixture = new TruncateTable($databaseService);
    }

    /**
     * @test
     */
    public function do_not_truncate_when_not_configured()
    {
        $processor = $this->getMockBuilder(Configuration::class)->disableOriginalConstructor()->getMock();
        $this->connection->expects($this->never())->method('exec_TRUNCATEquery');

        $this->fixture->execute($processor, []);
    }

    /**
     * @test
     */
    public function truncate_when_configured()
    {
        $processor = $this->getMockBuilder(Configuration::class)->disableOriginalConstructor()->getMock();

        $this->connection->expects($this->once())->method('exec_TRUNCATEquery');

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
        $this->connection->expects($this->exactly(3))->method('exec_TRUNCATEquery')->withConsecutive(['test'], ['test2'], ['test3']);

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
                'class' => get_class($this->fixture),
                'method' => 'execute',
                'object' => null,
                'passSignalInformation' => true,
            ],
        ];
        $this->assertEquals($expectedSlots, $slots);
    }
}
