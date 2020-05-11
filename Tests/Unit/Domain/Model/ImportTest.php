<?php

declare(strict_types=1);
/**
 * ImportTest.php
 */
namespace HDNET\Importr\Tests\Unit\Domain\Model;

use HDNET\Importr\Domain\Model\Import;
use HDNET\Importr\Service\Targets\TargetInterface;
use TYPO3\CMS\Core\Tests\UnitTestCase;

class ImportTest extends UnitTestCase
{

    /**
     * @test
     */
    public function do_all_increases_work_correctly()
    {
        $types = [
            TargetInterface::RESULT_INSERT => 'getInserted',
            TargetInterface::RESULT_UPDATE => 'getUpdated',
            TargetInterface::RESULT_IGNORED => 'getIgnored',
            TargetInterface::RESULT_UNSURE => 'getUnknowns',
            TargetInterface::RESULT_ERROR => 'getErrors',
        ];

        foreach ($types as $type => $getter) {
            $import = new Import();
            $import->increaseCount($type);
            self::assertEquals(1, $import->$getter());
        }
    }
}
