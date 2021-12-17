<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\controllers\rest;

use lujie\extend\rest\ActiveController;
use lujie\template\document\forms\DocumentTemplateForm;
use lujie\template\document\models\DocumentTemplate;
use lujie\template\document\searches\DocumentTemplateSearch;
use lujie\template\document\TemplateDocumentManager;
use Yii;
use yii\di\Instance;
use yii\web\Response;

/**
 * Class DocumentTemplateController
 * @package lujie\template\document\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DocumentTemplateController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = DocumentTemplate::class;

    /**
     * @var string
     */
    public $documentType;

    /**
     * @var TemplateDocumentManager
     */
    public $documentManager;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->documentManager = Instance::ensure($this->documentManager, TemplateDocumentManager::class);
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actions(): array
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['create'], $actions['delete']);
        return $actions;
    }

    /**
     * @param $id
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function actionTemplates($id): array
    {
        $query = DocumentTemplate::find()
            ->documentType($this->documentType)
            ->referenceId($id)
            ->orderByPosition();
        if (!$query->exists()) {
            DocumentTemplateForm::createTemplates($this->documentType, $id);
        }
        return $query->all();
    }

    /**
     * @param int $id
     * @inheritdoc
     */
    public function actionDownload(int $id): void
    {
        $generateFile = Yii::getAlias("@runtime/{$this->documentType}/{$id}.pdf");
        $this->documentManager->generate($generateFile, $id);
        Yii::$app->getResponse()->sendFile($generateFile, null, ['inline' => true]);
    }

    /**
     * @param int $id
     * @return string
     * @inheritdoc
     */
    public function actionPreview(int $id): string
    {
        Yii::$app->getResponse()->format = Response::FORMAT_HTML;
        return $this->documentManager->render($id);
    }
}
