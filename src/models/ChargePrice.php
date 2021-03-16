<?php

namespace lujie\charging\models;

use lujie\alias\behaviors\AliasPropertyBehavior;
use lujie\alias\behaviors\MoneyAliasBehavior;
use lujie\extend\db\AliasFieldTrait;
use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%charge_price}}".
 *
 * @property int $charge_price_id
 * @property string $charge_group
 * @property string $charge_type
 * @property string $custom_type
 * @property string $model_type
 * @property int $model_id
 * @property int $parent_model_id
 * @property int $price_table_id
 * @property int $price_cent
 * @property int $qty
 * @property int $subtotal_cent
 * @property int $discount_cent
 * @property int $surcharge_cent
 * @property int $grand_total_cent
 * @property string $currency
 * @property int $status
 * @property string $note
 * @property array|null $additional
 * @property int $owner_id
 *
 * @property string $error;
 */
class ChargePrice extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, AliasFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    public const STATUS_ESTIMATE = 0;
    public const STATUS_GENERATED = 10;
    public const STATUS_CANCELLED = 11;
    public const STATUS_FAILED = 12;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%charge_price}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['charge_type', 'model_type', 'model_id'], 'required'],
            [['charge_group', 'custom_type', 'currency', 'note'], 'default', 'value' => ''],
            [['parent_model_id', 'price_table_id', 'price_cent', 'qty', 'subtotal_cent', 'discount_cent', 'surcharge_cent', 'grand_total_cent', 'status', 'owner_id'], 'default', 'value' => 0],
            [['additional'], 'default', 'value' => []],
            [['model_id', 'parent_model_id', 'price_table_id',
                'price_cent', 'qty', 'subtotal_cent', 'discount_cent', 'surcharge_cent', 'grand_total_cent',
                'status', 'owner_id'], 'integer'],
            [['additional'], 'safe'],
            [['charge_group', 'charge_type', 'custom_type', 'model_type'], 'string', 'max' => 50],
            [['currency'], 'string', 'max' => 3],
            [['note'], 'string', 'max' => 1000],
            [['model_id', 'model_type', 'charge_type'], 'unique', 'targetAttribute' => ['model_id', 'model_type', 'charge_type']],
            [['discountPriceCent', 'discountPercent'], 'integer']
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
                    'subtotal' => 'subtotal_cent',
                    'discount' => 'discount_cent',
                    'surcharge' => 'surcharge_cent',
                    'grand_total' => 'grand_total_cent',
                ]
            ],
            'alias' => [
                'class' => AliasPropertyBehavior::class,
                'aliasProperties' => [
                    'error' => 'additional.error',
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
            'charge_price_id' => Yii::t('lujie/charging', 'Charge Price ID'),
            'charge_group' => Yii::t('lujie/charging', 'Charge Group'),
            'charge_type' => Yii::t('lujie/charging', 'Charge Type'),
            'custom_type' => Yii::t('lujie/charging', 'Custom Type'),
            'model_type' => Yii::t('lujie/charging', 'Model Type'),
            'model_id' => Yii::t('lujie/charging', 'Model ID'),
            'parent_model_id' => Yii::t('lujie/charging', 'Parent Model ID'),
            'price_table_id' => Yii::t('lujie/charging', 'Price Table ID'),
            'price_cent' => Yii::t('lujie/charging', 'Price Cent'),
            'qty' => Yii::t('lujie/charging', 'Qty'),
            'subtotal_cent' => Yii::t('lujie/charging', 'Subtotal Cent'),
            'discount_cent' => Yii::t('lujie/charging', 'Discount Cent'),
            'surcharge_cent' => Yii::t('lujie/charging', 'Surcharge Cent'),
            'grand_total_cent' => Yii::t('lujie/charging', 'Grant Total Cent'),
            'currency' => Yii::t('lujie/charging', 'Currency'),
            'status' => Yii::t('lujie/charging', 'Status'),
            'note' => Yii::t('lujie/charging', 'Note'),
            'additional' => Yii::t('lujie/charging', 'Additional'),
            'owner_id' => Yii::t('lujie/charging', 'Owner ID'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ChargePriceQuery the active query used by this AR class.
     */
    public static function find(): ActiveQuery
    {
        return new ChargePriceQuery(static::class);
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
            'subtotal' => 'subtotal',
            'discount' => 'discount',
            'surcharge' => 'surcharge',
            'grand_total' => 'grand_total',
        ]);
    }

    /**
     * @param bool $insert
     * @return bool
     * @inheritdoc
     */
    public function beforeSave($insert): bool
    {
        $this->calculateTotal();
        $this->setChargePriceStatus();
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    protected function calculateTotal(): void
    {
        if (empty($this->discount_cent)) {
            $this->discount_cent = 0;
        }
        if (empty($this->surcharge_cent)) {
            $this->surcharge_cent = 0;
        }
        $this->subtotal_cent = $this->price_cent * $this->qty;
        $this->grand_total_cent = $this->subtotal_cent - $this->discount_cent + $this->surcharge_cent;
    }

    /**
     * @inheritdoc
     */
    protected function setChargePriceStatus(): void
    {
        if (empty($this->price_table_id)) {
            $this->status = self::STATUS_FAILED;
            $this->price_table_id = 0;
            $this->price_cent = 0;
            $this->currency = '';
        } else if ($this->status === self::STATUS_FAILED) {
            $this->status = self::STATUS_ESTIMATE;
        }
    }

    /**
     * @param int $priceCent
     * @inheritdoc
     */
    public function setDiscountPriceCent($priceCent): void
    {
        if (is_numeric($priceCent)) {
            $this->discount_cent = $priceCent * $this->qty;
        }
    }

    /**
     * @param int $percent
     * @inheritdoc
     */
    public function setDiscountPercent($percent): void
    {
        if (is_numeric($percent)) {
            $this->discount_cent = (int)round($this->price_cent * $this->qty * $percent / 100, 0, PHP_ROUND_HALF_DOWN);
        }
    }
}
