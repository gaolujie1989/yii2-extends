<?php

namespace lujie\charging\models;

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
 * @property int $width_mm_limit
 * @property int $height_mm_limit
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
            [['weight_g_limit', 'length_mm_limit', 'width_mm_limit', 'height_mm_limit', 'height_mm_min_limit',
                'l2wh_mm_limit', 'lwh_mm_limit', 'lh_mm_limit', 'volume_mm3_limit',
                'price_cent', 'started_at', 'ended_at', 'owner_id'], 'default', 'value' => 0],
            [['weight_g_limit', 'length_mm_limit', 'width_mm_limit', 'height_mm_limit', 'height_mm_min_limit',
                'l2wh_mm_limit', 'lwh_mm_limit', 'lh_mm_limit', 'volume_mm3_limit',
                'price_cent', 'started_at', 'ended_at', 'owner_id'], 'integer'],
            [['carrier'], 'string', 'max' => 10],
            [['departure', 'destination'], 'string', 'max' => 2],
            [['currency'], 'string', 'max' => 3],
        ];
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
            'weight_g_limit' => Yii::t('lujie/charging', 'Weight G Limit'),
            'length_mm_limit' => Yii::t('lujie/charging', 'Length Mm Limit'),
            'width_mm_limit' => Yii::t('lujie/charging', 'Width Mm Limit'),
            'height_mm_limit' => Yii::t('lujie/charging', 'Height Mm Limit'),
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
