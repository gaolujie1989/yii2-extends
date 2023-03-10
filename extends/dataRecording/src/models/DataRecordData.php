<?php

namespace lujie\data\recording\models;

use lujie\extend\compressors\CompressorInterface;
use lujie\extend\compressors\GzCompressor;
use Yii;

/**
 * This is the model class for table "{{%data_record_data}}".
 *
 * @property int $data_record_data_id
 * @property int|null $data_record_id
 * @property resource|string|null $data_text
 */
class DataRecordData extends \lujie\data\recording\base\db\ActiveRecord
{
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
            [['data_text'], 'string'],
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

    /**
     * @param array $dataRecordIds
     * @param CompressorInterface|null|false $compressor
     * @return array
     * @inheritdoc
     */
    public static function getDataTextsByRecordIds(array $dataRecordIds, $compressor = null): array
    {
        $dataTexts = static::find()
            ->andWhere(['data_record_id' => $dataRecordIds])
            ->select(['data_text'])
            ->indexBy('data_record_id')
            ->column();
        if ($compressor === false) {
            return $dataTexts;
        }
        if ($compressor === null) {
            $compressor = new GzCompressor();
        }
        foreach ($dataTexts as $key => $dataText) {
            $dataTexts[$key] = $compressor->unCompress($dataText);
        }
        return $dataTexts;
    }

    /**
     * @param int $dataRecordId
     * @param CompressorInterface|null|false $compressor
     * @return string|null
     * @inheritdoc
     */
    public static function getDataTextByRecordId(int $dataRecordId, $compressor = null): ?string
    {
        $dataTexts = static::getDataTextsByRecordIds([$dataRecordId], $compressor);
        return $dataTexts[$dataRecordId] ?? null;
    }
}
