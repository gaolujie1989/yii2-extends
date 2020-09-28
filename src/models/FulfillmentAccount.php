<?php

namespace lujie\fulfillment\models;

use lujie\extend\constants\StatusConst;
use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use lujie\state\machine\behaviors\StatusCheckBehavior;
use Yii;

/**
 * This is the model class for table "{{%fulfillment_account}}".
 *
 * @property int $fulfillment_account_id
 * @property string $name
 * @property string $type
 * @property string $url
 * @property string $username
 * @property string $password
 * @property array|null $options
 * @property array|null $additional
 * @property int $status
 *
 * @property bool $isActive;
 * @property bool $isInActive;
 */
class FulfillmentAccount extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

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
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 50],
            [['url', 'username', 'password'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'statusChecker' => [
                'class' => StatusCheckBehavior::class,
                'statusCheckProperties' => [
                    'isActive' => [StatusConst::STATUS_ACTIVE],
                    'isInActive' => [StatusConst::STATUS_INACTIVE],
                ]
            ]
        ]);
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
            'additional' => Yii::t('lujie/fulfillment', 'Additional'),
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
