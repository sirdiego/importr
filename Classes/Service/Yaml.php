<?php

declare(strict_types=1);
namespace HDNET\Importr\Service;

use Symfony\Component\Yaml\Exception\ParseException;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Description of Yaml
 *
 * @author timlochmueller
 */
class Yaml
{
    /**
     * @param $input string
     *
     * @return array
     */
    public static function parse($input)
    {
        self::legacyClassLoading();
        try {
            $array = \Symfony\Component\Yaml\Yaml::parse($input);
            /**
             * The parser can return integer or string
             * if an number or a string is passed to it.
             * We always need an configuration array, so
             * we drop any other datatype here.
             */
            if (!\is_array($array)) {
                $array = [];
            }
        } catch (ParseException $e) {
            /**
             * @todo maybe log the error
             */
            $array = [];
        }
        return $array;
    }

    public static function legacyClassLoading()
    {
        if (!\class_exists(\Symfony\Component\Yaml\Yaml::class)) {
            $yamlComponentPath = ExtensionManagementUtility::extPath('importr', 'Resources/Private/Php/Yaml/');
            include_once $yamlComponentPath . 'Yaml.php';
            include_once $yamlComponentPath . 'Parser.php';
            include_once $yamlComponentPath . 'Inline.php';
            include_once $yamlComponentPath . 'Dumper.php';
            include_once $yamlComponentPath . 'Escaper.php';
            include_once $yamlComponentPath . 'Unescaper.php';
            include_once $yamlComponentPath . 'Exception/ExceptionInterface.php';
            include_once $yamlComponentPath . 'Exception/ParseException.php';
            include_once $yamlComponentPath . 'Exception/DumpException.php';
        }
    }
}
