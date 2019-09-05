<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\file\writers;

use lujie\data\exchange\file\FileWriterInterface;
use lujie\extend\helpers\ExcelHelper;
use yii\base\BaseObject;

/**
 * Class ExcelExporter
 * @package lujie\data\exchange\parsers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExcelWriter extends BaseObject implements FileWriterInterface
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
    public function write(string $file, array $data): void
    {
        ExcelHelper::writeExcel($file, $data, $this->keyAsHeader, $this->multiSheet);
    }
}
