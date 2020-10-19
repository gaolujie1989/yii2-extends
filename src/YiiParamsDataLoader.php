<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\loader;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class YiiParamsDataLoader
 * @package lujie\data\loader
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class YiiParamsDataLoader extends BaseDataLoader
{
    /**
     * @var string
     */
    public $paramKey;

    /**
     * @param int|string $key
     * @return array|mixed|null
     * @inheritdoc
     */
    public function get($key)
    {
        $data = ArrayHelper::getValue(Yii::$app->params, $this->paramKey, []);
        return ArrayHelper::getValue($data, $key);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function all(): ?array
    {
        return ArrayHelper::getValue(Yii::$app->params, $this->paramKey, []);
    }
}
