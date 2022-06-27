<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\controllers\rest;

use lujie\extend\helpers\TemplateHelper;
use lujie\template\document\models\DocumentTemplate;
use lujie\template\document\TemplateDocumentManager;
use Yii;
use yii\di\Instance;
use yii\rest\Controller;
use yii\web\Response;

/**
 * Class TemplateDocumentController
 * @package lujie\template\document\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TemplateDocumentController extends Controller
{
    public $filePathTemplate = '@runtime/documents/{documentType}/{documentKey}.pdf';

    /**
     * @var string
     */
    public $modelClass = DocumentTemplate::class;

    /**
     * @var TemplateDocumentManager
     */
    public $documentManager = 'documentManager';

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
     * @param string $type
     * @param $key
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     * @inheritdoc
     */
    public function actionDownload(string $type, $key): void
    {
        $data = ['documentType' => $type, 'documentKey' => $key];
        $generateFile = Yii::getAlias(TemplateHelper::generate($this->filePathTemplate, $data));
        $this->documentManager->generate($type, $generateFile, $key);
        $ext = pathinfo($generateFile, PATHINFO_EXTENSION);
        $inline = in_array($ext, ['pdf', 'html'], true);
        Yii::$app->getResponse()->sendFile($generateFile, null, ['inline' => $inline]);
    }

    /**
     * @param string $type
     * @param $key
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionPreview(string $type, $key): string
    {
        Yii::$app->getResponse()->format = Response::FORMAT_HTML;
        return $this->documentManager->render($type, $key);
    }
}
