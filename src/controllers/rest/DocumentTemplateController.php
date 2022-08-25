<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\controllers\rest;

use lujie\extend\rest\ActiveController;
use lujie\template\document\actions\DocumentAction;
use lujie\template\document\forms\DocumentTemplateForm;
use lujie\template\document\models\DocumentTemplate;
use lujie\template\document\TemplateDocumentManager;

/**
 * Class DocumentTemplateController
 * @package lujie\template\document\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DocumentTemplateController extends ActiveController
{
    /**
     * @var DocumentTemplate
     */
    public $modelClass = DocumentTemplate::class;

    /**
     * @var string
     */
    public $documentType;

    /**
     * @var TemplateDocumentManager
     */
    public $documentManager = 'documentManager';

    /**
     * @return array
     * @inheritdoc
     */
    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'download' => [
                'class' => DocumentAction::class,
                'documentManager' => $this->documentManager,
                'documentType' => $this->documentType,
            ],
            'preview' => [
                'class' => DocumentAction::class,
                'documentManager' => $this->documentManager,
                'documentType' => $this->documentType,
                'preview' => true,
            ]
        ]);
    }

    /**
     * @param $id
     * @param string|null $type
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function actionTemplates($id, ?string $type = null): array
    {
        $type = $type ?: $this->documentType;
        $query = $this->modelClass::find()
            ->documentType($type)
            ->referenceId($id)
            ->orderByPosition();
        if (!$query->exists()) {
            DocumentTemplateForm::createTemplates($type, $id);
        }
        return $query->all();
    }
}
