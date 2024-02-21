<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\writers;

use lujie\extend\flysystem\Filesystem;
use GdImage;
use Imagine\Image\Format;
use lujie\extend\file\FileWriterInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use yii\base\BaseObject;
use yii\helpers\FileHelper;
use yii\imagine\Image;

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

    public $imageResize = [
        'width' => 120,
        'height' => null
    ];

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
                if ($this->keyAsHeader) {
                    array_unshift($datum, array_keys(reset($datum)));
                }
                $writer->writeSheet($datum, $key);
            }
        } else {
            if ($this->keyAsHeader) {
                array_unshift($data, array_keys(reset($data)));
            }
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
        if ($this->keyAsHeader) {
            array_unshift($data, array_keys(reset($data)));
        }
        if ($this->withImage) {
            $rowIndex = 1;
            foreach ($data as $values) {
                $columnIndex = 'A';
                foreach ($values as $value) {
                    $pCoordinate = $columnIndex . $rowIndex;
                    if (is_array($value)) {
                        if (isset($value['url'])) {
                            $resource = imagecreatefromstring(file_get_contents($value['url']));
                        } else if (isset($value['file'], $value['fs']) && $value['fs'] instanceof Filesystem) {
                            $resource = $value['fs']->readStream($value['file']);
                        } else {
                            $resource = null;
                        }
                    } else if (is_resource($value)) {
                        $resource = $value;
                    } else if ($value instanceof GdImage) {
                        $resource = $value;
                    } else {
                        $resource = null;
                    }
                    if (isset($resource)) {
                        if (is_resource($resource) && $this->imageResize) {
                            $thumbnail = Image::thumbnail($resource, $this->imageResize['width'], $this->imageResize['height']);
                            $resource = $thumbnail->get(Format::ID_GIF);
                            $resource = imagecreatefromstring($resource);
                        }
                        $drawing = new MemoryDrawing();
                        $drawing->setImageResource($resource);
                        $drawing->setCoordinates($pCoordinate);
                        $drawing->setWorksheet($sheet);
                        $sheet->getRowDimension($rowIndex)->setRowHeight(round($drawing->getHeight() * 0.77));
                    } else {
                        $sheet->getCell($pCoordinate)->setValue($value);
                    }
                    $columnIndex++;
                }
                $rowIndex++;
            }
        } else {
            $sheet->fromArray($data, null, 'A1', true);
        }
    }

    /**
     * @param Worksheet $sheet
     * @param array $styles
     * @inheritdoc
     */
    protected function setSheetStyle(Worksheet $sheet, array $styles): void
    {
        foreach ($styles as $pos => $style) {
            if ($cellStyle = $sheet->getStyle($pos)) {
                foreach ($style as $key => $value) {
                    $alignment = $cellStyle->getAlignment();
                    $method = 'set' . ucfirst($key);
                    if (method_exists($alignment, $method)) {
                        $alignment->{$method}($value);
                    }
                }
            }
            if (!is_numeric($pos[strlen($pos) - 1]) && $dimension = $sheet->getColumnDimension($pos)) {
                foreach ($style as $key => $value) {
                    $method = 'set' . ucfirst($key);
                    if (method_exists($dimension, $method)) {
                        $dimension->{$method}($value);
                    }
                }
            }
        }
    }
}
