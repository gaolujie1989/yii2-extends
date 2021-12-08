<?php

namespace lujie\common\account\models;

use lujie\common\oauth\models\AuthToken;
use Yii;
use yii\db\ActiveQuery;

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
 *
 * @property AuthToken $authToken
 */
class Account extends \lujie\extend\db\ActiveRecord
{
    public const MODEL_TYPE = 'DEFAULT';

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        if (empty($this->model_type)) {
            $this->model_type = static::MODEL_TYPE;
        }
        parent::init();
    }

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
            [['type', 'username'], 'unique', 'targetAttribute' => ['type', 'username']],
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

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'authToken' => 'authToken'
        ]);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getAuthToken(): ActiveQuery
    {
        return $this->hasOne(AuthToken::class, ['user_id' => 'account_id', 'auth_service' => 'type']);
    }
}
