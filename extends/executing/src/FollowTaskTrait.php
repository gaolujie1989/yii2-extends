<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\executing;

use Generator;
use yii\di\Instance;

/**
 * Trait FollowTaskTrait
 * @package lujie\executing
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait FollowTaskTrait
{
    public $followTasks;

    /**
     * @return bool
     * @inheritdoc
     */
    public function shouldFollowTask(): bool
    {
        return !empty($this->followTasks);
    }

    /**
     * @return array|Generator
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function createFollowTasks(): array|Generator
    {
        foreach ($this->followTasks as $followTask) {
            yield Instance::ensure($followTask, ExecutableInterface::class);
        }
    }
}
