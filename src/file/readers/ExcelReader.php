<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\file\readers;

use lujie\data\exchange\file\FileReaderInterface;
use lujie\extend\helpers\ExcelHelper;
use yii\base\BaseObject;

/**
 * Class ExcelExporter
 * @package lujie\data\exchange\parsers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExcelReader extends BaseObject implements FileReaderInterface
{
    public $firstLineIsHeader = true;

    public $multiSheet = false;

    /**
     * @param string $file
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @inheritdoc
     */
    public function read(string $file): array
    {
        return ExcelHelper::readExcel($file, $this->firstLineIsHeader, $this->multiSheet);
    }
}
