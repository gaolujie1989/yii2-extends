<?php

namespace lujie\common\account\models;

use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%account}}".
 *
 * @property int $account_id
 * @property string $model_type
 * @property string $name
 * @property string $type
 * @property string $url
 * @property string $username
 * @property string $password
 * @property array|null $options
 * @property array|null $additional
 * @property int $status
 */
class Account extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    public const MODEL_TYPE = 'DEFAULT';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%account}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'type', 'url', 'username', 'password'], 'default', 'value' => ''],
            [['options', 'additional'], 'default', 'value' => []],
            [['status'], 'default', 'value' => 0],
            [['options', 'additional'], 'safe'],
            [['status'], 'integer'],
            [['type'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
            [['url', 'username', 'password'], 'string', 'max' => 255],
            [['name'], 'unique', 'targetAttribute' => ['name']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'account_id' => Yii::t('lujie/common', 'Account ID'),
            'model_type' => Yii::t('lujie/common', 'Model Type'),
            'name' => Yii::t('lujie/common', 'Name'),
            'type' => Yii::t('lujie/common', 'Type'),
            'url' => Yii::t('lujie/common', 'Url'),
            'username' => Yii::t('lujie/common', 'Username'),
            'password' => Yii::t('lujie/common', 'Password'),
            'options' => Yii::t('lujie/common', 'Options'),
            'additional' => Yii::t('lujie/common', 'Additional'),
            'status' => Yii::t('lujie/common', 'Status'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return AccountQuery the active query used by this AR class.
     */
    public static function find(): AccountQuery
    {
        return (new AccountQuery(static::class))->modelType(static::MODEL_TYPE);
    }
}
