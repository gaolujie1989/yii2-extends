<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling;

use lujie\executing\ExecutableTrait;
use lujie\executing\LockableTrait;
use lujie\executing\QueueableTrait;
use yii\base\Model;

/**
 * Class Task
 * @package lujie\scheduling
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CronTask extends Model implements ScheduleTaskInterface
{
    use CronScheduleTrait, ExecutableTrait, LockableTrait, QueueableTrait;
}
