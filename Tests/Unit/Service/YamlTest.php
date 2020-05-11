<?php

declare(strict_types=1);

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
    public function parseValid()
    {
        $string = <<<EOT
men: [John Smith, Bill Jones]
women:
  - Mary Smith
  - Susan Williams
EOT;
        $result = Yaml::parse($string);
        self::assertEquals([
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

    /**
     * @test
     */
    public function parseInvalid()
    {
        $string = <<<EOT
men [John Smith, Bill Jones]
women:
  - Mary Smith
  - Susan Williams
EOT;
        $result = Yaml::parse($string);
        self::assertEquals([], $result);
    }

    /**
     * @test
     */
    public function parseSimpleText()
    {
        $string = 'Test';
        $result = Yaml::parse($string);
        self::assertEquals([], $result);
    }
}
