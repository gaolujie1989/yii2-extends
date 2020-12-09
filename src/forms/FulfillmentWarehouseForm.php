<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\forms;

use lujie\alias\behaviors\TimestampAliasBehavior;
use lujie\extend\helpers\ModelHelper;
use lujie\fulfillment\models\FulfillmentWarehouse;

/**
 * Class FulfillmentWarehouseForm
 * @package lujie\fulfillment\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FulfillmentWarehouseForm extends FulfillmentWarehouse
{
    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = parent::rules();
        ModelHelper::removeAttributesRules($rules, 'external_movement_at');
        return array_merge($rules, [
            [['external_movement_time'], 'safe']
        ]);
    }

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

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return array_merge(parent::fields(), [
            'external_fulfillment_time' => 'external_fulfillment_time',
        ]);
    }
}