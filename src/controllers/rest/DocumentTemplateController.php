<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\controllers\rest;

use lujie\extend\rest\ActiveController;
use lujie\extend\rest\MethodAction;
use lujie\template\document\models\DocumentTemplate;
use lujie\template\document\TemplateDocumentManager;
use Yii;
use yii\di\Instance;

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
        return array_merge(parent::actions(), [
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
     * @param int $id referenceId
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function actionDownload($id): void
    {
        $this->templateDocument = Instance::ensure($this->templateDocument, TemplateDocumentManager::class);
        $generateFile = $this->templateDocument->generate($this->documentType, $id);
        Yii::$app->getResponse()->sendFile($generateFile, null, ['inline' => true]);
    }

    /**
     * @param int $id referenceId
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    public function actionTemplates($id): array
    {
        $this->templateDocument = Instance::ensure($this->templateDocument, TemplateDocumentManager::class);
        return $this->templateDocument->getTemplates($this->documentType, $id);
    }
}
