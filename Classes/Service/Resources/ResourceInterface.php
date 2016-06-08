<?php

namespace HDNET\Importr\Service\Resources;

use HDNET\Importr\Domain\Model\Strategy;

/**
 * Description of ResourceInterface
 *
 * @author timlochmueller
 */
interface ResourceInterface {

	/**
	 * @param $strategy Strategy
	 * @param $filepath array
	 */
	public function start(Strategy $strategy, $filepath);

	/**
	 * @return string
	 */
	public function getFilepathExpression();

	/**
	 *
	 */
	public function parseResource();

	/**
	 * @return integer
	 */
	public function getAmount();

	/**
	 * @param integer $pointer
	 */
	public function getEntry($pointer);

	/**
	 *
	 */
	public function end();
}
