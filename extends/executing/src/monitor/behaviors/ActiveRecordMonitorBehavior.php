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
     * @var ExecutableExec[]
     */
    private $executableExecs = [];

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

        $executableExec = $this->executableExecs[$executable->getExecUid()]
            ?? ($this->executableExecClass::findOne($condition) ?: Yii::createObject($this->executableExecClass));

        if ($executableExec->getIsNewRecord()) {
            $executableExec->setAttributes($condition);
        }

        $executableExec->setAttributes($data);
        $executableExec->save(false);

        $this->executableExecs[$executable->getExecUid()] = $executableExec;

        while (count($this->executableExecs) > 10) {
            array_shift($this->executableExecs);
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
