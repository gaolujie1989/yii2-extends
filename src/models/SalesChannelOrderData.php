<?php

namespace lujie\sales\channel\models;

use Yii;

/**
 * This is the model class for table "sales_channel_order_data".
 *
 * @property int $sales_channel_order_data_id
 * @property int $sales_channel_account_id
 * @property string $external_order_key
 * @property string $external_order_no
 * @property int $external_created_at
 * @property int $external_updated_at
 * @property resource|null $order_data
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
            [['sales_channel_account_id', 'external_created_at', 'external_updated_at'], 'default', 'value' => 0],
            [['external_order_key', 'external_order_no'], 'default', 'value' => ''],
            [['sales_channel_account_id', 'external_created_at', 'external_updated_at'], 'integer'],
            [['order_data'], 'string'],
            [['external_order_key', 'external_order_no'], 'string', 'max' => 50],
            [['external_order_key', 'sales_channel_account_id'], 'unique', 'targetAttribute' => ['external_order_key', 'sales_channel_account_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'sales_channel_order_data_id' => Yii::t('lujie/salesChannel', 'Sales Channel Order Data ID'),
            'sales_channel_account_id' => Yii::t('lujie/salesChannel', 'Sales Channel Account ID'),
            'external_order_key' => Yii::t('lujie/salesChannel', 'External Order Key'),
            'external_order_no' => Yii::t('lujie/salesChannel', 'External Order No'),
            'external_created_at' => Yii::t('lujie/salesChannel', 'External Created At'),
            'external_updated_at' => Yii::t('lujie/salesChannel', 'External Updated At'),
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
