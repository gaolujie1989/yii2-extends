<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging;


use lujie\charging\models\ChargePrice;
use yii\base\BaseObject;
use yii\db\BaseActiveRecord;

/**
 * Class BaseChargeCalculator
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseChargeCalculator extends BaseObject implements ChargeCalculatorInterface
{
    public function calculate(BaseActiveRecord $model, string $modelType, string $chargeType): ChargePrice
    {
        $chargePrice = ChargePrice::find()
            ->modelId($model->getPrimaryKey())
            ->modelType($modelType)
            ->chargeType($chargeType)
            ->one();
        if ($chargePrice !== null) {
            return $chargePrice;
        }


    }
}
