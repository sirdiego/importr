<?php

declare(strict_types=1);

namespace HDNET\Importr\Tests\Unit\ViewHelpers;

use HDNET\Importr\ViewHelpers\JsonViewHelper;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * JsonViewHelperTest
 */
class JsonViewHelperTest extends UnitTestCase
{
    /**
     * @test
     */
    public function arrayToJson()
    {
        $fixup = new JsonViewHelper();
        $fixup->setRenderChildrenClosure(function () {
            return ['test' => 'done'];
        });
        $result = $fixup->render();

        self::assertSame('{\"test\":\"done\"}', $result);
    }
}
