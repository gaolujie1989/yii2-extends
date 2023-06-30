<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\as2\tasks;

use AS2\MessageInterface;
use lujie\as2\As2Manager;
use lujie\as2\models\As2Message;
use lujie\executing\ProgressInterface;
use lujie\executing\ProgressTrait;
use lujie\extend\constants\ExecStatusConst;
use lujie\scheduling\CronTask;
use yii\di\Instance;

/**
 * Class ProcessAs2MessageTask
 * @package lujie\as2\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class As2MessageProcessTask extends CronTask implements ProgressInterface
{
    use ProgressTrait;

    /**
     * @var As2Manager
     */
    public $as2Manager = 'as2Manager';

    /**
     * @return \Generator
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function execute(): \Generator
    {
        $this->as2Manager = Instance::ensure($this->as2Manager, As2Manager::class);
        $query = As2Message::find()
            ->inboundDirection()
            ->processedStatus(ExecStatusConst::EXEC_STATUS_PENDING)
            ->status($this->as2Manager->allowProcessMessageStatus);
        $progress = $this->getProgress($query->count());
        foreach ($query->each() as $as2Message) {
            $this->as2Manager->processMessage($as2Message);
            $progress->done++;
            yield true;
        }
        yield true;
    }
}
