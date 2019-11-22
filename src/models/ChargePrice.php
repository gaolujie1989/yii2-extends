<?php

namespace lujie\charging\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

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
 * @property int $grant_total_cent
 * @property string $currency
 * @property int $status
 * @property string $note
 * @property array $additional
 * @property int $owner_id
 */
class ChargePrice extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    public const STATUS_ESTIMATE = 0;
    public const STATUS_GENERATED = 10;
    public const STATUS_CANCELLED = 110;

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
            [['charge_price_id', 'model_id', 'parent_model_id', 'price_table_id',
                'price_cent', 'qty', 'subtotal_cent', 'discount_cent', 'surcharge_cent', 'grant_total_cent',
                'status', 'owner_id', ], 'integer'],
            [['charge_type', 'model_type', 'model_id'], 'required'],
            [['additional'], 'safe'],
            [['charge_group', 'charge_type', 'custom_type', 'model_type'], 'string', 'max' => 50],
            [['currency'], 'string', 'max' => 3],
            [['note'], 'string', 'max' => 1000],
            [['model_id', 'model_type', 'charge_type'], 'unique', 'targetAttribute' => ['model_id', 'model_type', 'charge_type']],
        ];
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
            'grant_total_cent' => Yii::t('lujie/charging', 'Grant Total Cent'),
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
    public static function find(): ChargePriceQuery
    {
        return new ChargePriceQuery(static::class);
    }

    /**
     * @param bool $insert
     * @return bool
     * @inheritdoc
     */
    public function beforeSave($insert): void
    {
        $this->calculateTotal();
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
        $this->grant_total_cent = $this->subtotal_cent - $this->discount_cent + $this->surcharge_cent;
    }
}
