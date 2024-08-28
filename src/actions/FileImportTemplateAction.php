<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\data\exchange\actions;

use lujie\data\exchange\FileImporter;
use lujie\data\exchange\forms\FileImportForm;
use lujie\data\exchange\ModelFileImporter;
use Yii;
use yii\di\Instance;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class FileImportTemplateAction
 * @package lujie\data\exchange\actions
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileImportTemplateAction extends Action
{
    /**
     * @var FileImporter
     */
    public $fileImporter;

    /**
     * @var string
     */
    public $filePath = '@statics/imports/templates/{datetime}_{rand}.xlsx';

    /**
     * @var string
     */
    public $templateFileName;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->fileImporter = Instance::ensure($this->fileImporter, FileImporter::class);
    }

    /**
     * @throws NotFoundHttpException
     * @throws \Exception
     * @inheritdoc
     */
    public function run(): void
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $fileImporter = $this->fileImporter;
        if ($fileImporter instanceof ModelFileImporter) {
            $templateFile = $fileImporter->templateFile($this->filePath);
            Yii::$app->getResponse()->sendFile($templateFile, $this->templateFileName);
            return;
        }
        throw new NotFoundHttpException('No template');
    }
}
