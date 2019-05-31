<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\file\parsers;

use lujie\data\exchange\file\FileParserInterface;
use lujie\extend\helpers\ExcelHelper;
use yii\base\BaseObject;

/**
 * Class ExcelExporter
 * @package lujie\data\exchange\parsers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExcelParser extends BaseObject implements FileParserInterface
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
    public function parseFile(string $file): array
    {
        return ExcelHelper::readExcel($file, $this->firstLineIsHeader, $this->multiSheet);
    }
}
