<?php
/**
 * ImportTest.php
 */

namespace HDNET\Importr\Tests\Unit\Domain\Model;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Service\Targets\TargetInterface;

class ImportTest extends UnitTestCase {

    /**
     * @test
     */
    public function do_all_increases_work_correctly()
    {
        $import = new Import;

        $types = [
            TargetInterface::RESULT_INSERT => 'getInserted',
            TargetInterface::RESULT_UPDATE => 'getUpdated',
            TargetInterface::RESULT_IGNORED => 'getIgnored',
            TargetInterface::RESULT_UNSURE => 'getUnknowns',
            TargetInterface::RESULT_ERROR => 'getErrors',
        ];

        foreach($types as $type => $getter) {
            $import->increaseCount($type);
            $this->assertEquals(1, $import->$getter());
        }
    }
}
