<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;

use lujie\extend\queue\RetryableJobTrait;
use yii\base\BaseObject;
use yii\di\Instance;
use yii\queue\JobInterface;
use yii\queue\RetryableJobInterface;

/**
 * Class ExecutableJob
 * @package lujie\executing
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ExecutableJob extends BaseObject implements JobInterface, RetryableJobInterface
{
    use RetryableJobTrait;

    /**
     * @var ExecutableInterface|array
     */
    public $executable;

    /**
     * @var Executor|string
     */
    public $executor;

    /**
     * @var int
     */
    public $ttr = 900;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->executable = Instance::ensure($this->executable, ExecutableInterface::class);
        $this->executable->getExecUid();
    }

    /**
     * @param \yii\queue\Queue $queue
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function execute($queue): void
    {
        $this->executor = Instance::ensure($this->executor, Executor::class);
        $this->executor->execute($this->executable);
    }
}
