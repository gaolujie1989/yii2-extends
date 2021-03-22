<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\extend\helpers;

use lujie\extend\file\readers\ExcelReader;
use lujie\extend\file\writers\ExcelWriter;
use lujie\extend\file\writers\ZipWriter;

/**
 * Class ZipHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ZipHelper
{
    /**
     * @param string $file
     * @param array $data
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @inheritdoc
     */
    public static function writeZip(string $file, array $data): void
    {
        $zipWriter = new ZipWriter();
        $zipWriter->write($file, $data);
    }
}
