<?php

namespace lujie\template\document\models;

use Yii;

/**
 * This is the model class for table "{{%document_file}}".
 *
 * @property int $document_file_id
 * @property string $document_type
 * @property int $reference_id
 * @property string $reference_no
 * @property string $document_no
 * @property string $document_file
 * @property array|null $document_data
 * @property array|null $additional
 */
class DocumentFile extends \lujie\extend\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%document_file}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['document_type', 'reference_no', 'document_no', 'document_file'], 'default', 'value' => ''],
            [['reference_id'], 'default', 'value' => 0],
            [['document_data', 'additional'], 'default', 'value' => []],
            [['reference_id'], 'integer'],
            [['document_data', 'additional'], 'safe'],
            [['document_type', 'reference_no', 'document_no'], 'string', 'max' => 50],
            [['document_file'], 'string', 'max' => 255],
            [['document_type', 'reference_id'], 'unique', 'targetAttribute' => ['document_type', 'reference_id']],
            [['document_type', 'document_no'], 'unique', 'targetAttribute' => ['document_type', 'document_no']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'document_file_id' => Yii::t('lujie/template', 'Document File ID'),
            'document_type' => Yii::t('lujie/template', 'Document Type'),
            'reference_id' => Yii::t('lujie/template', 'Reference ID'),
            'reference_no' => Yii::t('lujie/template', 'Reference No'),
            'document_no' => Yii::t('lujie/template', 'Document No'),
            'document_file' => Yii::t('lujie/template', 'Document File'),
            'document_data' => Yii::t('lujie/template', 'Document Data'),
            'additional' => Yii::t('lujie/template', 'Additional'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return DocumentFileQuery the active query used by this AR class.
     */
    public static function find(): DocumentFileQuery
    {
        return new DocumentFileQuery(static::class);
    }
}
