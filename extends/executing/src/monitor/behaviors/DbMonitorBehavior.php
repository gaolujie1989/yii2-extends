<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing\monitor\behaviors;

use lujie\executing\ExecutableInterface;
use lujie\extend\constants\ExecStatusConst;
use yii\db\Connection;
use yii\db\Query;
use yii\di\Instance;

/**
 * Class DbMonitorBehavior
 * @package lujie\executing\monitor
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DbMonitorBehavior extends BaseMonitorBehavior
{
    /**
     * @var array
     */
    private $executableExecIds = [];

    /**
     * @var string
     */
    public $execTable = '{{%executable_exec}}';

    /**
     * @var Connection
     */
    public $db = 'db';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::class);
    }

    /**
     * @param ExecutableInterface $executable
     * @param string $executeManagerName
     * @param array $data
     * @throws \yii\db\Exception
     * @inheritdoc
     */
    protected function saveExec(ExecutableInterface $executable, string $executeManagerName, array $data): void
    {
        $condition = [
            'executable_id' => $executable->getId(),
            'executable_exec_uid' => $executable->getExecUid(),
            'executor' => $executeManagerName
        ];
        $execId = $this->executableExecIds[$executable->getExecUid()]
            ?? (new Query())
                ->from($this->execTable)
                ->andWhere($condition)
                ->scalar();

        if ($execId) {
            $this->db->createCommand()
                ->update($this->execTable, $data, ['executable_exec_id' => $execId])
                ->execute();
        } else {
            $columns = array_merge($data, $condition);
            $this->db->createCommand()
                ->insert($this->execTable, $columns)
                ->execute();
            $this->executableExecIds[$executable->getExecUid()] = $this->db->getLastInsertID();
        }

        while (count($this->executableExecIds) > 10) {
            array_shift($this->executableExecIds);
        }
    }

    /**
     * @param string|array $condition
     * @inheritdoc
     */
    protected function deleteExec($condition): void
    {
        $this->db->createCommand()->delete($this->execTable, $condition);
    }
}
