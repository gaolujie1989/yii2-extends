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
     * @var TemplateDocumentManager
     */
    public $documentManager = 'documentManager';

    /**
     * @var string
     */
    public $documentType;

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
     * @param $key
     * @param string $type
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     * @inheritdoc
     */
    public function actionDownload($key, ?string $type = null): void
    {
        $type = $type ?: $this->documentType;
        $data = ['{documentType}' => $type, '{documentKey}' => $key];
        $generateFile = Yii::getAlias(TemplateHelper::generate($this->filePathTemplate, $data));
        $this->documentManager->generate($type, $key, $generateFile);
        $ext = pathinfo($generateFile, PATHINFO_EXTENSION);
        $inline = in_array($ext, ['pdf', 'html'], true);
        Yii::$app->getResponse()->sendFile($generateFile, null, ['inline' => $inline]);
    }

    /**
     * @param $key
     * @param string $type
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actionPreview($key, ?string $type = null): string
    {
        $type = $type ?: $this->documentType;
        Yii::$app->getResponse()->format = Response::FORMAT_HTML;
        return $this->documentManager->render($type, $key);
    }
}
