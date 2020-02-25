<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use lujie\extend\constants\ExecStatusConst;
use yii\db\BaseActiveRecord;

/**
 * Class ExecuteHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExecuteHelper
{
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
        bool $throwException = true): void
    {
        $timeAttribute && $model->setAttribute($timeAttribute, time());
        $statusAttribute && $model->setAttribute($statusAttribute, ExecStatusConst::EXEC_STATUS_RUNNING);
        $model->save(false);

        try {
            $callable();
            $statusAttribute && $model->setAttribute($statusAttribute, ExecStatusConst::EXEC_STATUS_SUCCESS);
            $model->save(false);
        } catch (\Throwable $exception) {
            $message = $exception->getMessage() . "\n" . $exception->getTraceAsString();
            $resultAttribute && $model->setAttribute($resultAttribute, ['error' => mb_substr($message, 0, 1000)]);
            $statusAttribute && $model->setAttribute($statusAttribute, ExecStatusConst::EXEC_STATUS_FAILED);
            $model->save(false);
            if ($throwException) {
                throw $exception;
            }
        }
    }
}
