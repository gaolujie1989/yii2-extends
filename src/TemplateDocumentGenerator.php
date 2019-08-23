<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document;

use lujie\template\document\models\DocumentTemplate;
use yii\base\BaseObject;
use yii\base\InvalidArgumentException;

/**
 * Class TemplateDocumentGenerator
 * @package lujie\template\document
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TemplateDocumentGenerator extends BaseObject
{
    /**
     * @param string $documentType
     * @param int $documentReferenceId
     * @return string
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function generateDocument(string $documentType, int $documentReferenceId = 0): string
    {
        $templateQuery = DocumentTemplate::find()->type($documentType)->referenceId($documentReferenceId);
        if ($documentReferenceId && !$templateQuery->exists()) {
            $defaultTemplates = DocumentTemplate::find()
                ->type($documentType)
                ->referenceId($documentReferenceId)
                ->asArray()
                ->all();
            if (empty($defaultTemplates)) {
                throw new InvalidArgumentException("Invalid Document Type: {$documentType}");
            }
            $defaultTemplates = array_map(static function ($template) use ($documentReferenceId) {
                unset($template['document_template_id']);
                $template['document_reference_id'] = $documentReferenceId;
                return $template;
            }, $defaultTemplates);
            DocumentTemplate::getDb()
                ->createCommand()
                ->batchInsert(DocumentTemplate::tableName(), array_keys($defaultTemplates[0]), $defaultTemplates)
                ->execute();
        }
        $documentTemplates = $templateQuery->active()->orderByPosition()->asArray()->all();

    }
}
