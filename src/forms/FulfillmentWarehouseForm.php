<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\forms;

use lujie\alias\behaviors\TimestampAliasBehavior;
use lujie\extend\db\FormTrait;
use lujie\fulfillment\models\FulfillmentWarehouse;

/**
 * Class FulfillmentWarehouseForm
 * @package lujie\fulfillment\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentWarehouseForm extends FulfillmentWarehouse
{
    use FormTrait;

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'tsAlias' => [
                'class' => TimestampAliasBehavior::class,
                'aliasProperties' => [
                    'external_movement_time' => 'external_movement_at',
                ]
            ]
        ]);
    }
}
