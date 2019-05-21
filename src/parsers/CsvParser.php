<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\parsers;

use lujie\extend\helpers\CsvHelper;

/**
 * Class CsvParser
 * @package lujie\data\exchange\parsers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CsvParser implements ParserInterface
{
    public $firstLineIsHeader = true;

    /**
     * @param string $file
     * @return array
     * @inheritdoc
     */
    public function parse(string $file): array
    {
        return CsvHelper::readCsv($file, $this->firstLineIsHeader);
    }
}
