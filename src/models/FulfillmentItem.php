<?php

namespace lujie\fulfillment\models;

use lujie\alias\behaviors\JsonAliasBehavior;
use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%fulfillment_item}}".
 *
 * @property string $fulfillment_item_id
 * @property string $fulfillment_account_id
 * @property string $item_id
 * @property int $item_updated_at
 * @property string $external_item_id
 * @property string $external_item_no
 * @property string $external_item_parent_id for some system, support variation must link item
 * @property int $external_created_at
 * @property int $external_updated_at
 * @property array $item_pushed_options
 * @property array $item_pushed_errors
 * @property int $item_pushed_at
 * @property int $stock_pulled_at
 *
 * @property FulfillmentAccount $fulfillmentAccount
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
            [['fulfillment_account_id', 'item_id'], 'required'],
            [['fulfillment_account_id', 'item_id', 'item_updated_at',
                'external_item_id', 'external_item_parent_id',
                'external_created_at', 'external_updated_at',
                'item_pushed_at', 'stock_pulled_at'], 'integer'],
            [['fulfillment_account_id', 'item_id'], 'unique', 'targetAttribute' => ['fulfillment_account_id', 'item_id']],
            [['external_item_no'], 'string', 'max' => 50],
            [['item_pushed_options', 'item_pushed_errors'], 'safe']
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
                    'item_error_summary' => 'item_pushed_errors'
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
            'fulfillment_item_id' => Yii::t('lujie/fulfillment', 'Fulfillment Item ID'),
            'fulfillment_account_id' => Yii::t('lujie/fulfillment', 'Fulfillment Account ID'),
            'item_id' => Yii::t('lujie/fulfillment', 'Item ID'),
            'item_updated_at' => Yii::t('lujie/fulfillment', 'Item Updated At'),
            'external_item_id' => Yii::t('lujie/fulfillment', 'External Item ID'),
            'external_item_no' => Yii::t('lujie/fulfillment', 'External Item No'),
            'external_item_parent_id' => Yii::t('lujie/fulfillment', 'External Item Parent ID'),
            'external_created_at' => Yii::t('lujie/fulfillment', 'External Created At'),
            'external_updated_at' => Yii::t('lujie/fulfillment', 'External Updated At'),
            'item_pushed_options' => Yii::t('lujie/fulfillment', 'Item Pushed Options'),
            'item_pushed_errors' => Yii::t('lujie/fulfillment', 'Item Pushed Errors'),
            'item_pushed_at' => Yii::t('lujie/fulfillment', 'Item Pushed At'),
            'stock_pulled_at' => Yii::t('lujie/fulfillment', 'Stock Pulled At'),
        ];
    }

    /**
     * @return FulfillmentItemQuery
     * @inheritdoc
     */
    public static function find(): FulfillmentItemQuery
    {
        return new FulfillmentItemQuery(static::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return array_merge(parent::fields(), [
            'id',
            'item_error_summary'
        ]);
    }
}
