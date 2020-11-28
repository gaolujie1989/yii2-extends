<?php

namespace lujie\common\modelData\models;

use lujie\extend\compressors\GzCompressor;
use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%model_data}}".
 *
 * @property int $model_data_id
 * @property string $model_type
 * @property int $model_id
 * @property resource|null|string $data_text
 */
class ModelData extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    public const MODEL_TYPE = 'DEFAULT';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%model_data}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['model_type', 'model_id'], 'default', 'value' => 0],
            [['model_id'], 'integer'],
            [['data_text'], 'string'],
            [['model_type'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'model_data_id' => Yii::t('lujie/common', 'Model Data ID'),
            'model_type' => Yii::t('lujie/common', 'Model Type'),
            'model_id' => Yii::t('lujie/common', 'Model ID'),
            'data_text' => Yii::t('lujie/common', 'Data Text'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ModelDataQuery the active query used by this AR class.
     */
    public static function find(): ModelDataQuery
    {
        return (new ModelDataQuery(static::class))->modelType(static::MODEL_TYPE);
    }

    #region Helpful Functions

    /**
     * @param int $modelId
     * @return static|null
     * @inheritdoc
     */
    public static function findByModelId(int $modelId): ?self
    {
        return static::find()->modelId($modelId)->one();
    }

    /**
     * @param array $modelIds
     * @param null $compressor
     * @return array
     * @inheritdoc
     */
    public static function getDataTextsByModelIds(array $modelIds, $compressor = null): array
    {
        $dataTexts = static::find()
            ->modelId($modelIds)
            ->getDataTexts();
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
     * @param int $modelId
     * @param null $compressor
     * @return string|null
     * @inheritdoc
     */
    public static function getDataTextByModelId(int $modelId, $compressor = null): ?string
    {
        $dataTexts = static::getDataTextsByModelIds([$modelId], $compressor);
        return $dataTexts[$modelId] ?? null;
    }

    #endregion
}
