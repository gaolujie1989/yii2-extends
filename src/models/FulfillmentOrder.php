<?php

namespace lujie\fulfillment\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%fulfillment_order}}".
 *
 * @property int $fulfillment_order_id
 * @property int $fulfillment_account_id
 * @property int $fulfillment_status
 * @property int $order_id
 * @property int $order_status
 * @property int $order_updated_at
 * @property string $external_order_key
 * @property string $external_order_status
 * @property array|null $external_order_additional
 * @property int $external_created_at
 * @property int $external_updated_at
 * @property int $order_pushed_at
 * @property int $order_pushed_status
 * @property array|null $order_pushed_result
 * @property int $order_pulled_at
 * @property array|null $additional
 */
class FulfillmentOrder extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%fulfillment_order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['fulfillment_account_id', 'fulfillment_status', 'order_id', 'order_status', 'order_updated_at', 'external_created_at', 'external_updated_at', 'order_pushed_at', 'order_pushed_status', 'order_pulled_at'], 'default', 'value' => 0],
            [['external_order_key', 'external_order_status'], 'default', 'value' => ''],
            [['external_order_additional', 'order_pushed_result', 'additional'], 'default', 'value' => []],
            [['fulfillment_account_id', 'fulfillment_status', 'order_id', 'order_status', 'order_updated_at', 'external_created_at', 'external_updated_at', 'order_pushed_at', 'order_pushed_status', 'order_pulled_at'], 'integer'],
            [['external_order_additional', 'order_pushed_result', 'additional'], 'safe'],
            [['external_order_key'], 'string', 'max' => 50],
            [['external_order_status'], 'string', 'max' => 20],
            [['order_id', 'fulfillment_account_id'], 'unique', 'targetAttribute' => ['order_id', 'fulfillment_account_id']],
            [['external_order_key', 'fulfillment_account_id'], 'unique', 'targetAttribute' => ['external_order_key', 'fulfillment_account_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'fulfillment_order_id' => Yii::t('lujie/fulfillment', 'Fulfillment Order ID'),
            'fulfillment_account_id' => Yii::t('lujie/fulfillment', 'Fulfillment Account ID'),
            'fulfillment_status' => Yii::t('lujie/fulfillment', 'Fulfillment Status'),
            'order_id' => Yii::t('lujie/fulfillment', 'Order ID'),
            'order_status' => Yii::t('lujie/fulfillment', 'Order Status'),
            'order_updated_at' => Yii::t('lujie/fulfillment', 'Order Updated At'),
            'external_order_key' => Yii::t('lujie/fulfillment', 'External Order Key'),
            'external_order_status' => Yii::t('lujie/fulfillment', 'External Order Status'),
            'external_order_additional' => Yii::t('lujie/fulfillment', 'External Order Additional'),
            'external_created_at' => Yii::t('lujie/fulfillment', 'External Created At'),
            'external_updated_at' => Yii::t('lujie/fulfillment', 'External Updated At'),
            'order_pushed_at' => Yii::t('lujie/fulfillment', 'Order Pushed At'),
            'order_pushed_status' => Yii::t('lujie/fulfillment', 'Order Pushed Status'),
            'order_pushed_result' => Yii::t('lujie/fulfillment', 'Order Pushed Result'),
            'order_pulled_at' => Yii::t('lujie/fulfillment', 'Order Pulled At'),
            'additional' => Yii::t('lujie/fulfillment', 'Additional'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return FulfillmentOrderQuery the active query used by this AR class.
     */
    public static function find(): FulfillmentOrderQuery
    {
        return new FulfillmentOrderQuery(static::class);
    }
}
