<?php

namespace lujie\charging\models;

use lujie\alias\behaviors\MoneyAliasBehavior;
use lujie\alias\behaviors\TimestampAliasBehavior;
use lujie\alias\behaviors\UnitAliasBehavior;
use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
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
 * @property int $weight_g_limit
 * @property int $length_mm_limit
 * @property int $length_mm_min_limit
 * @property int $width_mm_limit
 * @property int $width_mm_min_limit
 * @property int $height_mm_limit
 * @property int $height_mm_min_limit
 * @property int $l2wh_mm_limit
 * @property int $lh_mm_limit
 * @property int $volume_mm3_limit
 * @property int $weight_volume_compare_limit MAX(weight_g, volume_m3 / 5000)
 * @property int $price_cent
 * @property string $currency
 * @property int $started_at
 * @property int $ended_at
 * @property int $owner_id
 */
class ShippingTable extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

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
            [['carrier', 'departure', 'destination', 'currency'], 'default', 'value' => ''],
            [['weight_g_limit', 'length_mm_limit', 'length_mm_min_limit', 'width_mm_limit', 'width_mm_min_limit',
                'height_mm_limit', 'height_mm_min_limit', 'l2wh_mm_limit', 'lh_mm_limit',
                'volume_mm3_limit', 'weight_volume_compare_limit',
                'price_cent', 'started_at', 'ended_at', 'owner_id'], 'default', 'value' => 0],
            [['weight_g_limit', 'length_mm_limit', 'length_mm_min_limit', 'width_mm_limit', 'width_mm_min_limit',
                'height_mm_limit', 'height_mm_min_limit', 'l2wh_mm_limit', 'lh_mm_limit',
                'volume_mm3_limit', 'weight_volume_compare_limit',
                'price_cent', 'started_at', 'ended_at', 'owner_id'], 'integer'],
            [['carrier'], 'string', 'max' => 10],
            [['currency'], 'string', 'max' => 3],
            [['departure', 'destination'], 'string', 'max' => 2],
        ];
    }

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
                    'length_cm_min_limit' => 'length_mm_min_limit',
                    'width_cm_min_limit' => 'width_mm_min_limit',
                    'height_cm_min_limit' => 'height_mm_min_limit',
                    'l2wh_cm_limit' => 'l2wh_mm_limit',
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
            'shipping_table_id' => Yii::t('lujie/option', 'Shipping Table ID'),
            'carrier' => Yii::t('lujie/option', 'Carrier'),
            'departure' => Yii::t('lujie/option', 'Departure'),
            'destination' => Yii::t('lujie/option', 'Destination'),
            'weight_g_limit' => Yii::t('lujie/option', 'Weight G Limit'),
            'length_mm_limit' => Yii::t('lujie/option', 'Length Mm Limit'),
            'length_mm_min_limit' => Yii::t('lujie/option', 'Length Mm Min Limit'),
            'width_mm_limit' => Yii::t('lujie/option', 'Width Mm Limit'),
            'width_mm_min_limit' => Yii::t('lujie/option', 'Width Mm Min Limit'),
            'height_mm_limit' => Yii::t('lujie/option', 'Height Mm Limit'),
            'height_mm_min_limit' => Yii::t('lujie/option', 'Height Mm Min Limit'),
            'l2wh_mm_limit' => Yii::t('lujie/option', 'L2wh Mm Limit'),
            'lh_mm_limit' => Yii::t('lujie/option', 'Lh Mm Limit'),
            'volume_mm3_limit' => Yii::t('lujie/option', 'Volume Mm3 Limit'),
            'weight_volume_compare_limit' => Yii::t('lujie/option', 'Weight Volume Compare Limit'),
            'price_cent' => Yii::t('lujie/option', 'Price Cent'),
            'currency' => Yii::t('lujie/option', 'Currency'),
            'started_at' => Yii::t('lujie/option', 'Started At'),
            'ended_at' => Yii::t('lujie/option', 'Ended At'),
            'owner_id' => Yii::t('lujie/option', 'Owner ID'),
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

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return array_merge(parent::fields(), [
            'id' => 'id',
            'price' => 'price',
            'weight_kg_limit' => 'weight_kg_limit',
            'length_cm_limit' => 'length_cm_limit',
            'width_cm_limit' => 'width_cm_limit',
            'height_cm_limit' => 'height_cm_limit',
            'length_cm_min_limit' => 'length_cm_min_limit',
            'width_cm_min_limit' => 'width_cm_min_limit',
            'height_cm_min_limit' => 'height_cm_min_limit',
            'l2wh_cm_limit' => 'l2wh_cm_limit',
            'lh_cm_limit' => 'lh_cm_limit',
            'volume_l_limit' => 'volume_l_limit',
            'started_time' => 'started_time',
            'ended_time' => 'ended_time',
        ]);
    }
}
