<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\file\readers;

use yii\helpers\Json;

/**
 * Class JsonReader
 * @package lujie\extend\file\readers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class JsonReader extends BaseFileReader
{
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
