<?php

namespace lujie\template\document\models;

use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use Yii;

/**
 * This is the model class for table "{{%document_template}}".
 *
 * @property int $document_template_id
 * @property string $document_reference_id
 * @property string $document_type
 * @property int $position
 * @property string $title
 * @property string $subtitle
 * @property string $content
 * @property int $status
 */
class DocumentTemplate extends \yii\db\ActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait;

    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 10;

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
            [['document_reference_id', 'position', 'status'], 'integer'],
            [['content'], 'required'],
            [['content'], 'string'],
            [['document_type'], 'string', 'max' => 50],
            [['title', 'subtitle'], 'string', 'max' => 250],
            [['status'], 'in', 'range' => [self::STATUS_INACTIVE, self::STATUS_ACTIVE]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'document_template_id' => Yii::t('lujie/template', 'Document Template ID'),
            'document_reference_id' => Yii::t('lujie/template', 'Document Reference ID'),
            'document_type' => Yii::t('lujie/template', 'Document Type'),
            'position' => Yii::t('lujie/template', 'Position'),
            'title' => Yii::t('lujie/template', 'Title'),
            'subtitle' => Yii::t('lujie/template', 'Subtitle'),
            'content' => Yii::t('lujie/template', 'Content'),
            'status' => Yii::t('lujie/template', 'Status'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return DocumentTemplateQuery the active query used by this AR class.
     */
    public static function find(): DocumentTemplateQuery
    {
        return new DocumentTemplateQuery(self::class);
    }
}
