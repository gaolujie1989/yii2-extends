<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\file\writers;

use lujie\data\exchange\file\FileWriterInterface;
use lujie\extend\helpers\CsvHelper;
use yii\base\BaseObject;

/**
 * Class CsvParser
 * @package lujie\data\exchange\parsers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CsvWriter extends BaseObject implements FileWriterInterface
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
