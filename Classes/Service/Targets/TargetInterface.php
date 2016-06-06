<?php

namespace HDNET\Importr\Service\Targets;

/**
 * Description of TargetInterface
 *
 * @author timlochmueller
 */
interface TargetInterface {

	const RESULT_INSERT = 1;

	const RESULT_UPDATE = 2;

	const RESULT_IGNORED = 3;

	const RESULT_UNSURE = 4;

	const RESULT_ERROR = 5;

	/**
	 * @param $strategy \HDNET\Importr\Domain\Model\Strategy
	 */
	public function start(\HDNET\Importr\Domain\Model\Strategy $strategy);

	/**
	 *
	 * @param $entry array
	 *
	 * @return integer
	 */
	public function processEntry(array $entry);

	/**
	 *
	 */
	public function end();
}