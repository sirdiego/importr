<?php

namespace HDNET\Importr\Tests\Unit\Service;

use HDNET\Importr\Service\Yaml;
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * YamlTest
 */
class YamlTest extends UnitTestCase
{
    /**
     * @test
     */
    public function parse()
    {
        $string = <<<EOT
men: [John Smith, Bill Jones]
women:
  - Mary Smith
  - Susan Williams
EOT;
        $result = Yaml::parse($string);
        $this->assertEquals([
            'men' => [
                'John Smith',
                'Bill Jones',
            ],
            'women' => [
                'Mary Smith',
                'Susan Williams',
            ]
        ], $result);
    }
}
