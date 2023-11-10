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
        $expressionParts = explode(' ', $this->expression);
        $firstPart = reset($expressionParts);
        if (str_starts_with($firstPart, '*/')) {
            return ((int)substr($firstPart, 2)) * 60 - 30;
        }
        if (is_numeric($firstPart)) {
            return 1800;
        }
        return $this->ttr;
    }
}
