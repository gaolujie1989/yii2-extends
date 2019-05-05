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
    public static function readExcel($file, $firstLineIsHeader = true, $multiSheet = false)
    {
        $spreadsheet = IOFactory::load($file);
        if ($multiSheet) {
            $data = [];
            foreach ($spreadsheet->getAllSheets() as $sheet) {
                $sheetData = $sheet->toArray();
                if ($firstLineIsHeader) {
                    array_walk($sheetData, function (&$a) use ($sheetData) {
                        $a = array_combine($sheetData[0], $a);
                    });
                    array_shift($sheetData);
                }
                $data[$sheet->getTitle()] = $sheetData;
            }
            return $data;
        } else {
            $data = $spreadsheet->getActiveSheet()->toArray();
            if ($firstLineIsHeader) {
                array_walk($data, function (&$a) use ($data) {
                    $a = array_combine($data[0], $a);
                });
                array_shift($data);
            }
            return $data;
        }
    }

    /**
     * @param $file
     * @param $data
     * @param bool $keyAsHeader
     * @param bool $multiSheet
     * @return mixed
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @inheritdoc
     */
    public static function writeExcel($file, $data, $keyAsHeader = true, $multiSheet = false)
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
        return $file;
    }

    /**
     * @param Worksheet $sheet
     * @param $data
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @inheritdoc
     */
    private static function writeData2Sheet(Worksheet $sheet, $data, $withImage = false)
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
