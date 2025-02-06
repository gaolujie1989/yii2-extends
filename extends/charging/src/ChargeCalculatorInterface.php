<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging;

use lujie\charging\models\ChargePrice;
use yii\db\BaseActiveRecord;

/**
 * Interface ChargeCalculatorInterface
 * @package lujie\charging
 */
interface ChargeCalculatorInterface
{
    /**
     * @param BaseActiveRecord $model
     * @return CalculatedPrice[]
     * @inheritdoc
     */
    public function calculate(BaseActiveRecord $model): array;
}
