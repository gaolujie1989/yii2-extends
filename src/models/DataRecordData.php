<?php

namespace lujie\data\recording\models;

use lujie\extend\db\TraceableBehaviorTrait;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%data_record_data}}".
 *
 * @property int $data_record_data_id
 * @property int $data_record_id
 * @property int $data_text
 */
class DataRecordData extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%data_record_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['data_record_id'], 'integer'],
            [['data_text'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'data_record_data_id' => Yii::t('lujie/data', 'Data Record Data ID'),
            'data_record_id' => Yii::t('lujie/data', 'Data Record ID'),
            'data_text' => Yii::t('lujie/data', 'data_text'),
        ];
    }

    /**
     * @param int $dataRecordId
     * @return DataRecordData|null
     * @inheritdoc
     */
    public static function findByDataRecordId(int $dataRecordId): ?self
    {
        return static::findOne(['data_record_id' => $dataRecordId]);
    }
}
