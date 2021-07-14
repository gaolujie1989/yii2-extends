<?php

namespace lujie\fulfillment\models;

use lujie\fulfillment\constants\FulfillmentConst;
use Yii;

/**
 * This is the model class for table "{{%fulfillment_order}}".
 *
 * @property int $fulfillment_order_id
 * @property int $fulfillment_account_id
 * @property int $fulfillment_status
 * @property string $fulfillment_type
 * @property int $order_id
 * @property string $order_status
 * @property int $order_updated_at
 * @property int $warehouse_id
 * @property string $external_order_key
 * @property string $external_order_status
 * @property array|null $external_order_additional
 * @property int $external_created_at
 * @property int $external_updated_at
 * @property string $external_warehouse_key
 * @property int $order_pushed_at
 * @property int $order_pushed_status
 * @property array|null $order_pushed_result
 * @property int $order_pulled_at
 * @property int $charge_pulled_at
 * @property array|null $additional
 */
class FulfillmentOrder extends \lujie\fulfillment\base\db\ActiveRecord
{
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
            [['fulfillment_account_id', 'fulfillment_status', 'order_id', 'order_updated_at', 'warehouse_id',
                'external_created_at', 'external_updated_at', 'order_pushed_at', 'order_pushed_status',
                'order_pulled_at', 'charge_pulled_at'], 'default', 'value' => 0],
            [['order_status', 'external_order_key', 'external_order_status', 'external_warehouse_key'], 'default', 'value' => ''],
            [['external_order_additional', 'order_pushed_result', 'additional'], 'default', 'value' => []],
            [['fulfillment_account_id', 'fulfillment_status', 'order_id', 'order_updated_at', 'warehouse_id',
                'external_created_at', 'external_updated_at', 'order_pushed_at', 'order_pushed_status',
                'order_pulled_at', 'charge_pulled_at'], 'integer'],
            [['external_order_additional', 'order_pushed_result', 'additional'], 'safe'],
            [['order_status', 'external_order_status'], 'string', 'max' => 20],
            [['external_order_key', 'external_warehouse_key'], 'string', 'max' => 50],
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
            'fulfillment_type' => Yii::t('lujie/fulfillment', 'Fulfillment Type'),
            'order_id' => Yii::t('lujie/fulfillment', 'Order ID'),
            'order_status' => Yii::t('lujie/fulfillment', 'Order Status'),
            'order_updated_at' => Yii::t('lujie/fulfillment', 'Order Updated At'),
            'warehouse_id' => Yii::t('lujie/fulfillment', 'Warehouse ID'),
            'external_order_key' => Yii::t('lujie/fulfillment', 'External Order Key'),
            'external_order_status' => Yii::t('lujie/fulfillment', 'External Order Status'),
            'external_order_additional' => Yii::t('lujie/fulfillment', 'External Order Additional'),
            'external_created_at' => Yii::t('lujie/fulfillment', 'External Created At'),
            'external_updated_at' => Yii::t('lujie/fulfillment', 'External Updated At'),
            'external_warehouse_key' => Yii::t('lujie/fulfillment', 'External Warehouse Key'),
            'order_pushed_at' => Yii::t('lujie/fulfillment', 'Order Pushed At'),
            'order_pushed_status' => Yii::t('lujie/fulfillment', 'Order Pushed Status'),
            'order_pushed_result' => Yii::t('lujie/fulfillment', 'Order Pushed Result'),
            'order_pulled_at' => Yii::t('lujie/fulfillment', 'Order Pulled At'),
            'charge_pulled_at' => Yii::t('lujie/fulfillment', 'Charge Pulled At'),
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

    /**
     * @param bool $insert
     * @return bool
     * @inheritdoc
     */
    public function beforeSave($insert): bool
    {
        if (!in_array($this->fulfillment_type, FulfillmentConst::FULFILLMENT_TYPES)) {
            $this->addError('fulfillment_type', 'Invalid fulfillment type');
            return false;
        }
        return parent::beforeSave($insert);
    }
}
