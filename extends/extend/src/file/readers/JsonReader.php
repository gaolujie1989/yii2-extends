<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\readers;

use lujie\extend\file\FileReaderInterface;
use yii\base\BaseObject;
use yii\helpers\Json;

/**
 * Class JsonReader
 * @package lujie\extend\file\readers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class JsonReader extends BaseObject implements FileReaderInterface
{
    /**
     * @param string $file
     * @return array
     * @inheritdoc
     */
    public function read(string $file): array
    {
        if (file_exists($file)) {
            return $this->readContent(file_get_contents($file));
        }
        return [];
    }

    /**
     * @param string $content
     * @return array
     * @inheritdoc
     */
    public function readContent(string $content): array
    {
        return Json::decode($content);
    }
}
