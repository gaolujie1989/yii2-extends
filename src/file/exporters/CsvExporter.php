<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\file\exporters;

use lujie\data\exchange\file\FileExporterInterface;
use lujie\extend\helpers\CsvHelper;

/**
 * Class CsvParser
 * @package lujie\data\exchange\parsers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CsvExporter implements FileExporterInterface
{
    public $keyAsHeader = true;

    /**
     * @param string $file
     * @param array $data
     * @inheritdoc
     */
    public function exportToFile(string $file, array $data): void
    {
        CsvHelper::writeCsv($file, $data, $this->keyAsHeader);
    }
}
