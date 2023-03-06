<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman\db;

use yii\base\NotSupportedException;
use yii\db\Exception;
use yii\db\Transaction;

/**
 * Class Connection
 * @package lujie\workerman\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class Connection extends \yii\db\Connection
{
    /**
     * @param null $isolationLevel
     * @return Transaction
     * @throws Exception
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function beginTransaction($isolationLevel = null): Transaction
    {
        try {
            return parent::beginTransaction($isolationLevel);
        } catch (\Exception $e) {
            $e = $this->getSchema()->convertException($e, 'BeginTransaction');
            if (ConnectionHelper::isLostConnection($e, $this)) {
                return parent::beginTransaction($isolationLevel);
            }
            throw $e;
        }
    }
}
