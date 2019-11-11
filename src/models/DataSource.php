<?php

namespace lujie\data\recording\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%data_source}}".
 *
 * @property int $data_source_id
 * @property int $data_account_id
 * @property string $name
 * @property string $type
 * @property array $condition
 * @property array $additional
 * @property int $status
 * @property int $last_exec_at
 * @property int $last_exec_status
 * @property array $last_exec_result
 *
 * @property DataAccount $dataAccount
 */
class DataSource extends \lujie\data\recording\base\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%data_source}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['data_account_id', 'status', 'last_exec_at', 'last_exec_status'], 'integer'],
            [['condition', 'additional', 'last_exec_result'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'data_source_id' => Yii::t('lujie/data', 'Data Source ID'),
            'data_account_id' => Yii::t('lujie/data', 'Data Account ID'),
            'name' => Yii::t('lujie/data', 'Name'),
            'type' => Yii::t('lujie/data', 'Type'),
            'condition' => Yii::t('lujie/data', 'Condition'),
            'additional' => Yii::t('lujie/data', 'Additional'),
            'status' => Yii::t('lujie/data', 'Status'),
            'last_exec_at' => Yii::t('lujie/data', 'Last Exec At'),
            'last_exec_status' => Yii::t('lujie/data', 'Last Exec Status'),
            'last_exec_result' => Yii::t('lujie/data', 'Last Exec Result'),
        ];
    }

    /**
     * @return DataSourceQuery|ActiveQuery
     * @inheritdoc
     */
    public static function find(): DataSourceQuery
    {
        return new DataSourceQuery(static::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'dataAccount',
        ]);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getDataAccount(): ActiveQuery
    {
        return $this->hasOne(DataAccount::class, ['data_account_id' => 'data_account_id']);
    }
}
