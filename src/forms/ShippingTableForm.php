<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\forms;


use lujie\alias\behaviors\MoneyAliasBehavior;
use lujie\alias\behaviors\TimestampAliasBehavior;
use lujie\alias\behaviors\UnitAliasBehavior;
use lujie\charging\models\ShippingTable;
use lujie\extend\base\FormTrait;

/**
 * Class ShippingTableForm
 * @package lujie\charging\forms
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShippingTableForm extends ShippingTable
{
    use FormTrait;

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'money' => [
                'class' => MoneyAliasBehavior::class,
                'aliasProperties' => [
                    'price' => 'price_cent',
                ]
            ],
            'unitWeight' => [
                'class' => UnitAliasBehavior::class,
                'baseUnit' => 'g',
                'displayUnit' => 'kg',
                'aliasProperties' => [
                    'weight_kg_limit' => 'weight_g_limit',
                ]
            ],
            'unitSize' => [
                'class' => UnitAliasBehavior::class,
                'baseUnit' => 'mm',
                'displayUnit' => 'cm',
                'aliasProperties' => [
                    'length_cm_limit' => 'length_mm_limit',
                    'width_cm_limit' => 'width_mm_limit',
                    'height_cm_limit' => 'height_mm_limit',
                    'height_cm_min_limit' => 'height_mm_min_limit',
                    'l2wh_cm_limit' => 'l2wh_mm_limit',
                    'lwh_cm_limit' => 'lwh_mm_limit',
                    'lh_cm_limit' => 'lh_mm_limit',
                ]
            ],
            'unitVolume' => [
                'class' => UnitAliasBehavior::class,
                'baseUnit' => 'mm3',
                'displayUnit' => 'dm3',
                'aliasProperties' => [
                    'volume_l_limit' => 'volume_mm3_limit',
                ]
            ],
            'timestampAlias' => [
                'class' => TimestampAliasBehavior::class,
                'aliasProperties' => [
                    'started_time' => 'started_at',
                    'ended_time' => 'ended_at',
                ]
            ]
        ]);
    }
}
