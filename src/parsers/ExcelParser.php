<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\parsers;

use lujie\extend\helpers\ExcelHelper;

/**
 * Class ExcelParser
 * @package lujie\data\exchange\parsers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExcelParser implements ParserInterface
{
    public $firstLineIsHeader = true;

    /**
     * @param string $file
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @inheritdoc
     */
    public function parse(string $file): array
    {
        return ExcelHelper::readExcel($file, $this->firstLineIsHeader);
    }
}
