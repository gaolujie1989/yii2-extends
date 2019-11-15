<?php

namespace lujie\fulfillment\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%fulfillment_warehouse}}".
 *
 * @property string $fulfillment_warehouse_id
 * @property string $fulfillment_account_id
 * @property string $warehouse_id
 * @property string $external_warehouse_id
 * @property string $external_warehouse_name
 * @property array $additional
 * @property int $status
 *
 * @property FulfillmentAccount $fulfillmentAccount
 */
class FulfillmentWarehouse extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;
    use FulfillmentAccountRelationTrait;

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
            [['fulfillment_account_id', 'warehouse_id', 'external_warehouse_id', 'status'], 'integer'],
            [['additional'], 'safe'],
            [['fulfillment_account_id', 'external_warehouse_id'], 'unique', 'targetAttribute' => ['fulfillment_account_id', 'external_warehouse_id']],
            [['external_warehouse_name'], 'string', 'max' => 100],
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
            'external_warehouse_id' => Yii::t('lujie/fulfillment', 'External Warehouse ID'),
            'external_warehouse_name' => Yii::t('lujie/fulfillment', 'External Warehouse Name'),
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
