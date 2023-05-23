<?php

namespace lujie\sales\channel\models;

use Yii;

/**
 * This is the model class for table "sales_channel_order_data".
 *
 * @property int $sales_channel_order_data_id
 * @property int $sales_channel_order_id
 * @property array|null $order_data
 */
class SalesChannelOrderData extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'sales_channel_order_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['sales_channel_order_id'], 'default', 'value' => 0],
            [['order_data'], 'default', 'value' => []],
            [['sales_channel_order_id'], 'integer'],
            [['order_data'], 'safe'],
            [['sales_channel_order_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'sales_channel_order_data_id' => Yii::t('lujie/salesChannel', 'Sales Channel Order Data ID'),
            'sales_channel_order_id' => Yii::t('lujie/salesChannel', 'Sales Channel Order ID'),
            'order_data' => Yii::t('lujie/salesChannel', 'Order Data'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return SalesChannelOrderDataQuery the active query used by this AR class.
     */
    public static function find(): SalesChannelOrderDataQuery
    {
        return new SalesChannelOrderDataQuery(static::class);
    }
}
