<?php

namespace lujie\charging\models;

use Yii;

/**
 * This is the model class for table "{{%shipping_zone}}".
 *
 * @property int $shipping_zone_id
 * @property string $carrier
 * @property string $departure
 * @property string $destination
 * @property string $zone
 * @property string $postal_code_from
 * @property string $postal_code_to
 * @property int $started_at
 * @property int $ended_at
 * @property int $owner_id
 */
class ShippingZone extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%shipping_zone}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['carrier', 'departure', 'destination', 'zone', 'postal_code_from'], 'default', 'value' => ''],
            [['postal_code_to', 'started_at', 'ended_at', 'owner_id'], 'default', 'value' => 0],
            [['started_at', 'ended_at', 'owner_id'], 'integer'],
            [['carrier', 'departure', 'zone'], 'string', 'max' => 10],
            [['destination'], 'string', 'max' => 2],
            [['postal_code_from', 'postal_code_to'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'shipping_zone_id' => Yii::t('lujie/charging', 'Shipping Zone ID'),
            'carrier' => Yii::t('lujie/charging', 'Carrier'),
            'departure' => Yii::t('lujie/charging', 'Departure'),
            'destination' => Yii::t('lujie/charging', 'Destination'),
            'zone' => Yii::t('lujie/charging', 'Zone'),
            'postal_code_from' => Yii::t('lujie/charging', 'Postal Code From'),
            'postal_code_to' => Yii::t('lujie/charging', 'Postal Code To'),
            'started_at' => Yii::t('lujie/charging', 'Started At'),
            'ended_at' => Yii::t('lujie/charging', 'Ended At'),
            'owner_id' => Yii::t('lujie/charging', 'Owner ID'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ShippingZoneQuery the active query used by this AR class.
     */
    public static function find(): ShippingZoneQuery
    {
        return new ShippingZoneQuery(static::class);
    }
}
