<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\actions;

use lujie\data\exchange\FileExporter;
use lujie\data\exchange\sources\ActiveRecordSource;
use lujie\executing\Executor;
use lujie\extend\helpers\MemoryHelper;
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
     * @var array
     */
    public $sourceConfig = [
        'class' => ActiveRecordSource::class,
    ];

    /**
     * @var FileExporter
     */
    public $fileExporter;

    /**
     * @var Executor|string|null
     */
    public $executor = 'executor';

    /**
     * @var string
     */
    public $filePath = '@statics/exports/{datetime}_{rand}.xlsx';

    /**
     * @var string
     */
    public $exportFileName;

    /**
     * @var int
     */
    public $exportLimit = 0;

    /**
     * @var string
     */
    public $memoryLimit = '1024M';

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
        $oldMemoryLimit = ini_get('memory_limit');
        MemoryHelper::setMemoryLimit($this->memoryLimit);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        $fileExporter = $this->fileExporter;
        $query = $this->queryPreparer->prepare($this->modelClass, $requestParams);
        if ($this->exportLimit) {
            $query->limit($this->exportLimit);
        }
        $fileExporter->source = Yii::createObject(array_merge($this->sourceConfig, ['query' => $query]));
        $fileExporter->prepare($this->filePath);
        $executed = $this->executor
            ? $this->executor->execute($fileExporter)
            : $fileExporter->execute();
        if ($executed) {
            $filePath = $fileExporter->getFilePath();
            $response = Yii::$app->getResponse();
            if (is_file($filePath)) {
                $response->sendFile($filePath, $this->exportFileName);
            } else {
                $response->data = ['No Data'];
            }
            MemoryHelper::setMemoryLimit($oldMemoryLimit);
            return;
        }
        MemoryHelper::setMemoryLimit($oldMemoryLimit);
        throw new ServerErrorHttpException('Unknown Error');
    }
}
