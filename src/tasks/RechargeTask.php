<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\tasks;

use lujie\charging\Charger;
use lujie\charging\models\ChargePrice;
use lujie\scheduling\CronTask;
use yii\di\Instance;

/**
 * Class RechargeTask
 * @package lujie\charging\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RechargeTask extends CronTask
{
    /**
     * @var Charger
     */
    public $charger = 'charger';

    /**
     * @var array
     */
    public $rechargePriceCondition = [
        'status' => ChargePrice::STATUS_FAILED
    ];

    /**
     * @return bool
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function execute(): bool
    {
        $this->charger = Instance::ensure($this->charger, Charger::class);
        $query = ChargePrice::find()->andWhere($this->rechargePriceCondition);
        /** @var ChargePrice $chargePrice */
        foreach ($query->each() as $chargePrice) {
            $recalculate = $this->charger->recalculate($chargePrice);
            if ($recalculate === false) {
                $chargePrice->delete();
            }
        }
        return true;
    }
}
