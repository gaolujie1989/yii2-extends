<?php

namespace lujie\fulfillment\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%fulfillment_item}}".
 *
 * @property int $fulfillment_item_id
 * @property int $fulfillment_account_id
 * @property int $item_id
 * @property int $item_updated_at
 * @property string $external_item_key
 * @property array|null $external_item_additional
 * @property int $external_created_at
 * @property int $external_updated_at
 * @property int $item_pushed_at
 * @property int $item_pushed_status
 * @property array|null $item_pushed_result
 * @property int $stock_pulled_at
 * @property array|null $additional
 */
class FulfillmentItem extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;
    use FulfillmentAccountRelationTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%fulfillment_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['fulfillment_account_id', 'item_id', 'item_updated_at', 'external_created_at', 'external_updated_at', 'item_pushed_at', 'item_pushed_status', 'stock_pulled_at'], 'default', 'value' => 0],
            [['external_item_key'], 'default', 'value' => ''],
            [['external_item_additional', 'item_pushed_result', 'additional'], 'default', 'value' => []],
            [['fulfillment_account_id', 'item_id', 'item_updated_at', 'external_created_at', 'external_updated_at', 'item_pushed_at', 'item_pushed_status', 'stock_pulled_at'], 'integer'],
            [['external_item_additional', 'item_pushed_result', 'additional'], 'safe'],
            [['external_item_key'], 'string', 'max' => 50],
            [['item_id', 'fulfillment_account_id'], 'unique', 'targetAttribute' => ['item_id', 'fulfillment_account_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'fulfillment_item_id' => Yii::t('lujie/common', 'Fulfillment Item ID'),
            'fulfillment_account_id' => Yii::t('lujie/common', 'Fulfillment Account ID'),
            'item_id' => Yii::t('lujie/common', 'Item ID'),
            'item_updated_at' => Yii::t('lujie/common', 'Item Updated At'),
            'external_item_key' => Yii::t('lujie/common', 'External Item Key'),
            'external_item_additional' => Yii::t('lujie/common', 'External Item Additional'),
            'external_created_at' => Yii::t('lujie/common', 'External Created At'),
            'external_updated_at' => Yii::t('lujie/common', 'External Updated At'),
            'item_pushed_at' => Yii::t('lujie/common', 'Item Pushed At'),
            'item_pushed_status' => Yii::t('lujie/common', 'Item Pushed Status'),
            'item_pushed_result' => Yii::t('lujie/common', 'Item Pushed Result'),
            'stock_pulled_at' => Yii::t('lujie/common', 'Stock Pulled At'),
            'additional' => Yii::t('lujie/common', 'Additional'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return FulfillmentItemQuery the active query used by this AR class.
     */
    public static function find(): FulfillmentItemQuery
    {
        return new FulfillmentItemQuery(static::class);
    }
}
