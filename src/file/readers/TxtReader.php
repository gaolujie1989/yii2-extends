<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\file\readers;

use lujie\data\exchange\file\FileReaderInterface;
use yii\base\BaseObject;

/**
 * Class CsvParser
 * @package lujie\data\exchange\parsers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TxtReader extends BaseObject implements FileReaderInterface
{
    /**
     * @param string $file
     * @return array
     * @inheritdoc
     */
    public function read(string $file): array
    {
        return file($file);
    }
}
