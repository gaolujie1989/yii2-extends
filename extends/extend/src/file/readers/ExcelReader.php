<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\readers;

use Imagick;
use lujie\extend\file\FileReaderInterface;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Yii;
use yii\base\BaseObject;
use yii\base\NotSupportedException;
use yii\helpers\FileHelper;

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
     * @var bool
     */
    public $readImage = false;

    /**
     * @var string|null
     */
    public $imagePathTemplate = '@runtime/excel_images/{title}_{coordinates}_{datetime}_{random}.{ext}';

    /**
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->imagePathTemplate = Yii::getAlias($this->imagePathTemplate);
        FileHelper::createDirectory($this->imagePathTemplate);
    }

    /**
     * @param string $file
     * @return array
     * @throws \ImagickException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @inheritdoc
     */
    public function read(string $file): array
    {
        $spreadsheet = IOFactory::load($file);
        if ($this->multiSheet) {
            $data = [];
            foreach ($spreadsheet->getAllSheets() as $sheet) {
                if ($this->readImage) {
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
        return $this->readImage
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
            $firstRow = (array)reset($data);
            array_walk($data, static function (&$row) use ($firstRow) {
                $row = array_combine($firstRow, $row);
            });
            array_shift($data);
        }
        //fix load end null value
//        array_walk($data, static function (&$row) {
//            while (end($row) === null) {
//                array_pop($row);
//            }
//        });
        return $data;
    }

    /**
     * @param Worksheet $sheet
     * @return array
     * @throws \ImagickException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function getSheetImages(Worksheet $sheet): array
    {
        $images = [];
        /** @var Drawing $drawing */
        foreach ($sheet->getDrawingCollection() as $drawing) {
            [$startColumn, $startRow] = Coordinate::coordinateFromString($drawing->getCoordinates());
            $imagePath = strtr($this->imagePathTemplate, [
                '{title}' => $sheet->getTitle(),
                '{coordinates}' => $drawing->getCoordinates(),
                '{ext}' => $drawing->getExtension(),
                '{date}' => date('Ymd'),
                '{datetime}' => date('YmdHis'),
                '{random}' => random_int(1000, 9999),
            ]);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $imagick = new Imagick();
            $imagick->readImage($drawing->getPath());
            $imagick->writeImage($imagePath);
            $startColumn = static::abc2Int($startColumn);
            $images[(int)$startRow - 1][$startColumn - 1] = $imagePath;
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
            $char = $columnABC[$i - 1];
            $int = ord($char);
            $ten += ($int - 64) * (26 ** ($len - $i));
        }
        return $ten;
    }

    /**
     * @param string $content
     * @return array
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function readContent(string $content): array
    {
        throw new NotSupportedException('Not support read content for excel file.');
    }
}
