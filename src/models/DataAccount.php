<?php

namespace lujie\data\recording\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%data_account}}".
 *
 * @property int $data_account_id
 * @property string $name
 * @property string $type
 * @property string $url
 * @property string $username
 * @property string $password
 * @property array $options
 * @property array $additional
 * @property int $status
 *
 * @property DataSource[] $dataSources
 */
class DataAccount extends \lujie\data\recording\base\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%data_account}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 50],
            [['url', 'username', 'password'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'data_account_id' => Yii::t('lujie/data', 'Data Account ID'),
            'name' => Yii::t('lujie/data', 'Name'),
            'type' => Yii::t('lujie/data', 'Type'),
            'url' => Yii::t('lujie/data', 'Url'),
            'username' => Yii::t('lujie/data', 'Username'),
            'password' => Yii::t('lujie/data', 'Password'),
            'options' => Yii::t('lujie/data', 'Options'),
            'additional' => Yii::t('lujie/data', 'Additional Info'),
            'status' => Yii::t('lujie/data', 'Status'),
        ];
    }

    /**
     * @return DataAccountQuery|ActiveQuery
     * @inheritdoc
     */
    public static function find(): DataAccountQuery
    {
        return new DataAccountQuery(static::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'dataSource',
        ]);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getDataSources(): ActiveQuery
    {
        return $this->hasMany(DataSource::class, ['data_account_id' => 'data_account_id']);
    }
}
