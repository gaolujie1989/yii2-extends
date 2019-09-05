<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\monitor\behaviors;

use lujie\executing\ExecutableInterface;
use lujie\executing\monitor\behaviors\ActiveRecordMonitorBehavior as ExecutorActiveRecordMonitorBehavior;

/**
 * Class MonitorBehavior
 * @package lujie\executing\monitor
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordMonitorBehavior extends ExecutorActiveRecordMonitorBehavior
{
    use ExchangeMonitorTrait;

    /**
     * @param ExecutableInterface $executable
     * @param string $executeManagerName
     * @param array $data
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function saveExec(ExecutableInterface $executable, string $executeManagerName, array $data): void
    {
        $data = array_merge($data, $this->getExchangeAdditional($executable));
        parent::saveExec($executable, $executeManagerName, $data);
    }
}
