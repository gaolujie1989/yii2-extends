<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\file\readers;

use lujie\data\exchange\file\FileReaderInterface;
use lujie\extend\helpers\CsvHelper;
use yii\base\BaseObject;

/**
 * Class CsvParser
 * @package lujie\data\exchange\parsers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CsvReader extends BaseObject implements FileReaderInterface
{
    public $firstLineIsHeader = true;

    public $delimiter = ',';
    public $enclosure = '"';
    public $escape = '\\';
    public $flag = true;

    /**
     * @param string $file
     * @return array
     * @inheritdoc
     */
    public function read(string $file): array
    {
        return CsvHelper::readCsv($file, $this->firstLineIsHeader, 0, $this->delimiter, $this->enclosure, $this->escape, $this->flag);
    }
}
