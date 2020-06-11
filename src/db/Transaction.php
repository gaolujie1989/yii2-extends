<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman\db;

use yii\db\Exception;

/**
 * Class Transaction
 * @package lujie\workerman\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Transaction extends \yii\db\Transaction
{
    /**
     * @param null $isolationLevel
     * @throws Exception
     * @inheritdoc
     */
    public function begin($isolationLevel = null)
    {
        try {
            return parent::begin($isolationLevel);
        } catch (Exception $e) {
            if ($this->isLostConnection($e)) {
                return parent::begin($isolationLevel);
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
        return ConnectionHelper::isLostConnection($exception, $this->db, $resetConnection);
    }
}