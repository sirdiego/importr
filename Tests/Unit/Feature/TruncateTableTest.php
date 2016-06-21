<?php
/**
 * TruncateTableTest.php
 */

namespace HDNET\Importr\Tests\Unit\Feature;

use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Domain\Model\Strategy;
use HDNET\Importr\Feature\TruncateTable;
use HDNET\Importr\Service\DatabaseService;
use HDNET\Importr\Service\ManagerInterface;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Tests\UnitTestCase;

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
        $manager = $this->getMock(ManagerInterface::class);
        $import = $this->getMock(Import::class);
        $strategy = $this->getMock(Strategy::class);

        $import->expects($this->once())->method('getStrategy')->will($this->returnValue($strategy));
        $this->connection->expects($this->never())->method('exec_TRUNCATEquery');

        $this->fixture->execute($manager, $import);
    }

    /**
     * @test
     */
    public function truncate_when_configured()
    {
        $manager = $this->getMock(ManagerInterface::class);
        $import = $this->getMock(Import::class);
        $strategy = $this->getMock(Strategy::class);
        $strategy->expects($this->once())->method('getConfiguration')->will($this->returnValue(['truncate' => true]));
        $import->expects($this->once())->method('getStrategy')->will($this->returnValue($strategy));

        $this->connection->expects($this->once())->method('exec_TRUNCATEquery');

        $this->fixture->execute($manager, $import);
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

        $manager = $this->getMock(ManagerInterface::class);
        $import = $this->getMock(Import::class);
        $strategy = $this->getMock(Strategy::class);

        $strategy->expects($this->once())
            ->method('getConfiguration')
            ->will($this->returnValue($configuration));
        $import->expects($this->once())->method('getStrategy')->will($this->returnValue($strategy));
        $this->connection->expects($this->exactly(3))->method('exec_TRUNCATEquery')->withConsecutive(['test'], ['test2'], ['test3']);

        $this->fixture->execute($manager, $import);
    }
}
