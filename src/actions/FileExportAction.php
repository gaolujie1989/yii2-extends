<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\actions;

use lujie\data\exchange\FileExport;
use lujie\data\exchange\sources\QuerySource;
use lujie\extend\rest\IndexQueryPreparer;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\UnknownClassException;
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
     * @var FileExport
     */
    public $fileExport;

    public $path;

    /**
     * @var string
     */
    public $filePath = '/tmp/exports/{date}/tmp_{datetime}.xlsx';

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->queryPreparer = Instance::ensure($this->queryPreparer, IndexQueryPreparer::class);
        $this->fileExport = Instance::ensure($this->fileExport, FileExport::class);
        $this->fileExport->pipeline->filePathTemplate = $this->filePath;
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

        $query = $this->queryPreparer->prepare($this);
        $this->fileExport->source = new QuerySource(['query' => $query]);
        if ($this->fileExport->execute()) {
            $filePath = $this->fileExport->getFilePath();
            Yii::$app->getResponse()->sendFile($filePath);
            return;
        }
        throw new ServerErrorHttpException('Unknown Error');
    }
}
