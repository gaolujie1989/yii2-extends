<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\controllers\rest;

use lujie\extend\rest\ActiveController;
use lujie\extend\rest\MethodAction;
use lujie\template\document\forms\DocumentTemplateForm;
use lujie\template\document\models\DocumentTemplate;
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
    public $templateDocument = 'templateDocument';

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actions(): array
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['create'], $actions['delete']);
        return array_merge($actions, [
            'move-prev' => [
                'class' => MethodAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'method' => 'movePrev'
            ],
            'move-next' => [
                'class' => MethodAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'method' => 'moveNext'
            ],
        ]);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->templateDocument = Instance::ensure($this->templateDocument, TemplateDocumentManager::class);
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
    public function actionDownload($id): void
    {
        $generateFile = $this->templateDocument->generate($id);
        Yii::$app->getResponse()->sendFile($generateFile, null, ['inline' => true]);
    }

    /**
     * @param int $id
     * @return string
     * @inheritdoc
     */
    public function actionPreview($id): string
    {
        Yii::$app->getResponse()->format = Response::FORMAT_HTML;
        return $this->templateDocument->render($id);
    }
}
