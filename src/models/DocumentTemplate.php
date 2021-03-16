<?php

namespace lujie\template\document\models;

use lujie\extend\db\AliasFieldTrait;
use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%document_template}}".
 *
 * @property int $document_template_id
 * @property string $document_type
 * @property int $reference_id
 * @property int $position
 * @property string $name
 * @property string $content
 * @property string $additional
 * @property int $status
 */
class DocumentTemplate extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, AliasFieldTrait, SaveTrait, TransactionTrait, DbConnectionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%document_template}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['document_type', 'name'], 'default', 'value' => ''],
            [['reference_id', 'position', 'status'], 'default', 'value' => 0],
            [['reference_id', 'position', 'status'], 'integer'],
            [['content', 'additional'], 'required'],
            [['content'], 'string'],
            [['additional'], 'safe'],
            [['document_type'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'document_template_id' => Yii::t('lujie/template', 'Document Template ID'),
            'document_type' => Yii::t('lujie/template', 'Document Type'),
            'reference_id' => Yii::t('lujie/template', 'Reference ID'),
            'position' => Yii::t('lujie/template', 'Position'),
            'name' => Yii::t('lujie/template', 'Name'),
            'content' => Yii::t('lujie/template', 'Content'),
            'additional' => Yii::t('lujie/template', 'Additional'),
            'status' => Yii::t('lujie/template', 'Status'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return DocumentTemplateQuery the active query used by this AR class.
     */
    public static function find(): DocumentTemplateQuery
    {
        return new DocumentTemplateQuery(static::class);
    }
}
