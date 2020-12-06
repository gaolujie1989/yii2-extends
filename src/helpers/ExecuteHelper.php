<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use lujie\extend\constants\ExecStatusConst;
use Yii;
use yii\db\BaseActiveRecord;
use yii\httpclient\Exception;
use yii\queue\JobInterface;
use yii\queue\Queue;

/**
 * Class ExecuteHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExecuteHelper
{
    /**
     * @param Queue $queue
     * @param JobInterface $job
     * @param BaseActiveRecord $model
     * @param string $timeAttribute
     * @param string $statusAttribute
     * @param string $resultAttribute
     * @param int $queuedDuration
     * @return bool
     * @inheritdoc
     */
    public static function pushJob(
        Queue $queue,
        JobInterface $job,
        BaseActiveRecord $model,
        string $statusAttribute = 'execute_status',
        string $resultAttribute = 'execute_result',
        string $timeAttribute = 'updated_at',
        int $queuedDuration = 3600): bool
    {
        $statusValue = $model->getAttribute($statusAttribute);
        $resultValue = $model->getAttribute($resultAttribute) ?: [];
        if ($statusValue === ExecStatusConst::EXEC_STATUS_QUEUED) {
            if (!empty($resultValue['jobId'])) {
                $time = $model->getAttribute($timeAttribute);
                if (time() - $time < $queuedDuration) {
                    return false;
                }
            }
        }
        if ($jobId = $queue->push($job)) {
            $model->setAttribute($timeAttribute, time());
            $model->setAttribute($statusAttribute, ExecStatusConst::EXEC_STATUS_QUEUED);
            $model->setAttribute($resultAttribute, array_merge($resultValue, ['jobId' => $jobId]));
            return $model->save(false);
        }
        return false;
    }

    /**
     * @param callable $callable
     * @param BaseActiveRecord $model
     * @param string $timeAttribute
     * @param string $statusAttribute
     * @param string $resultAttribute
     * @param bool $throwException
     * @throws \Throwable
     * @inheritdoc
     */
    public static function execute(
        callable $callable,
        BaseActiveRecord $model,
        string $timeAttribute = 'execute_at',
        string $statusAttribute = 'execute_status',
        string $resultAttribute = 'execute_result',
        bool $throwException = false,
        array $warningExceptions = [Exception::class]): bool
    {
        $statusAttribute && $model->setAttribute($statusAttribute, ExecStatusConst::EXEC_STATUS_RUNNING);
        $model->save(false);

        try {
            $callable();
            if ($resultAttribute) {
                $resultValue = $model->getAttribute($resultAttribute) ?: [];
                unset($resultValue['error']);
                $model->setAttribute($resultAttribute, $resultValue);
            }
            //time attribute only update on success
            $timeAttribute && $model->setAttribute($timeAttribute, time());
            $statusAttribute && $model->setAttribute($statusAttribute, ExecStatusConst::EXEC_STATUS_SUCCESS);
            $model->save(false);
            return true;
        } catch (\Throwable $exception) {
            $message = '[' . get_class($exception) . ']' . $exception->getMessage() . "\n" . $exception->getTraceAsString();
            $resultAttribute && $model->setAttribute($resultAttribute, ['error' => mb_substr($message, 0, 1000)]);
            $statusAttribute && $model->setAttribute($statusAttribute, ExecStatusConst::EXEC_STATUS_FAILED);
            $model->save(false);
            foreach ($warningExceptions as $warningException) {
                if ($exception instanceof $warningException) {
                    Yii::warning($message, __METHOD__);
                    return false;
                }
            }
            if ($throwException) {
                throw $exception;
            }
            Yii::error($message, __METHOD__);
            return false;
        }
    }
}
