<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\template\document\controllers\rest;

use creocoder\flysystem\Filesystem;
use lujie\extend\rest\ActiveController;
use lujie\extend\rest\MethodAction;
use lujie\template\document\forms\DocumentGenerateForm;
use lujie\template\document\models\DocumentFile;
use Yii;
use yii\di\Instance;

/**
 * Class DocumentFileController
 * @package lujie\template\document\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DocumentFileController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = DocumentFile::class;

    /**
     * @var Filesystem
     */
    public $fs = 'filesystem';

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actions(): array
    {
        $actions = array_merge(parent::actions(), [
            'generate' => [
                'class' => MethodAction::class,
                'modelClass' => DocumentGenerateForm::class,
                'method' => 'generate'
            ],
        ]);
        return array_intersect_key($actions, array_flip(['index', 'generate']));
    }

    /**
     * @param int $id
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\RangeNotSatisfiableHttpException
     * @inheritdoc
     */
    public function actionDownload(int $id): void
    {
        /** @var DocumentFile $model */
        $model = $this->findModel($id);
        $this->fs = Instance::ensure($this->fs, Filesystem::class);
        $content = $this->fs->read($model->document_file);
        $ext = pathinfo($model->document_file, PATHINFO_EXTENSION);
        $name = $model->document_no . $ext;
        $inline = in_array($ext, ['pdf', 'html'], true);
        Yii::$app->getResponse()->sendContentAsFile($content, $name, ['inline' => $inline]);
    }
}
