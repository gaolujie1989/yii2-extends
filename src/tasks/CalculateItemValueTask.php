<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tasks;

use lujie\fulfillment\ItemValueCalculator;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\scheduling\CronTask;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class CalculateItemValueTask
 * @package lujie\fulfillment\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CalculateItemValueTask extends CronTask
{
    /**
     * @var string
     */
    public $movementDateFrom = '-2 days';

    /**
     * @var string
     */
    public $movementDateTo = '-1 days';

    /**
     * @var ItemValueCalculator
     */
    public $itemValueCalculator;

    /**
     * @return bool
     * @throws InvalidConfigException
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function execute(): bool
    {
        $this->itemValueCalculator = Instance::ensure($this->itemValueCalculator, ItemValueCalculator::class);
        $movementDateFrom = date('Y-m-d', strtotime($this->movementDateFrom));
        $movementDateTo = date('Y-m-d', strtotime($this->movementDateTo));
        $fulfillmentWarehouses = FulfillmentWarehouse::find()->supportMovement()->all();
        foreach ($fulfillmentWarehouses as $fulfillmentWarehouse) {
            $warehouseId = $fulfillmentWarehouse->warehouse_id;
            if (empty($warehouseId)) {
                continue;
            }
            $this->itemValueCalculator->calculateMovementsItemValues($warehouseId, $movementDateFrom, $movementDateTo);
        }
        return true;
    }
}
