<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\file\writers;

use lujie\data\exchange\file\FileWriterInterface;
use yii\base\BaseObject;

/**
 * Class TxtWriter
 * @package lujie\data\exchange\file\writers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TxtWriter extends BaseObject implements FileWriterInterface
{
    /**
     * @param string $file
     * @param array $data
     * @inheritdoc
     */
    public function write(string $file, array $data): void
    {
        file_put_contents($file, implode("\n", $data));
    }
}
