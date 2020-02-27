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
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @inheritdoc
     */
    public static function readExcel(string $file, bool $firstLineIsHeader = true, bool $multiSheet = false): array
    {
        $excelReader = new ExcelReader();
        $excelReader->firstLineIsHeader = $firstLineIsHeader;
        $excelReader->multiSheet = $multiSheet;
        return $excelReader->read($file);
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
        $excelWriter = new ExcelWriter();
        $excelWriter->keyAsHeader = $keyAsHeader;
        $excelWriter->multiSheet = $multiSheet;
        $excelWriter->write($file, $data);
    }
}
