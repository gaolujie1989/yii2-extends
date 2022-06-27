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
        Yii::$app->getResponse()->sendFile($generateFile, null, $this->downloadOptions);
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
