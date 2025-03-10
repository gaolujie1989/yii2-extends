<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\readers;

use lujie\extend\file\FileReaderInterface;
use yii\base\BaseObject;
use yii\base\NotSupportedException;

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
            return (array)require($file);
        }
        return [];
    }

    /**
     * @param string $content
     * @return array
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function readContent(string $content): array
    {
        throw new NotSupportedException('Not support read content for excel file.');
    }
}
