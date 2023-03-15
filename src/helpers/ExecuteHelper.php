<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use lujie\extend\constants\ExecStatusConst;
use Yii;
use yii\db\BaseActiveRecord;
use yii\db\Query;
use yii\helpers\Json;
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
        Queue            $queue,
        JobInterface     $job,
        BaseActiveRecord $model,
        string           $statusAttribute = 'execute_status',
        string           $resultAttribute = 'execute_result',
        string           $timeAttribute = 'updated_at',
        int              $queuedDuration = 3600,
        int              $pushDelay = 0,
    ): bool
    {
        $statusValue = $model->getAttribute($statusAttribute);
        $resultValue = $model->getAttribute($resultAttribute) ?: [];
        if (($statusValue === ExecStatusConst::EXEC_STATUS_QUEUED) && !empty($resultValue['jobId'])) {
            $time = $model->getAttribute($timeAttribute);
            if (time() - $time < $queuedDuration) {
                return false;
            }
        }
        if ($jobId = $queue->delay($pushDelay)->push($job)) {
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
     * @param array|string[] $warningExceptions
     * @param string|null $memoryLimit
     * @return bool
     * @throws \Throwable
     * @inheritdoc
     */
    public static function execute(
        callable         $callable,
        BaseActiveRecord $model,
        string           $timeAttribute = 'execute_at',
        string           $statusAttribute = 'execute_status',
        string           $resultAttribute = 'execute_result',
        bool             $throwException = false,
        array            $warningExceptions = [Exception::class],
        ?string          $memoryLimit = null
    ): bool
    {
        $statusAttribute && $model->setAttribute($statusAttribute, ExecStatusConst::EXEC_STATUS_RUNNING);
        $model->save(false);

        if ($memoryLimit) {
            $oldMemoryLimit = ini_get('memory_limit');
            ini_set('memory_limit', $memoryLimit);
        }
        try {
            if ($resultAttribute) {
                $resultValue = $model->getAttribute($resultAttribute) ?: [];
                unset($resultValue['error'], $resultValue['trace']);
                $model->setAttribute($resultAttribute, $resultValue);
            }
            $callable();
            //time attribute only update on success
            if ($model->hasErrors()) {
                $statusAttribute && $model->setAttribute($statusAttribute, ExecStatusConst::EXEC_STATUS_INVALID);
                if ($resultAttribute) {
                    $resultValue = $model->getAttribute($resultAttribute) ?: [];
                    $resultValue = array_merge($resultValue, [
                        'error' => $model->getFirstError(),
                        'errors' => $model->getErrors(),
                    ]);
                    $model->setAttribute($resultAttribute, $resultValue);
                    $modelClass = get_class($model);
                    $primaryKey = implode(',', $model->getPrimaryKey(true));
                    $message = "Execute {$modelClass}:{$primaryKey} with errors: " . Json::encode($model->getErrors());
                    Yii::error($message, __METHOD__);
                }
            } else {
                $timeAttribute && $model->setAttribute($timeAttribute, time());
                $statusAttribute && $model->setAttribute($statusAttribute, ExecStatusConst::EXEC_STATUS_SUCCESS);
            }
            $model->save(false);
            return true;
        } catch (ExecuteException $exception) {
            $statusAttribute && $model->setAttribute($statusAttribute, $exception->status);
            $message = ExceptionHelper::getMessage($exception);
            if ($resultAttribute && $exception->result) {
                $resultValue = $model->getAttribute($resultAttribute) ?: [];
                $resultValue = array_merge($resultValue, [
                    'error' => $exception->getMessage(),
                    'trace' => $message,
                ], $exception->result);
                $model->setAttribute($resultAttribute, $resultValue);
            }
            $model->save(false);
            Yii::error($message, __METHOD__);
            return false;
        } catch (\Throwable $exception) {
            $statusAttribute && $model->setAttribute($statusAttribute, ExecStatusConst::EXEC_STATUS_FAILED);
            $message = ExceptionHelper::getMessage($exception);
            if ($resultAttribute) {
                $resultValue = $model->getAttribute($resultAttribute) ?: [];
                $resultValue = array_merge($resultValue, [
                    'error' => $exception->getMessage(),
                    'trace' => $message,
                ]);
                $model->setAttribute($resultAttribute, $resultValue);
            }
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
        } finally {
            if (isset($oldMemoryLimit)) {
                $memoryUsage = memory_get_usage(true);
                if (MemoryHelper::getMemory($oldMemoryLimit) >= $memoryUsage) {
                    ini_set('memory_limit', $oldMemoryLimit);
                } else {
                    ini_set('memory_limit', MemoryHelper::getAllowedMemoryLimit($memoryUsage));
                }
            }
        }
    }

    /**
     * @param Query $query
     * @param string $statusAttribute
     * @param int $queuedDuration
     * @param string $updateAtAttribute
     * @inheritdoc
     */
    public static function queryNotQueuedOrQueuedButNotExecuted(
        Query  $query,
        string $statusAttribute = 'execute_status',
        int    $queuedDuration = 3600,
        string $updateAtAttribute = 'updated_at'
    ): void
    {
        $query->andWhere(['OR',
            ['!=', $statusAttribute, ExecStatusConst::EXEC_STATUS_QUEUED],
            ['AND',
                [$statusAttribute => ExecStatusConst::EXEC_STATUS_QUEUED, ExecStatusConst::EXEC_STATUS_RUNNING],
                ['<=', $updateAtAttribute, time() - $queuedDuration],
            ]
        ]);
    }
}
