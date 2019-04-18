<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling;


use yii\console\Controller;
use yii\di\Instance;

/**
 * Class SchedulerCommand
 * @package lujie\scheduling
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class SchedulerCommand extends Controller
{
    /**
     * @var Scheduler
     */
    public $scheduler;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->scheduler = Instance::ensure($this->scheduler, Scheduler::class);
    }


}
