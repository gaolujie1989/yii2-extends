<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

/**
 * Class ArrayLoader
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ArrayDataLoader extends BaseObject implements DataLoaderInterface
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
    public function all()
    {
        return $this->data;
    }
}
