<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\readers;

use lujie\extend\file\FileReaderInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use yii\base\BaseObject;

/**
 * Class ExcelExporter
 * @package lujie\data\exchange\parsers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExcelReader extends BaseObject implements FileReaderInterface
{
    public $firstLineIsHeader = true;

    public $multiSheet = false;

    /**
     * @param string $file
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @inheritdoc
     */
    public function read(string $file): array
    {
        $spreadsheet = IOFactory::load($file);
        if ($this->multiSheet) {
            $data = [];
            foreach ($spreadsheet->getAllSheets() as $sheet) {
                $data[$sheet->getTitle()] = $this->getSheetData($sheet);
            }
            return $data;
        }

        return $this->getSheetData($spreadsheet->getActiveSheet());
    }

    /**
     * @param Worksheet $sheet
     * @return array
     * @inheritdoc
     */
    public function getSheetData(Worksheet $sheet): array
    {
        $data = $sheet->toArray();
        if ($this->firstLineIsHeader) {
            array_walk($data, static function (&$a) use ($data) {
                $a = array_combine($data[0], $a);
            });
            array_shift($data);
        }
        return $data;
    }
}
