<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\behaviors;

use lujie\charging\ChargeEvent;
use lujie\charging\Charger;
use Yii;
use yii\base\Behavior;
use yii\helpers\ArrayHelper;

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
        Yii::info("Charge {$event->modelType} {$event->model->getPrimaryKey()} with {$chargeTypes} is started, calculated: {$calculated}", __METHOD__);
    }

    /**
     * @param ChargeEvent $event
     * @inheritdoc
     */
    public function afterCharge(ChargeEvent $event): void
    {
        $chargeTypes = implode('/', $event->chargeTypes);
        $chargePrices = [];
        foreach ($event->chargePrices as $chargePrice) {
            $chargePrices[] = $chargePrice->charge_type . ':' . $chargePrice->price_cent;
        }
        $chargePrices = implode(',', $chargePrices);
        Yii::info("charge {$event->modelType} {$event->model->getPrimaryKey()} with {$chargeTypes} is finished, chargePrices: {$chargePrices}", __METHOD__);
    }
}
