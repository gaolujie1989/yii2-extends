<?php

namespace lujie\sales\channel\models;

use Yii;

/**
 * This is the model class for table "{{%sales_channel_item}}".
 *
 * @property int $sales_channel_item_id
 * @property int $sales_channel_account_id
 * @property string $item_type
 * @property int $item_id
 * @property int $item_updated_at
 * @property string $external_item_key
 * @property array|null $external_item_additional
 * @property int $external_created_at
 * @property int $external_updated_at
 * @property int $item_pushed_at
 * @property int $item_pushed_status
 * @property array|null $item_pushed_result
 * @property int $stock_pushed_at
 * @property array|null $additional
 */
class SalesChannelItem extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%sales_channel_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['item_type', 'external_item_key'], 'default', 'value' => ''],
            [['item_id', 'item_updated_at', 'external_created_at', 'external_updated_at', 'item_pushed_at', 'item_pushed_status', 'stock_pushed_at'], 'default', 'value' => 0],
            [['external_item_additional', 'item_pushed_result', 'additional'], 'default', 'value' => []],
            [['sales_channel_account_id'], 'required'],
            [['sales_channel_account_id', 'item_id', 'item_updated_at', 'external_created_at', 'external_updated_at', 'item_pushed_at', 'item_pushed_status', 'stock_pushed_at'], 'integer'],
            [['external_item_additional', 'item_pushed_result', 'additional'], 'safe'],
            [['item_type'], 'string', 'max' => 20],
            [['external_item_key'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'sales_channel_item_id' => Yii::t('lujie/salesChannel', 'Sales Channel Item ID'),
            'sales_channel_account_id' => Yii::t('lujie/salesChannel', 'Sales Channel Account ID'),
            'item_type' => Yii::t('lujie/salesChannel', 'Item Type'),
            'item_id' => Yii::t('lujie/salesChannel', 'Item ID'),
            'item_updated_at' => Yii::t('lujie/salesChannel', 'Item Updated At'),
            'external_item_key' => Yii::t('lujie/salesChannel', 'External Item Key'),
            'external_item_additional' => Yii::t('lujie/salesChannel', 'External Item Additional'),
            'external_created_at' => Yii::t('lujie/salesChannel', 'External Created At'),
            'external_updated_at' => Yii::t('lujie/salesChannel', 'External Updated At'),
            'item_pushed_at' => Yii::t('lujie/salesChannel', 'Item Pushed At'),
            'item_pushed_status' => Yii::t('lujie/salesChannel', 'Item Pushed Status'),
            'item_pushed_result' => Yii::t('lujie/salesChannel', 'Item Pushed Result'),
            'stock_pushed_at' => Yii::t('lujie/salesChannel', 'Stock Pushed At'),
            'additional' => Yii::t('lujie/salesChannel', 'Additional'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return SalesChannelItemQuery the active query used by this AR class.
     */
    public static function find(): SalesChannelItemQuery
    {
        return new SalesChannelItemQuery(static::class);
    }
}
