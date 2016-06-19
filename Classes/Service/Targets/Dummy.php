<?php
namespace HDNET\Importr\Service\Targets;

use HDNET\Importr\Domain\Model\Strategy;

/**
 * Description of ExtbaseModel
 *
 * @author tim
 */
class Dummy extends AbstractTarget implements TargetInterface
{

    protected $possibleResults = [
        TargetInterface::RESULT_IGNORED,
        TargetInterface::RESULT_INSERT,
        TargetInterface::RESULT_ERROR,
        TargetInterface::RESULT_UNSURE,
        TargetInterface::RESULT_UPDATE,
    ];

    /**
     * @return array
     */
    public function getConfiguration()
    {
        $configuration = parent::getConfiguration();
        $configuration['sleepSeconds'] = (isset($configuration['sleepSeconds'])) ? (int)$configuration['sleepSeconds'] : 1;
        $configuration['result'] = (isset($configuration['result'])) ? (int)$configuration['result'] : 'unsure';
        return $configuration;
    }

    /**
     * @param Strategy $strategy
     *
     * @return void
     */
    public function start(Strategy $strategy)
    {
    }

    /**
     * @param array $entry
     *
     * @throws \Exception
     * @return int|void
     */
    public function processEntry(array $entry)
    {
        $configuration = $this->getConfiguration();
        if ($configuration['sleepSeconds'] > 0) {
            sleep($configuration['sleepSeconds']);
        }

        if ($configuration['result'] == 'random') {
            $configuration['result'] = $this->possibleResults[rand(0, sizeof($this->possibleResults) - 1)];
        }

        if (!in_array($configuration['result'], $this->possibleResults)) {
            throw new \Exception('Invalid result param "' . $configuration['result'] . '". Have to be one of: ' . var_export(
                $results,
                true
            ), 12617283);
        }

        return $configuration['result'];
    }

    public function end()
    {
    }
}
