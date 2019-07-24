<?php

namespace lujie\fulfillment\models;

use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%fulfillment_item}}".
 *
 * @property string $fulfillment_item_id
 * @property string $fulfillment_account_id
 * @property string $item_id
 * @property string $external_item_id
 * @property string $external_item_no
 * @property string $external_item_parent_id for some system, support variation must link item
 * @property int $external_created_at
 * @property int $external_updated_at
 * @property array $item_options
 * @property array $item_errors
 */
class FulfillmentItem extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait;

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
            [['fulfillment_account_id', 'item_id',
                'external_item_id', 'external_item_parent_id',
                'external_created_at', 'external_updated_at'], 'integer'],
            [['item_id'], 'required'],
            [['external_item_no'], 'string', 'max' => 50],
        ];
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
            'external_item_id' => Yii::t('lujie/fulfillment', 'External Item ID'),
            'external_item_no' => Yii::t('lujie/fulfillment', 'External Item No'),
            'external_item_parent_id' => Yii::t('lujie/fulfillment', 'External Item Parent ID'),
            'external_created_at' => Yii::t('lujie/fulfillment', 'External Created At'),
            'external_updated_at' => Yii::t('lujie/fulfillment', 'External Updated At'),
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
}
