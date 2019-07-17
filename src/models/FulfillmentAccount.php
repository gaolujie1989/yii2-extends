<?php

namespace lujie\fulfillment\models;

use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use Yii;

/**
 * This is the model class for table "{{%fulfillment_account}}".
 *
 * @property string $fulfillment_account_id
 * @property string $name
 * @property string $type
 * @property string $url
 * @property string $username
 * @property string $password
 * @property array $options
 * @property array $additional_info
 * @property int $status
 */
class FulfillmentAccount extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%fulfillment_account}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['options', 'additional_info'], 'safe'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 50],
            [['url', 'username', 'password'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'fulfillment_account_id' => Yii::t('lujie/fulfillment', 'Fulfillment Account ID'),
            'name' => Yii::t('lujie/fulfillment', 'Name'),
            'type' => Yii::t('lujie/fulfillment', 'Type'),
            'url' => Yii::t('lujie/fulfillment', 'Url'),
            'username' => Yii::t('lujie/fulfillment', 'Username'),
            'password' => Yii::t('lujie/fulfillment', 'Password'),
            'options' => Yii::t('lujie/fulfillment', 'Options'),
            'additional_info' => Yii::t('lujie/fulfillment', 'Additional Info'),
            'status' => Yii::t('lujie/fulfillment', 'Status'),
        ];
    }

    /**
     * @return FulfillmentAccountQuery
     * @inheritdoc
     */
    public static function find(): FulfillmentAccountQuery
    {
        return new FulfillmentAccountQuery(static::class);
    }
}
