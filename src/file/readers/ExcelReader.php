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
 * Class ExcelReader
 * @package lujie\extend\file\readers
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
            array_walk($data, static function (&$values) use ($data) {
                $values = array_combine($data[0], $values);
            });
            array_shift($data);
        }
        //fix load end null value
        array_walk($data, static function (&$values) {
            while (end($values) === null) {
                array_pop($values);
            }
        });
        return $data;
    }

    /**
     * @param string $columnABC
     * @return int
     * @inheritdoc
     */
    public static function abc2Int(string $columnABC): int
    {
        $ten = 0;
        $columnABC = strtoupper($columnABC);
        $len = strlen($columnABC);
        for ($i = 1; $i <= $len; $i++) {
            $char = substr($columnABC, $i - 1, 1);
            $int = ord($char);
            $ten += ($int - 64) * pow(26, $len - $i);
        }
        return $ten;
    }
}
