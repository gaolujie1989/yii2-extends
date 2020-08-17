<?php

namespace lujie\charging\models;

use lujie\alias\behaviors\AliasPropertyBehavior;
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
 * This is the model class for table "{{%charge_table}}".
 *
 * @property int $charge_table_id
 * @property string $charge_group
 * @property string $charge_type
 * @property string $custom_type
 * @property int $min_limit
 * @property int $max_limit
 * @property string $limit_unit
 * @property string $display_limit_unit
 * @property int $price_cent
 * @property string $currency
 * @property int $over_limit_price_cent
 * @property int $per_limit
 * @property int $max_over_limit
 * @property array $additional
 * @property int $started_at
 * @property int $ended_at
 * @property int $owner_id
 *
 * @property int $display_min_limit
 * @property int $display_max_limit
 * @property int $display_per_limit
 * @property int $display_min_over_limit
 * @property int $display_max_over_limit
 */
class ChargeTable extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%charge_table}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['charge_type'], 'required'],
            [['charge_group', 'custom_type', 'limit_unit', 'display_limit_unit', 'currency'], 'default', 'value' => ''],
            [['min_limit', 'max_limit', 'price_cent', 'over_limit_price_cent', 'per_limit',
                'min_over_limit', 'max_over_limit',
                'started_at', 'ended_at', 'owner_id'], 'default', 'value' => 0],
            [['min_limit', 'max_limit', 'price_cent', 'over_limit_price_cent', 'per_limit',
                'min_over_limit', 'max_over_limit',
                'started_at', 'ended_at', 'owner_id'], 'integer'],
            [['charge_group', 'charge_type', 'custom_type'], 'string', 'max' => 50],
            [['limit_unit', 'display_limit_unit'], 'string', 'max' => 10],
            [['currency'], 'string', 'max' => 3],
            [['additional'], 'safe'],
            [['price', 'over_limit_price', 'discountPercent',
                'display_min_limit', 'display_max_limit', 'display_per_limit',
                'display_min_over_limit', 'display_max_over_limit'], 'default', 'value' => 0],
            [['price', 'over_limit_price', 'discountPercent',
                'display_min_limit', 'display_max_limit', 'display_per_limit',
                'display_min_over_limit', 'display_max_over_limit'], 'number'],
            [['started_time', 'ended_time'], 'string'],
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
                    'over_limit_price' => 'over_limit_price_cent',
                ]
            ],
            'alias' => [
                'class' => AliasPropertyBehavior::class,
                'aliasProperties' => [
                    'discountPercent' => 'additional.discountPercent',
                ]
            ],
            'unit' => [
                'class' => UnitAliasBehavior::class,
                'baseUnitAttribute' => 'limit_unit',
                'displayUnitAttribute' => 'display_limit_unit',
                'aliasProperties' => [
                    'display_min_limit' => 'min_limit',
                    'display_max_limit' => 'max_limit',
                    'display_per_limit' => 'per_limit',
                    'display_min_over_limit' => 'min_over_limit',
                    'display_max_over_limit' => 'max_over_limit',
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
            'charge_rate_id' => Yii::t('lujie/charging', 'Charge Rate ID'),
            'charge_group' => Yii::t('lujie/charging', 'Charge Group'),
            'charge_type' => Yii::t('lujie/charging', 'Charge Type'),
            'custom_type' => Yii::t('lujie/charging', 'Custom Type'),
            'min_limit' => Yii::t('lujie/charging', 'Min Limit'),
            'max_limit' => Yii::t('lujie/charging', 'Max Limit'),
            'limit_unit' => Yii::t('lujie/charging', 'Limit Unit'),
            'display_limit_unit' => Yii::t('lujie/charging', 'Display Limit Unit'),
            'price_cent' => Yii::t('lujie/charging', 'Price Cent'),
            'currency' => Yii::t('lujie/charging', 'Currency'),
            'over_limit_price_cent' => Yii::t('lujie/charging', 'Over Limit Price Cent'),
            'per_limit' => Yii::t('lujie/charging', 'Per Limit'),
            'min_over_limit' => Yii::t('lujie/charging', 'Min Over Limit'),
            'max_over_limit' => Yii::t('lujie/charging', 'Max Over Limit'),
            'additional' => Yii::t('lujie/charging', 'Additional'),
            'started_at' => Yii::t('lujie/charging', 'Started At'),
            'ended_at' => Yii::t('lujie/charging', 'Ended At'),
            'owner_id' => Yii::t('lujie/charging', 'Owner ID'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ChargeTableQuery the active query used by this AR class.
     */
    public static function find(): ChargeTableQuery
    {
        return new ChargeTableQuery(static::class);
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
            'over_limit_price' => 'over_limit_price',
            'discountPercent' => 'discountPercent',
            'display_min_limit' => 'display_min_limit',
            'display_max_limit' => 'display_max_limit',
            'display_per_limit' => 'display_per_limit',
            'display_min_over_limit' => 'display_min_over_limit',
            'display_max_over_limit' => 'display_max_over_limit',
            'started_time' => 'started_time',
            'ended_time' => 'ended_time',
        ]);
    }
}
