<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman\db;

use Yii;
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
     * @param null $fetchMode
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
    public function execute(): int
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
     * @inheritdoc
     */
    protected function isLostConnection(Exception $exception, $resetConnection = true): bool
    {
        $errorCode = $exception->errorInfo[1];
        if ($errorCode === 2006 || $errorCode === 2013) {
            if ($resetConnection) {
                $this->db->close();
                $this->db->open();
                $this->bindValues($this->params);
                $this->pdoStatement = null;
            }
            Yii::warning("MYSQL ERROR: {$errorCode}. Connection Lost And Reset...", __METHOD__);
            return true;
        }
        return false;
    }
}
