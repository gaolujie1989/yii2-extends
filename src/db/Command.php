<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman\db;

use yii\db\Exception;

/**
 * Class Command
 * @package lujie\workerman\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Command extends \yii\db\Command
{
    /**
     * @param string $method
     * @param int $fetchMode
     * @return mixed
     * @throws Exception
     * @inheritdoc
     */
    protected function queryInternal($method, $fetchMode = null)
    {
        try {
            return parent::queryInternal($method, $fetchMode);
        } catch (Exception $e) {
            if ($this->isLostConnection($e)) {
                return parent::queryInternal($method, $fetchMode);
            }
            throw $e;
        }
    }

    /**
     * @return int
     * @throws Exception
     * @inheritdoc
     */
    public function execute()
    {
        try {
            return parent::execute();
        } catch (Exception $e) {
            if ($this->isLostConnection($e)) {
                return parent::execute();
            }
            throw $e;
        }
    }

    /**
     * @param Exception $exception
     * @param bool $resetConnection
     * @return bool
     * @throws Exception
     */
    protected function isLostConnection(Exception $exception, $resetConnection = true): bool
    {
        if (ConnectionHelper::isLostConnection($exception, $this->db, $resetConnection)) {
            $this->bindValues($this->params);
            $this->pdoStatement = null;
            return true;
        }
        return false;
    }
}
