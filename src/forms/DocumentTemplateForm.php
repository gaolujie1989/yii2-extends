<?php

namespace lujie\template\document\forms;

use lujie\extend\db\FormTrait;
use lujie\extend\helpers\ModelHelper;
use lujie\template\document\models\DocumentTemplate;
use yii\base\InvalidArgumentException;
use yii2tech\ar\position\PositionBehavior;

/**
 * Class DocumentTemplateForm
 * @package lujie\template\document\models
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DocumentTemplateForm extends DocumentTemplate
{
    use FormTrait;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        $rules = ModelHelper::searchRules($this);
        $rules = ModelHelper::removeAttributesRules($rules, ['document_type', 'reference_id', 'position']);
        return $rules;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'position' => [
                'class' => PositionBehavior::class,
                'groupAttributes' => ['type', 'reference_id'],
            ]
        ]);
    }

    /**
     * @param string $documentType
     * @param int $referenceId
     * @return int
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public static function createTemplates(string $documentType, int $referenceId): int
    {
        $defaultTemplates = DocumentTemplate::find()
            ->documentType($documentType)
            ->referenceId(0)
            ->asArray()
            ->all();
        if (empty($defaultTemplates)) {
            throw new InvalidArgumentException("Empty default template of {$documentType}");
        }
        $docTemplates = array_map(static function ($template) use ($referenceId) {
            unset($template['document_template_id']);
            $template['reference_id'] = $referenceId;
            return $template;
        }, $defaultTemplates);
        return DocumentTemplate::getDb()
            ->createCommand()
            ->batchInsert(DocumentTemplate::tableName(), array_keys(reset($docTemplates)), $docTemplates)
            ->execute();
    }
}
