<?php

namespace lujie\fulfillment\models;

use lujie\alias\behaviors\JsonAliasBehavior;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%fulfillment_order}}".
 *
 * @property string $fulfillment_order_id
 * @property string $fulfillment_account_id
 * @property string $fulfillment_status
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
 * @property int $order_pulled_at
 *
 * @property FulfillmentAccount $fulfillmentAccount
 */
class FulfillmentOrder extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait;
    use FulfillmentAccountRelationTrait;

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
            [['fulfillment_account_id', 'order_id'], 'required'],
            [['fulfillment_account_id', 'fulfillment_status', 'order_id', 'order_status',
                'external_order_id', 'external_created_at', 'external_updated_at', 'order_pulled_at'], 'integer'],
            [['external_order_no'], 'string', 'max' => 50],
            [['external_order_status'], 'string', 'max' => 20],
            [['fulfillment_account_id', 'order_id'], 'unique', 'targetAttribute' => ['fulfillment_account_id', 'order_id']],
            [['external_order_additional', 'order_options', 'order_errors'], 'safe']
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), $this->traceableBehaviors(), [
            'jsonAlias' => [
                'class' => JsonAliasBehavior::class,
                'aliasProperties' => [
                    'order_errors_summary' => 'order_errors'
                ],
            ]
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'fulfillment_order_id' => Yii::t('lujie/fulfillment', 'Fulfillment Order ID'),
            'fulfillment_account_id' => Yii::t('lujie/fulfillment', 'Fulfillment Account ID'),
            'fulfillment_status' => Yii::t('lujie/fulfillment', 'Fulfillment Status'),
            'order_id' => Yii::t('lujie/fulfillment', 'Order ID'),
            'order_status' => Yii::t('lujie/fulfillment', 'Order Status'),
            'external_order_id' => Yii::t('lujie/fulfillment', 'External Order ID'),
            'external_order_no' => Yii::t('lujie/fulfillment', 'External Order No'),
            'external_order_status' => Yii::t('lujie/fulfillment', 'External Order Status'),
            'external_order_additional' => Yii::t('lujie/fulfillment', 'External Order Additional'),
            'external_created_at' => Yii::t('lujie/fulfillment', 'External Created At'),
            'external_updated_at' => Yii::t('lujie/fulfillment', 'External Updated At'),
            'order_options' => Yii::t('lujie/fulfillment', 'Order Options'),
            'order_errors' => Yii::t('lujie/fulfillment', 'Order Errors'),
            'order_pulled_at' => Yii::t('lujie/fulfillment', 'Order Pulled At'),
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

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return array_merge(parent::fields(), [
            'order_errors_summary'
        ]);
    }
}
