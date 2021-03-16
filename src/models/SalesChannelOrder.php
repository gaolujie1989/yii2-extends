<?php

namespace lujie\sales\channel\models;

use lujie\extend\db\AliasFieldTrait;
use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%sales_channel_order}}".
 *
 * @property int $sales_channel_order_id
 * @property int $sales_channel_account_id
 * @property int $sales_channel_status
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
class SalesChannelOrder extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, AliasFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%sales_channel_order}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['sales_channel_status', 'order_id', 'order_status', 'order_updated_at', 'external_created_at', 'external_updated_at', 'order_pushed_at', 'order_pushed_status', 'order_pulled_at'], 'default', 'value' => 0],
            [['external_order_key', 'external_order_status'], 'default', 'value' => ''],
            [['external_order_additional', 'order_pushed_result', 'additional'], 'default', 'value' => []],
            [['sales_channel_account_id'], 'required'],
            [['sales_channel_account_id', 'sales_channel_status', 'order_id', 'order_status', 'order_updated_at', 'external_created_at', 'external_updated_at', 'order_pushed_at', 'order_pushed_status', 'order_pulled_at'], 'integer'],
            [['external_order_additional', 'order_pushed_result', 'additional'], 'safe'],
            [['external_order_key'], 'string', 'max' => 50],
            [['external_order_status'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'sales_channel_order_id' => Yii::t('lujie/sales', 'Sales Channel Order ID'),
            'sales_channel_account_id' => Yii::t('lujie/sales', 'Sales Channel Account ID'),
            'sales_channel_status' => Yii::t('lujie/sales', 'Sales Channel Status'),
            'order_id' => Yii::t('lujie/sales', 'Order ID'),
            'order_status' => Yii::t('lujie/sales', 'Order Status'),
            'order_updated_at' => Yii::t('lujie/sales', 'Order Updated At'),
            'external_order_key' => Yii::t('lujie/sales', 'External Order Key'),
            'external_order_status' => Yii::t('lujie/sales', 'External Order Status'),
            'external_order_additional' => Yii::t('lujie/sales', 'External Order Additional'),
            'external_created_at' => Yii::t('lujie/sales', 'External Created At'),
            'external_updated_at' => Yii::t('lujie/sales', 'External Updated At'),
            'order_pushed_at' => Yii::t('lujie/sales', 'Order Pushed At'),
            'order_pushed_status' => Yii::t('lujie/sales', 'Order Pushed Status'),
            'order_pushed_result' => Yii::t('lujie/sales', 'Order Pushed Result'),
            'order_pulled_at' => Yii::t('lujie/sales', 'Order Pulled At'),
            'additional' => Yii::t('lujie/sales', 'Additional'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return SalesChannelOrderQuery the active query used by this AR class.
     */
    public static function find(): SalesChannelOrderQuery
    {
        return new SalesChannelOrderQuery(static::class);
    }
}
