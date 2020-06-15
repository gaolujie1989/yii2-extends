<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman\db;

use Exception;
use yii\base\NotSupportedException;
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
     * @return Transaction|null
     * @throws NotSupportedException
     * @throws \yii\db\Exception
     * @throws Exception
     * @inheritdoc
     */
    public function beginTransaction($isolationLevel = null)
    {
        try {
            return parent::beginTransaction($isolationLevel);
        } catch (Exception $e) {
            $exception = $this->getSchema()->convertException($e, 'BeginTransaction');
            if (ConnectionHelper::isLostConnection($exception, $this)) {
                return parent::beginTransaction($isolationLevel);
            }
            throw $e;
        }
    }
}