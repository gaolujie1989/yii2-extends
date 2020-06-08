<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\readers;

use lujie\extend\file\FileReaderInterface;
use yii\base\BaseObject;

/**
 * Class PhpReader
 * @package lujie\extend\file\readers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PhpReader extends BaseObject implements FileReaderInterface
{
    /**
     * @param string $file
     * @return array
     * @inheritdoc
     */
    public function read(string $file): array
    {
        if (file_exists($file)) {
            return (array) require($file);
        }
        return [];
    }
}
