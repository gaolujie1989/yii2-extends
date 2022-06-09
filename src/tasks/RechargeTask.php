<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\tasks;

use lujie\charging\Charger;
use lujie\charging\models\ChargePrice;
use lujie\executing\ProgressInterface;
use lujie\executing\ProgressTrait;
use lujie\scheduling\CronTask;
use yii\di\Instance;

/**
 * Class RechargeTask
 * @package lujie\charging\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RechargeTask extends CronTask implements ProgressInterface
{
    use ProgressTrait;

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
     * @var string
     */
    public $createdAtFrom = '-60 days';

    /**
     * @return \Generator
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function execute(): \Generator
    {
        $this->charger = Instance::ensure($this->charger, Charger::class);
        $query = ChargePrice::find()
            ->andWhere(['>', 'created_at', strtotime($this->createdAtFrom)])
            ->andWhere($this->rechargePriceCondition);
        /** @var ChargePrice $chargePrice */
        $progress = $this->getProgress((int)$query->count());
        foreach ($query->each() as $chargePrice) {
            $recalculate = $this->charger->recalculate($chargePrice);
            if ($recalculate === false) {
                $chargePrice->delete();
            }
            $progress->done++;
            yield true;
        }
    }
}
