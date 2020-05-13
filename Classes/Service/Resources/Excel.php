<?php

declare(strict_types=1);
namespace HDNET\Importr\Service\Resources;

use HDNET\Importr\Domain\Model\Strategy;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Description of Excel
 *
 * @author Tim Spiekerkötter <tim.spiekerkoetter@hdnet.de>
 * @author Tim Lochmüller <tim.lochmueller@hdnet.de>
 */
class Excel extends AbstractResource implements ResourceInterface
{

    /**
     * @var string
     */
    protected $filepathExpression = '/.xlsx?$/';

    /**
     * @var array
     */
    protected $content = [];

    /**
     * @var string
     */
    protected $filepath;

    /**
     * @return mixed
     */
    public function getConfiguration()
    {
        $configuration = parent::getConfiguration();
        $configuration['skipRows'] = isset($configuration['skipRows']) ? (int)$configuration['skipRows'] : 0;
        $configuration['sheet'] = isset($configuration['sheet']) ? (int)$configuration['sheet'] : -1;
        return $configuration;
    }

    /**
     * @param Strategy $strategy
     * @param string   $filepath
     */
    public function start(Strategy $strategy, $filepath)
    {
        $this->filepath = $filepath;
    }

    /**
     * @return string
     */
    public function getFilepathExpression()
    {
        return $this->filepathExpression;
    }

    public function parseResource()
    {
        $configuration = $this->getConfiguration();

        if (!\class_exists(IOFactory::class)) {
            throw new \Exception('PHP Excel is needed! Please install phpoffice/phpexcel (composer mode)', 12367812368);
        }

        $filename = GeneralUtility::getFileAbsFileName($this->filepath);
        /** @var IReader $reader */
        $reader = IOFactory::createReaderForFile($filename);
        $reader->setReadDataOnly(true);
        $sheet = $reader->load($filename);
        if ($configuration['sheet'] >= 0) {
            $worksheet = $sheet->getSheet($configuration['sheet']);
        } else {
            $worksheet = $sheet->getActiveSheet();
        }

        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();

        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

        for ($row = 1 + $configuration['skipRows']; $row <= $highestRow; ++$row) {
            $rowRecord = [];
            for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                $rowRecord[] = \trim(
                    $worksheet->getCellByColumnAndRow($col, $row)
                        ->getValue()
                );
            }
            $this->content[] = $rowRecord;
        }
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return \count($this->content);
    }

    /**
     * @param int $pointer
     *
     * @return mixed
     */
    public function getEntry($pointer)
    {
        return $this->content[$pointer];
    }

    public function end()
    {
    }
}
