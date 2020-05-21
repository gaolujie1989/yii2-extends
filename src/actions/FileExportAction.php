<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\actions;

use lujie\data\exchange\FileExporter;
use lujie\data\exchange\sources\ActiveRecordSource;
use lujie\executing\Executor;
use lujie\extend\rest\IndexQueryPreparer;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;

/**
 * Class FileExportAction
 * @package lujie\data\exchange\actions
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileExportAction extends Action
{
    /**
     * @var IndexQueryPreparer
     */
    public $queryPreparer = IndexQueryPreparer::class;

    /**
     * @var FileExporter
     */
    public $fileExporter;

    /**
     * @var Executor
     */
    public $executor = 'executor';

    /**
     * @var string
     */
    public $path;

    /**
     * @var string
     */
    public $filePath = '/tmp/exports/{date}/tmp_{datetime}.xlsx';

    /**
     * @var string
     */
    public $exportFileName;

    /**
     * @var int
     */
    public $exportLimit = 9000;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->queryPreparer = Instance::ensure($this->queryPreparer, IndexQueryPreparer::class);
        $this->fileExporter = Instance::ensure($this->fileExporter, FileExporter::class);
        if ($this->executor && Yii::$app->has($this->executor)) {
            $this->executor = Instance::ensure($this->executor, Executor::class);
        } else {
            $this->executor = null;
        }
    }

    /**
     * @throws InvalidConfigException
     * @throws ServerErrorHttpException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function run(): void
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        $fileExporter = $this->fileExporter;
        $query = $this->queryPreparer->prepare($this->modelClass, $requestParams);
        $query->limit($this->exportLimit);
        $fileExporter->source = new ActiveRecordSource(['query' => $query]);
        $fileExporter->prepare($this->filePath);
        $executed = $this->executor
            ? $this->executor->execute($fileExporter)
            : $fileExporter->execute();
        if ($executed) {
            $filePath = $fileExporter->getFilePath();
            Yii::$app->getResponse()->sendFile($filePath, $this->exportFileName);
            return;
        }
        throw new ServerErrorHttpException('Unknown Error');
    }
}
