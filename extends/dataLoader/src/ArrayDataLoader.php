<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

use yii\helpers\ArrayHelper;

/**
 * Class ArrayLoader
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ArrayDataLoader extends BaseDataLoader
{
    public $data = [];

    /**
     * @param int|string $key
     * @return array|mixed|null
     * @inheritdoc
     */
    public function get($key)
    {
        return ArrayHelper::getValue($this->data, $key);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function all(): ?array
    {
        return $this->data;
    }
}
