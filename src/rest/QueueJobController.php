<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\queuing\monitor\rest;

use lujie\extend\rest\ActiveController;
use lujie\queuing\monitor\models\QueueJob;
use yii\di\Instance;
use yii\queue\Queue;

/**
 * Class QueueJobController
 * @package lujie\queuing\monitor\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QueueJobController extends ActiveController
{
    public $modelClass = QueueJob::class;

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actions(): array
    {
        $actions = parent::actions();
        return array_intersect_key($actions, array_flip(['index']));
    }

    /**
     * @param int|string $id
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     * @inheritdoc
     */
    public function actionPush($id): array
    {
        /** @var QueueJob $queueJob */
        $queueJob = $this->findModel($id);
        /** @var Queue $queue */
        $queue = Instance::ensure($queueJob->queue);
        $job = $queue->serializer->unserialize($queueJob->job);
        $pushedJobId = $queue->push($job);
        return ['jobId' => $pushedJobId];
    }
}
