<?php

namespace HDNET\Importr\Tests\ViewHelpers;

use HDNET\Importr\ViewHelpers\JsonViewHelper;
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * JsonViewHelperTest
 */
class JsonViewHelperTest extends UnitTestCase
{
    /**
     * @test
     */
    public function arrayToJson() {
        $fixup = new JsonViewHelper();
        $fixup->setRenderChildrenClosure(function () {
            return ['test' => 'done'];
        });
        $result = $fixup->render();

        $this->assertSame('{\"test\":\"done\"}', $result);
    }
}
