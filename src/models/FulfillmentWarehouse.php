<?php

namespace lujie\fulfillment\models;

use Yii;

/**
 * This is the model class for table "{{%fulfillment_warehouse}}".
 *
 * @property int $fulfillment_warehouse_id
 * @property int $fulfillment_account_id
 * @property int $warehouse_id
 * @property string $external_warehouse_key
 * @property array|null $external_warehouse_additional
 * @property int $support_movement
 * @property int $external_movement_at
 * @property array|null $additional
 */
class FulfillmentWarehouse extends \lujie\fulfillment\base\db\ActiveRecord
{

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
            [['fulfillment_account_id', 'warehouse_id', 'support_movement', 'external_movement_at'], 'default', 'value' => 0],
            [['external_warehouse_key'], 'default', 'value' => ''],
            [['external_warehouse_additional', 'additional'], 'default', 'value' => []],
            [['fulfillment_account_id', 'warehouse_id', 'support_movement', 'external_movement_at'], 'integer'],
            [['external_warehouse_additional', 'additional'], 'safe'],
            [['external_warehouse_key'], 'string', 'max' => 50],
            [['warehouse_id'], 'unique', 'when' => static function($model) {
                return $model->warehouse_id > 0;
            }],
            [['external_warehouse_key', 'fulfillment_account_id'], 'unique', 'targetAttribute' => ['external_warehouse_key', 'fulfillment_account_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'fulfillment_warehouse_id' => Yii::t('lujie/fulfillment', 'Fulfillment Warehouse ID'),
            'fulfillment_account_id' => Yii::t('lujie/fulfillment', 'Fulfillment Account ID'),
            'warehouse_id' => Yii::t('lujie/fulfillment', 'Warehouse ID'),
            'external_warehouse_key' => Yii::t('lujie/fulfillment', 'External Warehouse Key'),
            'external_warehouse_additional' => Yii::t('lujie/fulfillment', 'External Warehouse Additional'),
            'support_movement' => Yii::t('lujie/fulfillment', 'Support Movement'),
            'external_movement_at' => Yii::t('lujie/fulfillment', 'External Movement At'),
            'additional' => Yii::t('lujie/fulfillment', 'Additional'),
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
