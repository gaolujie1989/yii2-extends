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

    /**
     * @return int
     * @inheritdoc
     */
    public function getTtr(): int
    {
        if ($this->ttr > 0) {
            return $this->ttr;
        }
        if (str_starts_with($this->expression, '*/')) {
            return (int)substr($this->expression, 2) * 60 - 60;
        }
        return $this->ttr;
    }
}
