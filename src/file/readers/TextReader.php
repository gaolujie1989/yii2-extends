<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\readers;

use lujie\extend\file\FileReaderInterface;
use yii\base\BaseObject;

/**
 * Class TextReader
 * @package lujie\extend\file\readers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TextReader extends BaseObject implements FileReaderInterface
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

    /**
     * @param string $content
     * @return array
     * @inheritdoc
     */
    public function readContent(string $content): array
    {
        return explode("\n", $content);
    }
}
