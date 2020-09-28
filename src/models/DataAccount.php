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
 * @property array|null $options
 * @property array|null $additional
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
            [['name', 'type', 'url', 'username', 'password'], 'default', 'value' => ''],
            [['options', 'additional'], 'default', 'value' => []],
            [['status'], 'default', 'value' => 0],
            [['options', 'additional'], 'safe'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 255],
            [['username', 'password'], 'string', 'max' => 200],
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
            'additional' => Yii::t('lujie/data', 'Additional'),
            'status' => Yii::t('lujie/data', 'Status'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return DataAccountQuery the active query used by this AR class.
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
            'dataSource' => 'dataSource',
        ]);
    }

    /**
     * @return DataSourceQuery|ActiveQuery
     * @inheritdoc
     */
    public function getDataSources(): ActiveQuery
    {
        return $this->hasMany(DataSource::class, ['data_account_id' => 'data_account_id']);
    }
}
