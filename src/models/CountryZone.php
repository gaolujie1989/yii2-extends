<?php

namespace lujie\charging\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%country_zone}}".
 *
 * @property int $country_zone_id
 * @property string $carrier
 * @property string $zone
 * @property string $country
 * @property string $postal_code_from
 * @property string $postal_code_to
 * @property int $started_at
 * @property int $ended_at
 * @property int $owner_id
 */
class CountryZone extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%country_zone}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['carrier', 'zone', 'country', 'postal_code_from'], 'default', 'value' => ''],
            [['postal_code_to', 'started_at', 'ended_at', 'owner_id'], 'default', 'value' => 0],
            [['started_at', 'ended_at', 'owner_id'], 'integer'],
            [['carrier', 'zone'], 'string', 'max' => 10],
            [['postal_code_from', 'postal_code_to'], 'string', 'max' => 20],
            [['country'], 'string', 'max' => 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'country_zone_id' => Yii::t('lujie/charging', 'Country Zone ID'),
            'carrier' => Yii::t('lujie/charging', 'Carrier'),
            'zone' => Yii::t('lujie/charging', 'Zone'),
            'country' => Yii::t('lujie/charging', 'Country'),
            'postal_code_from' => Yii::t('lujie/charging', 'Postal Code From'),
            'postal_code_to' => Yii::t('lujie/charging', 'Postal Code To'),
            'started_at' => Yii::t('lujie/charging', 'Started At'),
            'ended_at' => Yii::t('lujie/charging', 'Ended At'),
            'owner_id' => Yii::t('lujie/charging', 'Owner ID'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return CountryZoneQuery the active query used by this AR class.
     */
    public static function find(): CountryZoneQuery
    {
        return new CountryZoneQuery(static::class);
    }
}
