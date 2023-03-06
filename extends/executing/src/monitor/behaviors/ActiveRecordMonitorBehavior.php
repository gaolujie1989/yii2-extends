<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing\monitor\behaviors;

use lujie\executing\ExecutableInterface;
use lujie\executing\monitor\models\ExecutableExec;
use lujie\extend\constants\ExecStatusConst;
use Yii;

/**
 * Class MonitorBehavior
 * @package lujie\executing\monitor
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ActiveRecordMonitorBehavior extends BaseMonitorBehavior
{
    /**
     * @var ?ExecutableExec
     */
    private $executableExec;

    /**
     * @var ExecutableExec
     */
    public $executableExecClass = ExecutableExec::class;

    /**
     * @param ExecutableInterface $executable
     * @param string $executeManagerName
     * @param array $data
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function saveExec(ExecutableInterface $executable, string $executeManagerName, array $data): void
    {
        $condition = [
            'executable_id' => $executable->getId(),
            'executable_exec_uid' => $executable->getExecUid(),
            'executor' => $executeManagerName
        ];
        if (!$this->executableExec) {
            $this->executableExec = $this->executableExecClass::findOne($condition)
                ?: $this->executableExec = Yii::createObject($this->executableExecClass);

            if ($this->executableExec->getIsNewRecord()) {
                $this->executableExec->setAttributes($condition);
            }
        }
        $this->executableExec->setAttributes($data);
        $this->executableExec->save(false);

        if ($data['status'] !== ExecStatusConst::EXEC_STATUS_RUNNING) {
            $this->executableExec = null;
        }
    }

    /**
     * @param array $condition
     * @inheritdoc
     */
    protected function deleteExec(array $condition): void
    {
        $this->executableExecClass::deleteAll($condition);
    }
}
