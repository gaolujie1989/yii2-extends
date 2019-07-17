<?php

namespace lujie\fulfillment\models;

use Yii;

/**
 * This is the model class for table "{{%fulfillment_warehouse}}".
 *
 * @property string $fulfillment_warehouse_id
 * @property string $fulfillment_account_id
 * @property string $warehouse_id
 * @property string $external_name
 * @property array $additional
 * @property int $status
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 */
class FulfillmentWarehouse extends \yii\db\ActiveRecord
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
            [['fulfillment_account_id', 'warehouse_id', 'status'], 'integer'],
            [['additional'], 'safe'],
            [['external_name'], 'string', 'max' => 100],
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
            'external_name' => Yii::t('lujie/fulfillment', 'External Name'),
            'additional' => Yii::t('lujie/fulfillment', 'Additional'),
            'status' => Yii::t('lujie/fulfillment', 'Status'),
        ];
    }

    /**
     * @return FulfillmentWarehouseQuery
     * @inheritdoc
     */
    public static function find(): FulfillmentWarehouseQuery
    {
        return new FulfillmentWarehouseQuery(static::class);
    }
}
