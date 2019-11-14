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
    public static function execute(
        callable $callable,
        BaseActiveRecord $model,
        string $timeAttribute = 'execute_at',
        string $statusAttribute = 'execute_status',
        string $resultAttribute = 'execute_result'): void
    {
        $model->setAttribute($timeAttribute, time());
        $model->setAttribute($statusAttribute, ExecStatusConst::EXEC_STATUS_RUNNING);
        $model->save(false);

        try {
            $callable();
            $model->setAttribute($statusAttribute, ExecStatusConst::EXEC_STATUS_SUCCESS);
            $model->save(false);
        } catch (\Throwable $exception) {
            $message = $exception->getMessage() . "\n" . $exception->getTraceAsString();
            $model->setAttribute($resultAttribute, ['error' => mb_substr($message, 0, 1000)]);
            $model->setAttribute($statusAttribute, ExecStatusConst::EXEC_STATUS_FAILED);
            $model->save(false);
        }
    }
}
