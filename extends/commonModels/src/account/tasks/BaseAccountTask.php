<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\common\account\tasks;

use lujie\executing\ProgressInterface;
use lujie\scheduling\CronTask;

/**
 * Class BaseAccountTask
 * @package lujie\common\account\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseAccountTask extends CronTask implements ProgressInterface
{
    use BaseAccountTaskTrait, BaseAccountSubTaskTrait;
}
