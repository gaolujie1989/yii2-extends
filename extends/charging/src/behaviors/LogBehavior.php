<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\behaviors;

use lujie\charging\ChargeEvent;
use lujie\charging\Charger;
use Yii;
use yii\base\Behavior;

/**
 * Class LogBehavior
 * @package lujie\charging\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class LogBehavior extends Behavior
{
    /**
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            Charger::EVENT_BEFORE_CHARGE => 'beforeCharge',
            Charger::EVENT_AFTER_CHARGE => 'afterCharge',
        ];
    }

    /**
     * @param ChargeEvent $event
     * @inheritdoc
     */
    public function beforeCharge(ChargeEvent $event): void
    {
        $chargeTypes = implode('/', $event->chargeTypes);
        $calculated = $event->calculated ? 'true' : 'false';
        $message = "Charge {$event->modelType} {$event->model->getPrimaryKey()} with {$chargeTypes} started, isCalculated: {$calculated}";
        Yii::info($message, Charger::class);
    }

    /**
     * @param ChargeEvent $event
     * @inheritdoc
     */
    public function afterCharge(ChargeEvent $event): void
    {
        $chargeTypes = implode('/', $event->chargeTypes);
        $chargePrices = [];
        foreach ($event->calculatedPrices as $calculatedPrice) {
            $chargePrices[] = "Type:{$calculatedPrice->chargeType}, ID:{$calculatedPrice->priceTableId}, Price:{$calculatedPrice->priceCent}";
        }
        $chargePrices = implode(' + ', $chargePrices);
        $message = "charge {$event->modelType} {$event->model->getPrimaryKey()} with {$chargeTypes} finished, chargePrices: {$chargePrices}";
        Yii::info($message, Charger::class);
    }
}
