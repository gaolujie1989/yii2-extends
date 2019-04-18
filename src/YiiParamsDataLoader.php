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
class YiiParamsDataLoader extends ArrayDataLoader implements DataLoaderInterface
{
    /**
     * @var string
     */
    public $paramKey;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->data = ArrayHelper::getValue(Yii::$app->params, $this->paramKey, []);
    }
}
