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
use yii\helpers\FileHelper;

/**
 * Class ExcelWriter
 * @package lujie\extend\file\writers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExcelWriter extends BaseObject implements FileWriterInterface
{
    public const ADAPTER_XLSX_WRITER = 'XLSXWriter';

    public const ADAPTER_PHP_SPREAD_SHEET = 'PhpSpreadsheet';

    public $keyAsHeader = true;

    public $multiSheet = false;

    public $withImage = false;

    /**
     * @var string
     */
    public $adapter = self::ADAPTER_XLSX_WRITER;

    /**
     * @param string $file
     * @param array $data
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function write(string $file, array $data): void
    {
        if (file_exists($file)) {
            unlink($file);
        }
        FileHelper::createDirectory(dirname($file));
        if ($this->keyAsHeader) {
            if ($this->multiSheet) {
                array_walk($data, static function (&$datum) {
                    array_unshift($datum, array_keys(reset($datum)));
                });
            } else {
                array_unshift($data, array_keys(reset($data)));
            }
        }

        $withStyle = count($data) === 2 && isset($data['data'], $data['style']);
        if ($this->adapter === self::ADAPTER_PHP_SPREAD_SHEET || $this->withImage || $withStyle) {
            $this->writeByPhpSpreadsheet($file, $data);
        } else {
            $this->writeByXLSXWriter($file, $data);
        }
    }

    /**
     * @param string $file
     * @param array $data
     * @inheritdoc
     */
    protected function writeByXLSXWriter(string $file, array $data): void
    {
        $writer = new XLSXWriter();
        if ($this->multiSheet) {
            foreach ($data as $key => $datum) {
                $writer->writeSheet($datum, $key);
            }
        } else {
            $writer->writeSheet($data);
        }
        $writer->writeToFile($file);
    }

    /**
     * @param string $file
     * @param array $data
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @inheritdoc
     */
    protected function writeByPhpSpreadsheet(string $file, array $data): void
    {
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
        $writer->save($file);
    }

    /**
     * @param Worksheet $sheet
     * @param array $data
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @inheritdoc
     */
    protected function setSheetData(Worksheet $sheet, array $data): void
    {
        if (count($data) === 2 && isset($data['data'], $data['style'])) {
            $this->setSheetData($sheet, $data['data']);
            $this->setSheetStyle($sheet, $data['style']);
            return;
        }
        if ($this->withImage) {
            $rowIndex = 1;
            foreach ($data as $values) {
                $columnIndex = 'A';
                foreach ($values as $value) {
                    $pCoordinate = $columnIndex . $rowIndex;
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

    /**
     * @param Worksheet $sheet
     * @param array $styles
     * @inheritdoc
     */
    protected function setSheetStyle(Worksheet $sheet, array $styles): void
    {
        foreach ($styles as $cell => $style) {
            if ($cell = $sheet->getStyle($cell)) {
                foreach ($style as $key => $value) {
                    $alignment = $cell->getAlignment();
                    $method = 'set' . ucfirst($key);
                    if (method_exists($alignment, $method)) {
                        $alignment->{$method}($value);
                    }
                }
            }
        }
    }
}
