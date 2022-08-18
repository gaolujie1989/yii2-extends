<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\writers;

use lujie\extend\file\FileWriterInterface;
use yii\base\BaseObject;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\helpers\VarDumper;

/**
 * Class JsonWriter
 * @package lujie\extend\file\writers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class JsonWriter extends BaseObject implements FileWriterInterface
{
    /**
     * @param string $file
     * @param array $data
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function write(string $file, array $data): void
    {
        FileHelper::createDirectory(dirname($file));
        file_put_contents($file, Json::encode($data));
    }
}
