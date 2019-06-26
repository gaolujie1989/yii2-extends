<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\order\center;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class Module
 * @package lujie\sales\order\center
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Module extends \lujie\extend\base\Module
{
    /**
     * @return string
     * @inheritdoc
     */
    public static function getSystemCurrency(): string
    {
        return ArrayHelper::getValue(Yii::$app->params, 'sales.systemCurrency', 'EUR');
    }
}
