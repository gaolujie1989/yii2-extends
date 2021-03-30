<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\tests\unit\mocks;

use lujie\charging\ChargeCalculatorInterface;
use lujie\charging\models\ChargePrice;
use yii\base\BaseObject;
use yii\db\BaseActiveRecord;

/**
 * Class MockTableCalculator
 * @package lujie\charging\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockCalculator extends BaseObject implements ChargeCalculatorInterface
{
    public static $mockChargePrices = [
        'MOCK_CHARGE1' => [
            [
                'custom_type' => 'C1',
                'qty' => 1,
                'owner_id' => 1,
                'parent_model_id' => 0,
                'price_table_id' => 1,
                'price_cent' => 123,
                'currency' => 'CNY',
            ]
        ]
    ];

    public function calculate(BaseActiveRecord $model, ChargePrice $chargePrice): ChargePrice
    {
        $chargePrice->setAttributes(array_shift(static::$mockChargePrices[$chargePrice->charge_type]));
        return $chargePrice;
    }
}
