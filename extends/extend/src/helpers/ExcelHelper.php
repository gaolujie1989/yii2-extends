<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\extend\helpers;

use lujie\extend\file\readers\ExcelReader;
use lujie\extend\file\writers\ExcelWriter;

/**
 * Class ExcelHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExcelHelper
{
    /**
     * @param string $file
     * @param bool $firstLineIsHeader
     * @param bool $multiSheet
     * @param bool $readImage
     * @param string|null $imagePathTemplate
     * @return array
     * @throws \ImagickException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @inheritdoc
     */
    public static function readExcel(
        string $file,
        bool $firstLineIsHeader = true,
        bool $multiSheet = false,
        bool $readImage = false,
        ?string $imagePathTemplate = null
    ): array
    {
        $excelReader = new ExcelReader();
        $excelReader->firstLineIsHeader = $firstLineIsHeader;
        $excelReader->multiSheet = $multiSheet;
        $excelReader->readImage = $readImage;
        if ($imagePathTemplate) {
            $excelReader->imagePathTemplate = $imagePathTemplate;
        }
        return $excelReader->read($file);
    }

    /**
     * @param string $file
     * @param array $data
     * @param bool $keyAsHeader
     * @param bool $multiSheet
     * @param bool $withImage
     * @param string $adapter
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public static function writeExcel(
        string $file,
        array $data,
        bool $keyAsHeader = true,
        bool $multiSheet = false,
        bool $withImage = false,
        string $adapter = ExcelWriter::ADAPTER_PHP_SPREAD_SHEET
    ): void
    {
        $excelWriter = new ExcelWriter();
        $excelWriter->keyAsHeader = $keyAsHeader;
        $excelWriter->multiSheet = $multiSheet;
        $excelWriter->withImage = $withImage;
        $excelWriter->adapter = $adapter;
        $excelWriter->write($file, $data);
    }
}
