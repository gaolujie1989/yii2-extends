<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\controllers\rest;

use lujie\extend\helpers\TemplateHelper;
use lujie\template\document\models\DocumentTemplate;
use lujie\template\document\TemplateDocumentManager;
use Yii;
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
     * @var string
     */
    public $documentType;

    /**
     * @var TemplateDocumentManager
     */
    public $documentManager;

    public $downloadOptions = ['inline' => true];

    /**
     * @param string $documentType
     * @param $documentKey
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     * @inheritdoc
     */
    public function actionDownload(string $documentType, $documentKey): void
    {
        $data = ['documentType' => $documentType, 'documentKey' => $documentKey];
        $generateFile = Yii::getAlias(TemplateHelper::generate($this->filePathTemplate, $data));
        $this->documentManager->generate($documentType, $generateFile, $documentKey);
        Yii::$app->getResponse()->sendFile($generateFile, null, $this->downloadOptions);
    }

    /**
     * @param string $documentType
     * @param $documentKey
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionPreview(string $documentType, $documentKey): string
    {
        Yii::$app->getResponse()->format = Response::FORMAT_HTML;
        return $this->documentManager->render($documentType, $documentKey);
    }
}
