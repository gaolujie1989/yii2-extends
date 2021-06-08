<?php

namespace lujie\charging\models;

use lujie\alias\behaviors\MoneyAliasBehavior;
use lujie\alias\behaviors\TimestampAliasBehavior;
use lujie\alias\behaviors\UnitAliasBehavior;
use lujie\extend\db\AliasFieldTrait;
use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%shipping_table}}".
 *
 * @property int $shipping_table_id
 * @property string $carrier
 * @property string $departure
 * @property string $destination
 * @property string $zone
 * @property int $weight_g_limit
 * @property int $length_mm_limit
 * @property int $width_mm_limit
 * @property int $height_mm_limit
 * @property int $length_mm_min_limit
 * @property int $width_mm_min_limit
 * @property int $height_mm_min_limit
 * @property int $l2wh_mm_limit
 * @property int $lwh_mm_limit
 * @property int $lh_mm_limit
 * @property int $volume_mm3_limit
 * @property int $price_cent
 * @property string $currency
 * @property int $started_at
 * @property int $ended_at
 * @property int $owner_id
 *
 * @property float $price
 * @property float $weight_kg_limit
 * @property float $length_cm_limit
 * @property float $width_cm_limit
 * @property float $height_cm_limit
 * @property float $height_cm_min_limit
 * @property float $l2wh_cm_limit
 * @property float $lwh_cm_limit
 * @property float $lh_cm_limit
 * @property float $volume_l_limit
 * @property float $started_time
 * @property float $ended_time
 */
class ShippingTable extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, AliasFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shipping_table}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['carrier', 'departure', 'destination', 'zone', 'currency'], 'default', 'value' => ''],
            [['weight_g_limit', 'length_mm_limit', 'width_mm_limit', 'height_mm_limit',
                'length_mm_min_limit', 'width_mm_min_limit', 'height_mm_min_limit',
                'l2wh_mm_limit', 'lwh_mm_limit', 'lh_mm_limit', 'volume_mm3_limit',
                'price_cent', 'started_at', 'ended_at', 'owner_id'], 'default', 'value' => 0],
            [['weight_g_limit', 'length_mm_limit', 'width_mm_limit', 'height_mm_limit',
                'length_mm_min_limit', 'width_mm_min_limit', 'height_mm_min_limit',
                'l2wh_mm_limit', 'lwh_mm_limit', 'lh_mm_limit', 'volume_mm3_limit',
                'price_cent', 'started_at', 'ended_at', 'owner_id'], 'integer'],
            [['carrier', 'departure', 'zone'], 'string', 'max' => 10],
            [['destination'], 'string', 'max' => 2],
            [['currency'], 'string', 'max' => 3],
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), $this->traceableBehaviors(), [
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
                    'length_cm_min_limit' => 'length_mm_min_limit',
                    'width_cm_min_limit' => 'width_mm_min_limit',
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
            'timestamp' => [
                'class' => TimestampAliasBehavior::class,
                'aliasProperties' => [
                    'started_time' => 'started_at',
                    'ended_time' => 'ended_at',
                ]
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'shipping_table_id' => Yii::t('lujie/charging', 'Shipping Table ID'),
            'carrier' => Yii::t('lujie/charging', 'Carrier'),
            'departure' => Yii::t('lujie/charging', 'Departure'),
            'destination' => Yii::t('lujie/charging', 'Destination'),
            'zone' => Yii::t('lujie/charging', 'Zone'),
            'weight_g_limit' => Yii::t('lujie/charging', 'Weight G Limit'),
            'length_mm_limit' => Yii::t('lujie/charging', 'Length Mm Limit'),
            'width_mm_limit' => Yii::t('lujie/charging', 'Width Mm Limit'),
            'height_mm_limit' => Yii::t('lujie/charging', 'Height Mm Limit'),
            'length_mm_min_limit' => Yii::t('lujie/charging', 'Length Mm Min Limit'),
            'width_mm_min_limit' => Yii::t('lujie/charging', 'Width Mm Min Limit'),
            'height_mm_min_limit' => Yii::t('lujie/charging', 'Height Mm Min Limit'),
            'l2wh_mm_limit' => Yii::t('lujie/charging', 'L2wh Mm Limit'),
            'lwh_mm_limit' => Yii::t('lujie/charging', 'Lwh Mm Limit'),
            'lh_mm_limit' => Yii::t('lujie/charging', 'Lh Mm Limit'),
            'volume_mm3_limit' => Yii::t('lujie/charging', 'Volume Mm3 Limit'),
            'price_cent' => Yii::t('lujie/charging', 'Price Cent'),
            'currency' => Yii::t('lujie/charging', 'Currency'),
            'started_at' => Yii::t('lujie/charging', 'Started At'),
            'ended_at' => Yii::t('lujie/charging', 'Ended At'),
            'owner_id' => Yii::t('lujie/charging', 'Owner ID'),
        ];
    }

    /**
     * @return ShippingTableQuery
     * @inheritdoc
     */
    public static function find(): ShippingTableQuery
    {
        return new ShippingTableQuery(static::class);
    }
}
