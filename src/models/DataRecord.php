<?php

namespace lujie\data\recording\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%data_record}}".
 *
 * @property int $data_record_id
 * @property int $data_account_id
 * @property string $data_source_type
 * @property int $data_id
 * @property string $data_key
 * @property int $data_parent_id
 * @property array|null $data_additional
 * @property int $data_created_at
 * @property int $data_updated_at
 *
 * @property DataRecordData $recordData
 * @property DataAccount $dataAccount
 * @property DataSource $dataSource
 */
class DataRecord extends \lujie\data\recording\base\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%data_record}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['data_account_id', 'data_source_type', 'data_id', 'data_parent_id',
                'data_created_at', 'data_updated_at'], 'default', 'value' => 0],
            [['data_key'], 'default', 'value' => ''],
            [['data_additional'], 'default', 'value' => []],
            [['data_account_id', 'data_id', 'data_parent_id', 'data_created_at', 'data_updated_at'], 'integer'],
            [['data_additional'], 'safe'],
            [['data_source_type'], 'string', 'max' => 50],
            [['data_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'data_record_id' => Yii::t('lujie/data', 'Data Record ID'),
            'data_account_id' => Yii::t('lujie/data', 'Data Account ID'),
            'data_source_type' => Yii::t('lujie/data', 'Data Source Type'),
            'data_id' => Yii::t('lujie/data', 'Data ID'),
            'data_key' => Yii::t('lujie/data', 'Data Key'),
            'data_parent_id' => Yii::t('lujie/data', 'Data Parent ID'),
            'data_additional' => Yii::t('lujie/data', 'Data Additional'),
            'data_created_at' => Yii::t('lujie/data', 'Data Created At'),
            'data_updated_at' => Yii::t('lujie/data', 'Data Updated At'),
        ];
    }

    /**
     * @return DataRecordQuery
     * @inheritdoc
     */
    public static function find(): DataRecordQuery
    {
        return new DataRecordQuery(static::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields(): array
    {
        return array_merge(parent::extraFields(), [
            'dataAccount',
            'recordData',
            'recordDataText',
        ]);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getRecordData(): ActiveQuery
    {
        return $this->hasOne(DataRecordData::class, ['data_record_id' => 'data_record_id']);
    }

    /**
     * @return ActiveQuery
     * @inheritdoc
     */
    public function getDataAccount(): ActiveQuery
    {
        return $this->hasOne(DataAccount::class, ['data_account_id' => 'data_account_id']);
    }

    /**
     * @return string|null
     * @inheritdoc
     */
    public function getRecordDataText(): ?string
    {
        return DataRecordData::getDataTextByRecordId($this->data_record_id);
    }
}
