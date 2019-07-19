<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\extend\helpers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Class ExcelHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExcelHelper
{
    /**
     * @param $file
     * @param bool $multi
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @inheritdoc
     */
    public static function readExcel(string $file, bool $firstLineIsHeader = true, bool $multiSheet = false): array
    {
        $spreadsheet = IOFactory::load($file);
        if ($multiSheet) {
            $data = [];
            foreach ($spreadsheet->getAllSheets() as $sheet) {
                $sheetData = $sheet->toArray();
                if ($firstLineIsHeader) {
                    array_walk($sheetData, static function (&$a) use ($sheetData) {
                        $a = array_combine($sheetData[0], $a);
                    });
                    array_shift($sheetData);
                }
                $data[$sheet->getTitle()] = $sheetData;
            }
            return $data;
        }

        $data = $spreadsheet->getActiveSheet()->toArray();
        if ($firstLineIsHeader) {
            array_walk($data, static function (&$a) use ($data) {
                $a = array_combine($data[0], $a);
            });
            array_shift($data);
        }
        return $data;
    }

    /**
     * @param string $file
     * @param array $data
     * @param bool $keyAsHeader
     * @param bool $multiSheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @inheritdoc
     */
    public static function writeExcel(string $file, array $data, bool $keyAsHeader = true, bool $multiSheet = false): void
    {
        $spreadsheet = new Spreadsheet();
        if ($multiSheet) {
            foreach ($data as $key => $datum) {
                if ($keyAsHeader) {
                    array_unshift($datum, array_keys($datum[0]));
                }
                $sheet = $spreadsheet->createSheet();
                $sheet->setTitle($key);
                static::writeData2Sheet($sheet, $datum);
            }
            $spreadsheet->removeSheetByIndex(0);
        } else {
            if ($keyAsHeader) {
                array_unshift($data, array_keys($data[0]));
            }
            $sheet = $spreadsheet->getActiveSheet();
            static::writeData2Sheet($sheet, $data);
        }
        $type = ucfirst(pathinfo($file, PATHINFO_EXTENSION));
        $writer = IOFactory::createWriter($spreadsheet, $type);
        if (file_exists($file)) {
            unlink($file);
        }
        $writer->save($file);
    }

    /**
     * @param Worksheet $sheet
     * @param $data
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @inheritdoc
     */
    private static function writeData2Sheet(Worksheet $sheet, array $data, bool $withImage = false): void
    {
        if ($withImage) {
            $rowIndex = 1;
            foreach ($data as $datum) {
                $columnIndex = 'A';
                foreach ($datum as $value) {
                    $pCoordinate = chr($columnIndex) . $rowIndex;
                    if (is_resource($value)) {
                        $drawing = new MemoryDrawing();
                        $drawing->setImageResource($value);
                        $drawing->setCoordinates($pCoordinate);
                        $drawing->setWorksheet($sheet);
                    } else {
                        $sheet->getCell($pCoordinate)->setValue($value);
                    }
                    $columnIndex++;
                }
                $rowIndex++;
            }
        } else {
            $sheet->fromArray($data);
        }
    }
}
