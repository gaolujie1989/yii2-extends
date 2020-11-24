<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\readers;

use Imagick;
use lujie\extend\file\FileReaderInterface;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use yii\base\BaseObject;
use yii\base\InvalidValueException;

/**
 * Class ExcelReader
 * @package lujie\extend\file\readers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExcelReader extends BaseObject implements FileReaderInterface
{
    /**
     * @var bool 
     */
    public $firstLineIsHeader = true;

    /**
     * @var bool 
     */
    public $multiSheet = false;

    /**
     * @var string|null
     */
    public $imageDir;

    /**
     * @param string $file
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @inheritdoc
     */
    public function read(string $file): array
    {
        $spreadsheet = IOFactory::load($file);
        if ($this->multiSheet) {
            $data = [];
            foreach ($spreadsheet->getAllSheets() as $sheet) {
                if ($this->imageDir) {
                    $data[$sheet->getTitle()] = [
                        'data' => $this->getSheetData($sheet),
                        'images' => $this->getSheetImages($sheet),
                    ];
                } else {
                    $data[$sheet->getTitle()] = $this->getSheetData($sheet);
                }
            }
            return $data;
        }

        $sheet = $spreadsheet->getActiveSheet();
        return $this->imageDir
            ? [
                'data' => $this->getSheetData($sheet),
                'images' => $this->getSheetImages($sheet),
            ]
            : $this->getSheetData($sheet);
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
     * @param Worksheet $sheet
     * @return array
     * @throws \ImagickException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @inheritdoc
     */
    public function getSheetImages(Worksheet $sheet): array
    {
        $images = [];
        foreach ($sheet->getDrawingCollection() as $drawing) {
            [$startColumn, $startRow] = Coordinate::coordinateFromString($drawing->getCoordinates());
            $extension = $drawing->getExtension();
            $imagePath = rtrim($this->imageDir, '/') . '/' . $sheet->getTitle() . '-' . $drawing->getCoordinates() . '.' . $extension;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $imagick = new Imagick();
            $imagick->readImage($drawing->getPath());
            $imagick->writeImage($imagePath);
            $startColumn = ExcelReader::abc2Int($startColumn);
            $images[$startRow - 1][$startColumn - 1] = $imagePath;
        }
        return $images;
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
