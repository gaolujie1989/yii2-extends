<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\controllers\rest;

use lujie\extend\rest\ActiveController;
use lujie\template\document\forms\DocumentTemplateForm;
use lujie\template\document\models\DocumentTemplate;

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
