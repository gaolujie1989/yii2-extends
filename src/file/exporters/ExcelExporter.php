<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\file\exporters;

use lujie\data\exchange\file\FileExporterInterface;
use lujie\extend\helpers\ExcelHelper;

/**
 * Class ExcelExporter
 * @package lujie\data\exchange\parsers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExcelExporter implements FileExporterInterface
{
    public $keyAsHeader = true;

    public $multiSheet = false;

    /**
     * @param string $file
     * @param array $data
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @inheritdoc
     */
    public function exportToFile(string $file, array $data): void
    {
        ExcelHelper::writeExcel($file, $data, $this->keyAsHeader, $this->multiSheet);
    }
}
