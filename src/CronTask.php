<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling;

use lujie\executing\ExecutableTrait;
use lujie\executing\FollowTaskInterface;
use lujie\executing\FollowTaskTrait;
use lujie\executing\LockableTrait;
use lujie\executing\QueueableTrait;
use yii\base\Model;

/**
 * Class Task
 * @package lujie\scheduling
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class CronTask extends Model implements ScheduleTaskInterface, FollowTaskInterface
{
    use CronScheduleTrait, ExecutableTrait, LockableTrait, QueueableTrait, FollowTaskTrait;

    /**
     * @return array
     * @inheritdoc
     */
    public function getParams(): array
    {
        return ['ttr', 'id'];
    }

    /**
     * @return int
     * @inheritdoc
     */
    public function getTtr(): int
    {
        if ($this->ttr > 0) {
            return $this->ttr;
        }
        $minutes = $this->getIntervalMinutes();
        if ($minutes === 60) {
            return 1800;
        }
        return $minutes * 60 - 30;
    }
}
