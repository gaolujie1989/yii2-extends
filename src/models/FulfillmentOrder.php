<?php

namespace lujie\fulfillment\models;

use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use Yii;

/**
 * This is the model class for table "{{%fulfillment_order}}".
 *
 * @property string $fulfillment_order_id
 * @property string $fulfillment_account_id
 * @property string $order_id
 * @property int $order_status
 * @property string $external_order_id
 * @property string $external_order_no
 * @property string $external_order_status
 * @property array $external_order_additional
 * @property int $external_created_at
 * @property int $external_updated_at
 * @property array $order_options
 * @property array $order_errors
 */
class FulfillmentOrder extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait;

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
            [['fulfillment_account_id', 'order_id', 'order_status',
                'external_order_id', 'external_order_no',
                'external_created_at', 'external_updated_at'], 'integer'],
            [['order_id', 'order_status'], 'required'],
            [['external_order_status'], 'string', 'max' => 20],
            [['fulfillment_account_id', 'order_id'], 'unique', 'targetAttribute' => ['fulfillment_account_id', 'order_id']],
            [['fulfillment_account_id', 'external_order_id'], 'unique', 'targetAttribute' => ['fulfillment_account_id', 'external_order_id']],
            [['external_order_additional'], 'safe']
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
            'order_id' => Yii::t('lujie/fulfillment', 'Order ID'),
            'order_status' => Yii::t('lujie/fulfillment', 'Order Status'),
            'external_order_id' => Yii::t('lujie/fulfillment', 'External Order ID'),
            'external_order_no' => Yii::t('lujie/fulfillment', 'External Order No'),
            'external_order_status' => Yii::t('lujie/fulfillment', 'External Order Status'),
            'external_order_additional' => Yii::t('lujie/fulfillment', 'External Order Additional'),
            'external_created_at' => Yii::t('lujie/fulfillment', 'External Created At'),
            'external_updated_at' => Yii::t('lujie/fulfillment', 'External Updated At'),
        ];
    }

    /**
     * @return FulfillmentOrderQuery
     * @inheritdoc
     */
    public static function find(): FulfillmentOrderQuery
    {
        return new FulfillmentOrderQuery(static::class);
    }
}
