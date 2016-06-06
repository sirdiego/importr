<?php
namespace HDNET\Importr\Service;

use Symfony\Component\Yaml\Exception\ParseException;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Description of Yaml
 *
 * @author timlochmueller
 */
class Yaml {

	/**
	 * @param $input string
	 *
	 * @return array
	 */
	public static function parse($input) {

		/**
		 * Maybe an custom autoloader would be cool here.
		 */
		$yamlComponentPath = ExtensionManagementUtility::extPath('importr', 'Resources/Private/Php/Yaml/');
		require_once $yamlComponentPath . 'Yaml.php';
		require_once $yamlComponentPath . 'Parser.php';
		require_once $yamlComponentPath . 'Inline.php';
		require_once $yamlComponentPath . 'Dumper.php';
		require_once $yamlComponentPath . 'Escaper.php';
		require_once $yamlComponentPath . 'Unescaper.php';
		require_once $yamlComponentPath . 'Exception/ExceptionInterface.php';
		require_once $yamlComponentPath . 'Exception/ParseException.php';
		require_once $yamlComponentPath . 'Exception/DumpException.php';

		try {
			$array = \Symfony\Component\Yaml\Yaml::parse($input);
			/**
			 * The parser can return integer or string
			 * if an number or a string is passed to it.
			 * We always need an configuration array, so
			 * we drop any other datatype here.
			 */
			if (!is_array($array)) {
				$array = array();
			}
		} catch (ParseException $e) {
			/**
			 * @todo maybe log the error
			 */
			$array = array();
		}
		return $array;
	}
}