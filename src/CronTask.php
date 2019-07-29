<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling;

use lujie\alias\behaviors\JsonAliasBehavior;
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
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'json' => [
                'class' => JsonAliasBehavior::class,
                'aliasProperties' => [
                    'executableJson' => 'executable'
                ]
            ]
        ]);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function fields(): array
    {
        return array_merge(parent::fields(), [
            'executableJson'
        ]);
    }
}
