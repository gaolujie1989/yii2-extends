<?php

namespace lujie\fulfillment\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%fulfillment_warehouse}}".
 *
 * @property int $fulfillment_warehouse_id
 * @property int $fulfillment_account_id
 * @property int $warehouse_id
 * @property string $external_warehouse_key
 * @property array|null $external_warehouse_additional
 * @property array|null $additional
 */
class FulfillmentWarehouse extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%fulfillment_warehouse}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['fulfillment_account_id', 'warehouse_id'], 'default', 'value' => 0],
            [['external_warehouse_key'], 'default', 'value' => ''],
            [['external_warehouse_additional', 'additional'], 'default', 'value' => []],
            [['fulfillment_account_id', 'warehouse_id'], 'integer'],
            [['external_warehouse_additional', 'additional'], 'safe'],
            [['external_warehouse_key'], 'string', 'max' => 50],
            [['external_warehouse_key', 'fulfillment_account_id'], 'unique', 'targetAttribute' => ['external_warehouse_key', 'fulfillment_account_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'fulfillment_warehouse_id' => Yii::t('lujie/common', 'Fulfillment Warehouse ID'),
            'fulfillment_account_id' => Yii::t('lujie/common', 'Fulfillment Account ID'),
            'warehouse_id' => Yii::t('lujie/common', 'Warehouse ID'),
            'external_warehouse_key' => Yii::t('lujie/common', 'External Warehouse Key'),
            'external_warehouse_additional' => Yii::t('lujie/common', 'External Warehouse Additional'),
            'additional' => Yii::t('lujie/common', 'Additional'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return FulfillmentWarehouseQuery the active query used by this AR class.
     */
    public static function find(): FulfillmentWarehouseQuery
    {
        return new FulfillmentWarehouseQuery(static::class);
    }
}
