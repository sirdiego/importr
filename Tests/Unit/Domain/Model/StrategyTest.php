<?php declare(strict_types=1);

namespace HDNET\Importr\Tests\Unit\Domain\Model;

use HDNET\Importr\Domain\Model\Strategy;
use TYPO3\CMS\Core\Tests\UnitTestCase;

class StrategyTest extends UnitTestCase
{
    public function testRawConfigLoadsFile()
    {
        $strategy = new Strategy();
        $classReflection = new \ReflectionClass($strategy);
        $propertyReflection = $classReflection->getProperty('configurationFile');
        $propertyReflection->setAccessible(true);
        $propertyReflection->setValue($strategy, 't3://file/test.yaml');
        $config = $strategy->getRawConfiguration();
        $this->assertEquals('', $config);
    }
}
