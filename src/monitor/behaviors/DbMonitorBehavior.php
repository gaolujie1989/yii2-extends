<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\monitor\behaviors;

use lujie\executing\ExecutableInterface;
use lujie\executing\monitor\behaviors\DbMonitorBehavior as ExecutorDbMonitorBehavior;

/**
 * Class DbMonitorBehavior
 * @package lujie\executing\monitor
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DbMonitorBehavior extends ExecutorDbMonitorBehavior
{
    use ExchangeMonitorTrait;

    /**
     * @param $taskCode
     * @param $scheduler
     * @param $data
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    protected function saveExec(ExecutableInterface $executable, string $executeManagerName, array $data): void
    {
        $data = array_merge($data, $this->getExchangeAdditional($executable));
        parent::saveExec($executable, $executeManagerName, $data);
    }
}
