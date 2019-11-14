<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\writers;

use lujie\extend\file\FileWriterInterface;
use yii\base\BaseObject;

/**
 * Class TextWriter
 * @package lujie\extend\file\writers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TextWriter extends BaseObject implements FileWriterInterface
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
