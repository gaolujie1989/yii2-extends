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
    use CronScheduleTrait, ExecutableTrait, LockableTrait, QueueableTrait, ScheduleSubTaskTrait;

    /**
     * @return int
     * @inheritdoc
     */
    public function getTtr(): int
    {
        if ($this->ttr > 0) {
            return $this->ttr;
        }
        $expressionParts = explode(' ', $this->expression);
        $firstPart = reset($expressionParts);
        if (str_starts_with($firstPart, '*/')) {
            $ttr = ((int)substr($firstPart, 2)) * 60 - 60;
            return $ttr < 300 ? 0 : $ttr;
        }
        if (is_numeric($firstPart)) {
            return 1800;
        }
        return $this->ttr;
    }
}
