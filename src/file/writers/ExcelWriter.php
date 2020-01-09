<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\writers;

use lujie\extend\file\FileWriterInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use XLSXWriter;
use yii\base\BaseObject;

/**
 * Class ExcelWriter
 * @package lujie\extend\file\writers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExcelWriter extends BaseObject implements FileWriterInterface
{
    public const ADAPTER_XLSXWriter = 'XLSXWriter';

    public $keyAsHeader = true;

    public $multiSheet = false;

    public $adapter = 'XLSXWriter';

    /**
     * @param string $file
     * @param array $data
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @inheritdoc
     */
    public function write(string $file, array $data): void
    {
        if ($this->adapter === self::ADAPTER_XLSXWriter) {
            $this->writeByXLSXWriter($file, $data);
            return;
        }

        $spreadsheet = new Spreadsheet();
        if ($this->multiSheet) {
            foreach ($data as $key => $datum) {
                $sheet = $spreadsheet->createSheet();
                $sheet->setTitle($key);
                $this->setSheetData($sheet, $datum);
            }
            $spreadsheet->removeSheetByIndex(0);
        } else {
            $this->setSheetData($spreadsheet->getActiveSheet(), $data);
        }

        $type = ucfirst(pathinfo($file, PATHINFO_EXTENSION));
        $writer = IOFactory::createWriter($spreadsheet, $type);
        if (file_exists($file)) {
            unlink($file);
        }
        $writer->save($file);
    }

    /**
     * @param string $file
     * @param array $data
     * @inheritdoc
     */
    public function writeByXLSXWriter(string $file, array $data): void
    {
        $writer = new XLSXWriter();
        if ($this->multiSheet) {
            foreach ($data as $key => $datum) {
                $writer->writeSheet($datum, $key);
            }
        } else {
            $writer->writeSheet($data);
        }

        if (file_exists($file)) {
            unlink($file);
        }
        $writer->writeToFile($file);
    }

    /**
     * @param Worksheet $sheet
     * @param array $data
     * @param bool $withImage
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @inheritdoc
     */
    public function setSheetData(Worksheet $sheet, array $data, bool $withImage = false): void
    {
        if ($this->keyAsHeader) {
            array_unshift($data, array_keys($data[0]));
        }
        if ($withImage) {
            $rowIndex = 1;
            foreach ($data as $values) {
                $columnIndex = 'A';
                foreach ($values as $value) {
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
