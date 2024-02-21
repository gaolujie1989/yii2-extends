<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\constants;

/**
 * Class ExecStatusConst
 * @package lujie\extend\constants
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExecStatusConst
{
    public const EXEC_STATUS_PENDING = 0; //wait to run
    public const EXEC_STATUS_RUNNING = 1; //running
    public const EXEC_STATUS_INVALID = 5; //failed by validation, should not retry
    public const EXEC_STATUS_PROCESSING = 9; //processing by external service, should queue(but not set status to queued) and wait for callback
    public const EXEC_STATUS_SUCCESS = 10;
    public const EXEC_STATUS_FAILED = 11; //failed by code exception, need retry(network) or fix, should throw exception and notify to developer
    public const EXEC_STATUS_SKIPPED = 12; //not execute by lock or data validation failed
    public const EXEC_STATUS_QUEUED = 13;
    public const EXEC_STATUS_STOPPED = 14; //user abort in progress
}
