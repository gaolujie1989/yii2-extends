<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\controllers\rest;

use lujie\data\recording\jobs\RecordingJob;
use lujie\data\recording\models\DataSource;
use lujie\extend\helpers\ExecuteHelper;
use lujie\extend\rest\ActiveController;
use yii\di\Instance;
use yii\queue\Queue;

/**
 * Class DataSourceController
 * @package kiwi\data\recording\controllers\backend
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class  DataSourceController extends ActiveController
{
    /**
     * @var string|DataSource
     */
    public $modelClass = DataSource::class;

    /**
     * @var Queue
     */
    public $queue = 'queue';

    /**
     * @var array
     */
    public $jobConfig = [];

    /**
     * @param int|string $id
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     * @inheritdoc
     */
    public function actionRecording($id): void
    {
        /** @var DataSource $model */
        $model = $this->findModel($id);

        $this->queue = Instance::ensure($this->queue, Queue::class);
        /** @var RecordingJob $job */
        $job = Instance::ensure($this->jobConfig, RecordingJob::class);
        $job->dataSourceId = $model->data_source_id;
        ExecuteHelper::pushJob($this->queue, $job, $model,
            'last_exec_at', 'last_exec_status', 'last_exec_result');
    }
}
