<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\rest;

use lujie\extend\helpers\PdfHelper;
use lujie\extend\helpers\TemplateHelper;
use Yii;
use yii\helpers\FileHelper;
use yii\rest\Action;
use yii\web\Response;

/**
 * Class DownloadPdfAction
 * @package lujie\extend\web
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DownloadPdfAction extends Action
{
    /**
     * @var string
     */
    public $viewPath;

    /**
     * @var string
     */
    public $view;

    /**
     * @var string
     */
    public $layout = 'layouts/main.php';

    /**
     * @var callable|array
     */
    public $fileNameCallback;

    /**
     * @var array
     */
    public $options = ['inline' => true];

    /**
     * @param $id
     * @return string|null
     * @throws \Exception
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     * @inheritdoc
     */
    public function run($id): ?string
    {
        $model = $this->findModel($id);
        $data = ['model' => $model];
        if (Yii::$app->getRequest()->getQueryParam('html')) {
            if ($this->layout) {
                $this->controller->layout = rtrim($this->viewPath, '/') . '/' . ltrim($this->layout, '/');
            }
            $this->controller->setViewPath($this->viewPath);
            $response = Yii::$app->getResponse();
            $response->format = Response::FORMAT_HTML;
            return $this->controller->render($this->view ?: $this->id, $data);
        }
        if (is_callable($this->fileNameCallback)) {
            $fileName = call_user_func($this->fileNameCallback, $model);
        } else {
            $fileName = TemplateHelper::generateRandomFileName('.pdf', $this->id);
        }
        $filePath = Yii::getAlias("@runtime/{$this->id}/{$fileName}");
        FileHelper::createDirectory(dirname($filePath));
        PdfHelper::writePdf($filePath, $data, $this->view, $this->viewPath);
        Yii::$app->getResponse()->sendFile($filePath, $fileName, $this->options);
    }
}
